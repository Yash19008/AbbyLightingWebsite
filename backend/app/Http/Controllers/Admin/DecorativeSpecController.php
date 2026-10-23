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
            ->orderBy('order', 'asc')
            ->orderBy('id', 'asc')
            ->get()
            ->map(function($row) {
                $row->label = $row->attribute ? $row->attribute->name : '';
                return $row;
            });

        // Group into basic_specifications and dimensions (grouped by size_id)
        $basic = $rows->where('section', 'basic_specifications')->sortBy('order')->values();
        
        $dimensionsRaw = $rows->where('section', 'dimensions');
        $dimensionsGrouped = [];
        foreach ($dimensionsRaw as $row) {
            $attrId = $row->dec_spec_attribute_id;
            if (!isset($dimensionsGrouped[$attrId])) {
                $dimensionsGrouped[$attrId] = [
                    'dec_spec_attribute_id' => $attrId,
                    'label' => $row->label,
                    'order' => (int) ($row->order ?? 0),
                    'values' => [],
                    'id' => $attrId // using attrId as unique row identifier for dimensions
                ];
            } else if ($row->order && empty($dimensionsGrouped[$attrId]['order'])) {
                $dimensionsGrouped[$attrId]['order'] = (int) $row->order;
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
            if ($a['order'] === $b['order']) {
                return $a['id'] <=> $b['id'];
            }
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
            'value_type' => 'nullable|in:text,richtext,chips',
            'values' => 'nullable|array' // For dimensions: size_id => value
        ]);

        $allowedTags = '<b><strong><i><em><u><ul><ol><li><br><p><span><s><sub><sup>';
        $maxOrder = DecProductSpecRow::where('product_id', $productId)->where('section', $request->section)->max('order') ?? 0;
        
        $createdRows = [];

        if ($request->section === 'dimensions') {
            $values = $request->values ?? [];
            $firstRow = null;
            foreach ($values as $sizeId => $valueRaw) {
                $val = match($request->value_type) {
                    'richtext' => strip_tags($valueRaw, $allowedTags),
                    'chips'    => $valueRaw,
                    default    => $valueRaw,
                };
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
        $value = match($request->value_type) {
            'richtext' => strip_tags($request->value, $allowedTags),
            'chips'    => $request->value,
            default    => $request->value,
        };

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
            'value_type' => 'nullable|in:text,richtext,chips',
            'values' => 'nullable|array'
        ]);

        $allowedTags = '<b><strong><i><em><u><ul><ol><li><br><p><span><s><sub><sup>';

        if ($request->section === 'dimensions') {
            // $id is dec_spec_attribute_id
            $attrId = $id;
            $values = $request->values ?? [];

            // Preserve existing order before deleting
            $existingOrder = DecProductSpecRow::where('product_id', $productId)
                ->where('section', 'dimensions')
                ->where('dec_spec_attribute_id', $attrId)
                ->value('order');

            // Delete existing rows for this attribute
            DecProductSpecRow::where('product_id', $productId)
                ->where('section', 'dimensions')
                ->where('dec_spec_attribute_id', $attrId)
                ->delete();

            $order = $existingOrder !== null ? (int)$existingOrder : ((DecProductSpecRow::where('product_id', $productId)->where('section', 'dimensions')->max('order') ?? 0) + 1);
            
            $createdRows = [];
            $firstRow = null;

            foreach ($values as $sizeId => $valueRaw) {
                if (empty($valueRaw)) continue;
                $val = match($request->value_type) {
                    'richtext' => strip_tags($valueRaw, $allowedTags),
                    'chips'    => $valueRaw,
                    default    => $valueRaw,
                };
                
                $row = DecProductSpecRow::create([
                    'product_id' => $productId,
                    'size_id'    => $sizeId,
                    'section'    => 'dimensions',
                    'dec_spec_attribute_id' => $request->dec_spec_attribute_id,
                    'value'      => $val,
                    'value_type' => $request->value_type ?? 'text',
                    'order'      => $order,
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
                    'order' => $order,
                    'values' => $createdRows
                ]
            ]);
        }

        // Basic Specs
        $row = DecProductSpecRow::findOrFail($id);
        $value = match($request->value_type) {
            'richtext' => strip_tags($request->value, $allowedTags),
            'chips'    => $request->value,
            default    => $request->value,
        };

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
        $section = $request->section;
        $orders = $request->orders; // [id => order] or list of IDs
        $ids = $request->ids;       // List of IDs in order

        // If ids list is provided, convert to map [id => order]
        if (!empty($ids) && is_array($ids)) {
            $orders = [];
            foreach ($ids as $index => $id) {
                $orders[$id] = $index + 1;
            }
        }

        if (is_array($orders) && !empty($orders)) {
            \DB::transaction(function () use ($orders, $productId, $section) {
                if ($section === 'dimensions') {
                    foreach ($orders as $attrId => $order) {
                        DecProductSpecRow::where('product_id', $productId)
                            ->where('section', 'dimensions')
                            ->where('dec_spec_attribute_id', $attrId)
                            ->update(['order' => (int)$order]);
                    }
                } else if ($section === 'basic_specifications') {
                    foreach ($orders as $id => $order) {
                        DecProductSpecRow::where('product_id', $productId)
                            ->where('section', 'basic_specifications')
                            ->where('id', $id)
                            ->update(['order' => (int)$order]);
                    }
                } else {
                    // Fallback if section is not specified
                    foreach ($orders as $id => $order) {
                        $affected = DecProductSpecRow::where('product_id', $productId)
                            ->where('section', 'basic_specifications')
                            ->where('id', $id)
                            ->update(['order' => (int)$order]);

                        if ($affected === 0) {
                            DecProductSpecRow::where('product_id', $productId)
                                ->where('section', 'dimensions')
                                ->where('dec_spec_attribute_id', $id)
                                ->update(['order' => (int)$order]);
                        }
                    }
                }
            });

            return response()->json([
                'success' => true,
                'message' => 'Specifications reordered successfully'
            ]);
        }

        return response()->json(['success' => false, 'message' => 'No order data provided.'], 400);
    }

    public function copyFromProduct(Request $request, $productId)
    {
        $targetProduct = DecProduct::findOrFail($productId);
        $sourceProductId = $request->source_product_id;

        $sourceProduct = DecProduct::where('id', $sourceProductId)->first();

        if (!$sourceProduct) {
            return response()->json(['success' => false, 'message' => 'Invalid source product.'], 403);
        }

        $sourceRows = DecProductSpecRow::where('product_id', $sourceProductId)
            ->orderBy('order', 'asc')
            ->orderBy('id', 'asc')
            ->get();

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
            $value = match($row->value_type) {
                'richtext' => strip_tags($row->value, $allowedTags),
                'chips'    => $row->value,
                default    => $row->value,
            };
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
