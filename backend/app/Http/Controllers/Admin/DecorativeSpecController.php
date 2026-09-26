<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Decorative\DecProductSpecRow;
use App\Models\Decorative\DecProduct;
use App\Models\Decorative\DecSpecTemplate;

class DecorativeSpecController extends Controller
{
    public function getRows($productId)
    {
        $rows = DecProductSpecRow::with('attribute')
            ->where('product_id', $productId)
            ->orderBy('section')
            ->orderBy('size_id')
            ->orderBy('order', 'asc')
            ->get()
            ->map(function($row) {
                $row->label = $row->attribute ? $row->attribute->name : '';
                return $row;
            });

        // Group into basic_specifications and dimensions (grouped by size_id)
        $basic = $rows->where('section', 'basic_specifications')->values();
        
        $dimensionsRaw = $rows->where('section', 'dimensions');
        $dimensionsGrouped = [];
        foreach ($dimensionsRaw as $row) {
            $attrId = $row->dec_spec_attribute_id;
            if (!isset($dimensionsGrouped[$attrId])) {
                $dimensionsGrouped[$attrId] = [
                    'dec_spec_attribute_id' => $attrId,
                    'label' => $row->label,
                    'order' => $row->order,
                    'values' => [],
                    'id' => $attrId // using attrId as unique row identifier for dimensions
                ];
            }
            $dimensionsGrouped[$attrId]['values'][$row->size_id] = [
                'id' => $row->id,
                'value' => $row->value,
                'value_type' => $row->value_type
            ];
        }
        $dimensions = array_values($dimensionsGrouped);
        // Sort by order
        usort($dimensions, function($a, $b) {
            return $a['order'] <=> $b['order'];
        });

        return response()->json([
            'success' => true, 
            'rows' => [
                'basic_specifications' => $basic,
                'dimensions' => $dimensions
            ]
        ]);
    }

    public function store(Request $request, $productId)
    {
        $request->validate([
            'section' => 'required|in:basic_specifications,dimensions',
            'dec_spec_attribute_id' => 'required|exists:dec_spec_attributes,id',
            'value_type' => 'nullable|in:text,richtext',
            'values' => 'nullable|array' // For dimensions: size_id => value
        ]);

        $allowedTags = '<b><strong><i><em><u><ul><ol><li><br><p><span><s><sub><sup>';
        $maxOrder = DecProductSpecRow::where('product_id', $productId)->where('section', $request->section)->max('order') ?? 0;
        
        $createdRows = [];

        if ($request->section === 'dimensions') {
            $values = $request->values ?? [];
            $firstRow = null;
            foreach ($values as $sizeId => $valueRaw) {
                $val = $request->value_type === 'richtext' ? strip_tags($valueRaw, $allowedTags) : $valueRaw;
                $row = DecProductSpecRow::create([
                    'product_id' => $productId,
                    'size_id'    => $sizeId,
                    'section'    => $request->section,
                    'dec_spec_attribute_id' => $request->dec_spec_attribute_id,
                    'value'      => $val,
                    'value_type' => $request->value_type ?? 'text',
                    'order'      => $maxOrder + 1,
                ]);
                $row->load('attribute');
                $row->label = $row->attribute ? $row->attribute->name : '';
                $createdRows[$sizeId] = $row;
                if (!$firstRow) $firstRow = $row;
            }
            return response()->json([
                'success' => true, 
                'row' => [
                    'id' => $request->dec_spec_attribute_id,
                    'dec_spec_attribute_id' => $request->dec_spec_attribute_id,
                    'label' => $firstRow ? $firstRow->label : '',
                    'order' => $maxOrder + 1,
                    'values' => $createdRows
                ]
            ]);
        }

        // Basic specs
        $value = $request->value_type === 'richtext'
            ? strip_tags($request->value, $allowedTags)
            : $request->value;

        $row = DecProductSpecRow::create([
            'product_id' => $productId,
            'size_id'    => null,
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

    public function update(Request $request, $productId, $id)
    {
        $request->validate([
            'section' => 'required|in:basic_specifications,dimensions',
            'dec_spec_attribute_id' => 'required|exists:dec_spec_attributes,id',
            'value_type' => 'nullable|in:text,richtext',
            'values' => 'nullable|array'
        ]);

        $allowedTags = '<b><strong><i><em><u><ul><ol><li><br><p><span><s><sub><sup>';

        if ($request->section === 'dimensions') {
            // $id is dec_spec_attribute_id
            $attrId = $id;
            $values = $request->values ?? [];

            // Delete existing rows for this attribute
            DecProductSpecRow::where('product_id', $productId)
                ->where('section', 'dimensions')
                ->where('dec_spec_attribute_id', $attrId)
                ->delete();

            $maxOrder = DecProductSpecRow::where('product_id', $productId)->where('section', 'dimensions')->max('order') ?? 0;
            
            $createdRows = [];
            $firstRow = null;

            foreach ($values as $sizeId => $valueRaw) {
                if (empty($valueRaw)) continue;
                $val = $request->value_type === 'richtext' ? strip_tags($valueRaw, $allowedTags) : $valueRaw;
                
                $row = DecProductSpecRow::create([
                    'product_id' => $productId,
                    'size_id'    => $sizeId,
                    'section'    => 'dimensions',
                    'dec_spec_attribute_id' => $request->dec_spec_attribute_id,
                    'value'      => $val,
                    'value_type' => $request->value_type ?? 'text',
                    'order'      => $maxOrder + 1,
                ]);
                $row->load('attribute');
                $row->label = $row->attribute ? $row->attribute->name : '';
                $createdRows[$sizeId] = $row;
                if (!$firstRow) $firstRow = $row;
            }

            return response()->json([
                'success' => true,
                'row' => [
                    'id' => $request->dec_spec_attribute_id,
                    'dec_spec_attribute_id' => $request->dec_spec_attribute_id,
                    'label' => $firstRow ? $firstRow->label : '',
                    'order' => $maxOrder + 1,
                    'values' => $createdRows
                ]
            ]);
        }

        // Basic Specs
        $row = DecProductSpecRow::findOrFail($id);
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

    public function destroy(Request $request, $productId, $id)
    {
        $section = $request->section ?? 'basic_specifications';

        if ($section === 'dimensions') {
            // $id is dec_spec_attribute_id
            DecProductSpecRow::where('product_id', $productId)
                ->where('section', 'dimensions')
                ->where('dec_spec_attribute_id', $id)
                ->delete();
        } else {
            $row = DecProductSpecRow::findOrFail($id);
            $row->delete();
        }

        return response()->json(['success' => true, 'message' => 'Specification deleted.']);
    }

    public function reorder(Request $request, $productId)
    {
        $orders = $request->orders; // [id => order]
        
        if (is_array($orders)) {
            foreach ($orders as $id => $order) {
                DecProductSpecRow::where('id', $id)
                    ->where('product_id', $productId)
                    ->update(['order' => $order]);
            }
            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false], 400);
    }

    public function copyFromProduct(Request $request, $productId)
    {
        $targetProduct = DecProduct::findOrFail($productId);
        $sourceProductId = $request->source_product_id;

        $sourceProduct = DecProduct::where('id', $sourceProductId)->first();

        if (!$sourceProduct) {
            return response()->json(['success' => false, 'message' => 'Invalid source product.'], 403);
        }

        $sourceRows = DecProductSpecRow::where('product_id', $sourceProductId)->get();

        if ($sourceRows->isEmpty()) {
            return response()->json(['success' => false, 'message' => 'Source product has no specifications.']);
        }

        // Delete existing rows before copying
        DecProductSpecRow::where('product_id', $productId)->delete();

        $targetSizes = \App\Models\Decorative\DecProductSize::where('product_id', $productId)->get();
        $sourceDimensions = $sourceRows->where('section', 'dimensions')->unique('dec_spec_attribute_id');
        $sourceBasic = $sourceRows->where('section', 'basic_specifications');

        $allowedTags = '<b><strong><i><em><u><ul><ol><li><br><p><span><s><sub><sup>';
        
        foreach ($sourceBasic as $row) {
            $value = $row->value_type === 'richtext' ? strip_tags($row->value, $allowedTags) : $row->value;
            DecProductSpecRow::create([
                'product_id' => $productId,
                'size_id'    => null,
                'section'    => $row->section,
                'dec_spec_attribute_id' => $row->dec_spec_attribute_id,
                'value'      => $value,
                'value_type' => $row->value_type,
                'order'      => $row->order,
            ]);
        }

        foreach ($sourceDimensions as $row) {
            if ($targetSizes->isEmpty()) {
                DecProductSpecRow::create([
                    'product_id' => $productId,
                    'size_id'    => null,
                    'section'    => 'dimensions',
                    'dec_spec_attribute_id' => $row->dec_spec_attribute_id,
                    'value'      => '',
                    'value_type' => $row->value_type,
                    'order'      => $row->order,
                ]);
            } else {
                foreach ($targetSizes as $size) {
                    DecProductSpecRow::create([
                        'product_id' => $productId,
                        'size_id'    => $size->id,
                        'section'    => 'dimensions',
                        'dec_spec_attribute_id' => $row->dec_spec_attribute_id,
                        'value'      => '',
                        'value_type' => $row->value_type,
                        'order'      => $row->order,
                    ]);
                }
            }
        }

        return response()->json(['success' => true, 'message' => 'Specifications copied successfully. Values for dimensions have been left blank.']);
    }

    public function getTemplates()
    {
        $templates = DecSpecTemplate::orderBy('name')->get();
        return response()->json(['success' => true, 'templates' => $templates]);
    }

    public function saveTemplate(Request $request, $productId)
    {
        $request->validate(['name' => 'required|string|max:255']);
        
        $rows = DecProductSpecRow::where('product_id', $productId)->orderBy('order')->get();
        if ($rows->isEmpty()) {
            return response()->json(['success' => false, 'message' => 'No specifications to save.']);
        }

        $structure = [
            'basic_specifications' => $rows->where('section', 'basic_specifications')->map(function($r) {
                return ['dec_spec_attribute_id' => $r->dec_spec_attribute_id, 'value' => $r->value, 'value_type' => $r->value_type];
            })->values()->toArray(),
            'dimensions' => $rows->where('section', 'dimensions')->unique('dec_spec_attribute_id')->map(function($r) {
                return ['dec_spec_attribute_id' => $r->dec_spec_attribute_id, 'value' => '', 'value_type' => $r->value_type];
            })->values()->toArray(),
        ];

        DecSpecTemplate::create([
            'name' => $request->name,
            'structure' => $structure
        ]);

        return response()->json(['success' => true, 'message' => 'Template saved successfully.']);
    }

    public function applyTemplate(Request $request, $productId)
    {
        $template = DecSpecTemplate::findOrFail($request->template_id);
        $structure = $template->structure;

        if (empty($structure)) {
            return response()->json(['success' => false, 'message' => 'Template is empty.']);
        }

        // Delete existing rows before applying template
        DecProductSpecRow::where('product_id', $productId)->delete();

        $targetSizes = \App\Models\Decorative\DecProductSize::where('product_id', $productId)->get();

        foreach (['basic_specifications', 'dimensions'] as $section) {
            if (isset($structure[$section]) && is_array($structure[$section])) {
                foreach ($structure[$section] as $index => $row) {
                    $attrId = $row['dec_spec_attribute_id'] ?? null;
                    if (!$attrId && isset($row['label'])) {
                        $attr = \App\Models\Decorative\DecSpecAttribute::firstOrCreate(['name' => $row['label']]);
                        $attrId = $attr->id;
                    }

                    if ($attrId) {
                        if ($section === 'dimensions' && $targetSizes->isNotEmpty()) {
                            foreach ($targetSizes as $size) {
                                DecProductSpecRow::create([
                                    'product_id' => $productId,
                                    'size_id'    => $size->id,
                                    'section' => $section,
                                    'dec_spec_attribute_id' => $attrId,
                                    'value' => '',
                                    'value_type' => $row['value_type'] ?? 'text',
                                    'order' => $index + 1,
                                ]);
                            }
                        } else {
                            DecProductSpecRow::create([
                                'product_id' => $productId,
                                'size_id'    => null, 
                                'section' => $section,
                                'dec_spec_attribute_id' => $attrId,
                                'value' => $section === 'dimensions' ? '' : ($row['value'] ?? ''),
                                'value_type' => $row['value_type'] ?? 'text',
                                'order' => $index + 1,
                            ]);
                        }
                    }
                }
            }
        }

        return response()->json(['success' => true, 'message' => 'Template applied successfully.']);
    }
}
