<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Decorative\DecProductSpecRow;
use App\Models\Decorative\DecProductVariant;
use App\Models\Decorative\DecSpecTemplate;

class DecorativeSpecController extends Controller
{
    public function getRows($variantId)
    {
        $rows = DecProductSpecRow::with('attribute')
            ->where('variant_id', $variantId)
            ->orderBy('section')
            ->orderBy('order', 'asc')
            ->get()
            ->map(function($row) {
                $row->label = $row->attribute ? $row->attribute->name : '';
                return $row;
            })
            ->groupBy('section');

        return response()->json(['success' => true, 'rows' => $rows]);
    }

    public function store(Request $request, $variantId)
    {
        $request->validate([
            'section' => 'required|in:basic_specifications,dimensions',
            'dec_spec_attribute_id' => 'required|exists:dec_spec_attributes,id',
            'value_type' => 'nullable|in:text,richtext'
        ]);

        $maxOrder = DecProductSpecRow::where('variant_id', $variantId)
            ->where('section', $request->section)
            ->max('order') ?? 0;

        // Sanitize richtext value to prevent XSS
        $allowedTags = '<b><strong><i><em><u><ul><ol><li><br><p><span><s><sub><sup>';
        $value = $request->value_type === 'richtext'
            ? strip_tags($request->value, $allowedTags)
            : $request->value;

        $row = DecProductSpecRow::create([
            'variant_id' => $variantId,
            'section'    => $request->section,
            'dec_spec_attribute_id' => $request->dec_spec_attribute_id,
            'value'      => $value,
            'value_type' => $request->value_type ?? 'text',
            'order'      => $maxOrder + 1,
        ]);

        $row->load('attribute');
        $row->label = $row->attribute ? $row->attribute->name : '';

        return response()->json(['success' => true, 'row' => $row]);
    }

    public function update(Request $request, $id)
    {
        $row = DecProductSpecRow::findOrFail($id);

        $request->validate([
            'dec_spec_attribute_id' => 'required|exists:dec_spec_attributes,id',
            'value_type' => 'nullable|in:text,richtext'
        ]);

        // Sanitize richtext value to prevent XSS
        $allowedTags = '<b><strong><i><em><u><ul><ol><li><br><p><span><s><sub><sup>';
        $value = $request->value_type === 'richtext'
            ? strip_tags($request->value, $allowedTags)
            : $request->value;

        $row->update([
            'dec_spec_attribute_id' => $request->dec_spec_attribute_id,
            'value'      => $value,
            'value_type' => $request->value_type ?? 'text',
        ]);

        $row->load('attribute');
        $row->label = $row->attribute ? $row->attribute->name : '';

        return response()->json(['success' => true, 'row' => $row]);
    }

    public function destroy($id)
    {
        $row = DecProductSpecRow::findOrFail($id);
        $row->delete();

        return response()->json(['success' => true, 'message' => 'Specification deleted.']);
    }

    public function reorder(Request $request, $variantId)
    {
        $orders = $request->orders; // [id => order]
        
        if (is_array($orders)) {
            foreach ($orders as $id => $order) {
                DecProductSpecRow::where('id', $id)
                    ->where('variant_id', $variantId)
                    ->update(['order' => $order]);
            }
            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false], 400);
    }

    public function copyFromVariant(Request $request, $variantId)
    {
        $targetVariant = DecProductVariant::findOrFail($variantId);
        $sourceVariantId = $request->source_variant_id;

        // Ownership check: source variant must belong to the same product
        $sourceVariant = DecProductVariant::where('id', $sourceVariantId)
            ->where('product_id', $targetVariant->product_id)
            ->first();

        if (!$sourceVariant) {
            return response()->json(['success' => false, 'message' => 'Invalid source variant.'], 403);
        }

        $sourceRows = DecProductSpecRow::where('variant_id', $sourceVariantId)->get();

        if ($sourceRows->isEmpty()) {
            return response()->json(['success' => false, 'message' => 'Source variant has no specifications.']);
        }

        // Delete existing rows before copying
        DecProductSpecRow::where('variant_id', $variantId)->delete();

        $allowedTags = '<b><strong><i><em><u><ul><ol><li><br><p><span><s><sub><sup>';
        foreach ($sourceRows as $row) {
            $value = $row->value_type === 'richtext' ? strip_tags($row->value, $allowedTags) : $row->value;
            DecProductSpecRow::create([
                'variant_id' => $variantId,
                'section'    => $row->section,
                'dec_spec_attribute_id' => $row->dec_spec_attribute_id,
                'value'      => $value,
                'value_type' => $row->value_type,
                'order'      => $row->order,
            ]);
        }

        return response()->json(['success' => true, 'message' => 'Specifications copied successfully.']);
    }

    public function getTemplates()
    {
        $templates = DecSpecTemplate::orderBy('name')->get();
        return response()->json(['success' => true, 'templates' => $templates]);
    }

    public function saveTemplate(Request $request, $variantId)
    {
        $request->validate(['name' => 'required|string|max:255']);
        
        $rows = DecProductSpecRow::where('variant_id', $variantId)->orderBy('order')->get();
        if ($rows->isEmpty()) {
            return response()->json(['success' => false, 'message' => 'No specifications to save.']);
        }

        $structure = [
            'basic_specifications' => $rows->where('section', 'basic_specifications')->map(function($r) {
                return ['dec_spec_attribute_id' => $r->dec_spec_attribute_id, 'value' => $r->value, 'value_type' => $r->value_type];
            })->values()->toArray(),
            'dimensions' => $rows->where('section', 'dimensions')->map(function($r) {
                return ['dec_spec_attribute_id' => $r->dec_spec_attribute_id, 'value' => $r->value, 'value_type' => $r->value_type];
            })->values()->toArray(),
        ];

        DecSpecTemplate::create([
            'name' => $request->name,
            'structure' => $structure
        ]);

        return response()->json(['success' => true, 'message' => 'Template saved successfully.']);
    }

    public function applyTemplate(Request $request, $variantId)
    {
        $template = DecSpecTemplate::findOrFail($request->template_id);
        $structure = $template->structure;

        if (empty($structure)) {
            return response()->json(['success' => false, 'message' => 'Template is empty.']);
        }

        // Delete existing rows before applying template
        DecProductSpecRow::where('variant_id', $variantId)->delete();

        foreach (['basic_specifications', 'dimensions'] as $section) {
            if (isset($structure[$section]) && is_array($structure[$section])) {
                foreach ($structure[$section] as $index => $row) {
                    $attrId = $row['dec_spec_attribute_id'] ?? null;
                    if (!$attrId && isset($row['label'])) {
                        $attr = \App\Models\Decorative\DecSpecAttribute::firstOrCreate(['name' => $row['label']]);
                        $attrId = $attr->id;
                    }

                    if ($attrId) {
                        DecProductSpecRow::create([
                            'variant_id' => $variantId,
                            'section' => $section,
                            'dec_spec_attribute_id' => $attrId,
                            'value' => $row['value'],
                            'value_type' => $row['value_type'] ?? 'text',
                            'order' => $index + 1,
                        ]);
                    }
                }
            }
        }

        return response()->json(['success' => true, 'message' => 'Template applied successfully.']);
    }
}
