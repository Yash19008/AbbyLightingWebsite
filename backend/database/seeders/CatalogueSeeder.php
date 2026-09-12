<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Catalogue;
use App\Models\CatalogueCategory;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;

class CatalogueSeeder extends Seeder
{
    public function run()
    {
        $imagesDir = public_path('uploads/catalogues/images');
        $pdfsDir = public_path('uploads/catalogues/pdfs');

        if (!File::isDirectory($imagesDir)) {
            File::makeDirectory($imagesDir, 0777, true, true);
        }
        if (!File::isDirectory($pdfsDir)) {
            File::makeDirectory($pdfsDir, 0777, true, true);
        }

        // Get category IDs
        $archCat = CatalogueCategory::where('name', 'Architecture')->orWhere('slug', 'architecture')->first();
        $decoCat = CatalogueCategory::where('name', 'Decorative')->orWhere('slug', 'decorative')->first();
        $outCat = CatalogueCategory::where('name', 'Outdoor')->orWhere('slug', 'outdoor')->first();

        $items = [
            [
                'title' => 'Architectural Master Catalogue',
                'slug' => 'architectural-master-catalogue',
                'category_id' => $archCat ? $archCat->id : null,
                'cover_source' => base_path('../frontend/public/images/figma-update/catalogue.png'),
                'cover_filename' => 'catalogue-architectural.png',
                'file_size' => '24.5 MB',
                'description' => 'Comprehensive architectural lighting solutions for commercial and residential spaces.',
                'is_featured' => 1,
                'sort_order' => 1,
            ],
            [
                'title' => 'Black Jack Series',
                'slug' => 'black-jack-series',
                'category_id' => $archCat ? $archCat->id : null,
                'cover_source' => base_path('../frontend/public/images/reference/product-black-jack.png'),
                'cover_filename' => 'catalogue-black-jack.png',
                'file_size' => '12.8 MB',
                'description' => 'Precision engineered recessed and spotlight collection.',
                'is_featured' => 0,
                'sort_order' => 2,
            ],
            [
                'title' => 'Brava Outdoor Collection',
                'slug' => 'brava-outdoor-collection',
                'category_id' => $outCat ? $outCat->id : null,
                'cover_source' => base_path('../frontend/public/images/inspiration/look-04.png'),
                'cover_filename' => 'catalogue-brava.png',
                'file_size' => '18.2 MB',
                'description' => 'Weatherproof architectural landscape and facade illumination.',
                'is_featured' => 1,
                'sort_order' => 3,
            ],
            [
                'title' => 'Quarry Decorative Collection',
                'slug' => 'quarry-decorative-collection',
                'category_id' => $decoCat ? $decoCat->id : null,
                'cover_source' => base_path('../frontend/public/images/reference/product-quarry.png'),
                'cover_filename' => 'catalogue-quarry.png',
                'file_size' => '15.4 MB',
                'description' => 'Artisanal stone-inspired luxury pendant and surface luminaires.',
                'is_featured' => 1,
                'sort_order' => 4,
            ],
            [
                'title' => 'Neoma Lighting Collection',
                'slug' => 'neoma-lighting-collection',
                'category_id' => $decoCat ? $decoCat->id : null,
                'cover_source' => base_path('../frontend/public/images/reference/product-neoma.png'),
                'cover_filename' => 'catalogue-neoma.png',
                'file_size' => '14.1 MB',
                'description' => 'Contemporary geometric lighting fixtures for elevated interiors.',
                'is_featured' => 0,
                'sort_order' => 5,
            ],
            [
                'title' => 'Symphony Collection',
                'slug' => 'symphony-collection',
                'category_id' => $decoCat ? $decoCat->id : null,
                'cover_source' => base_path('../frontend/public/images/inspiration/look-03.png'),
                'cover_filename' => 'catalogue-symphony.png',
                'file_size' => '22.0 MB',
                'description' => 'Harmonious acoustic and decorative modular luminaire designs.',
                'is_featured' => 1,
                'sort_order' => 6,
            ],
            [
                'title' => 'Circulo Collection',
                'slug' => 'circulo-collection',
                'category_id' => $decoCat ? $decoCat->id : null,
                'cover_source' => base_path('../frontend/public/images/circulo-figma/collection.png'),
                'cover_filename' => 'catalogue-circulo.png',
                'file_size' => '16.7 MB',
                'description' => 'Pure circular geometry with warm ambient halo distribution.',
                'is_featured' => 0,
                'sort_order' => 7,
            ],
            [
                'title' => 'Stellar Architectural',
                'slug' => 'stellar-architectural',
                'category_id' => $archCat ? $archCat->id : null,
                'cover_source' => base_path('../frontend/public/images/reference/product-stellar.png'),
                'cover_filename' => 'catalogue-stellar.png',
                'file_size' => '19.3 MB',
                'description' => 'High-performance downlights with multiple beam spreads.',
                'is_featured' => 0,
                'sort_order' => 8,
            ],
            [
                'title' => 'Outdoor Architectural Series',
                'slug' => 'outdoor-architectural-series',
                'category_id' => $outCat ? $outCat->id : null,
                'cover_source' => base_path('../frontend/public/images/inspiration/look-02.png'),
                'cover_filename' => 'catalogue-outdoor.png',
                'file_size' => '21.5 MB',
                'description' => 'Bollards, in-grounds, and exterior linear floodlighting.',
                'is_featured' => 0,
                'sort_order' => 9,
            ],
        ];

        foreach ($items as $item) {
            $coverName = null;
            if (File::exists($item['cover_source'])) {
                File::copy($item['cover_source'], $imagesDir . '/' . $item['cover_filename']);
                $coverName = $item['cover_filename'];
            }

            Catalogue::updateOrCreate(
                ['slug' => $item['slug']],
                [
                    'catalogue_category_id' => $item['category_id'],
                    'title' => $item['title'],
                    'cover_image' => $coverName,
                    'file_size' => $item['file_size'],
                    'description' => $item['description'],
                    'is_featured' => $item['is_featured'],
                    'sort_order' => $item['sort_order'],
                    'status' => 'active',
                ]
            );
        }
    }
}
