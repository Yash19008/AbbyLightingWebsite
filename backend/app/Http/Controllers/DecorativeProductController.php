<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DecorativeProduct;

class DecorativeProductController extends Controller
{
    public function index()
    {
        $products = DecorativeProduct::where('status', 'active')
            ->orderBy('sort_order')
            ->with('primaryImage')
            ->paginate(12);
            
        return view('pages.decorative-products.index', compact('products'));
    }

    public function show($slug)
    {
        $product = DecorativeProduct::where('slug', $slug)
            ->where('status', 'active')
            ->with(['images', 'attributeGroups.values', 'specificationSections.specifications'])
            ->firstOrFail();
            
        return view('pages.decorative-products.show', compact('product'));
    }
}
