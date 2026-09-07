<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\Tag;
use App\Models\SubTag;
use App\Models\ProductMaster;
use App\Models\ProductVariant;
use Illuminate\Support\Str;

class ProductsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->command->info('Starting ProductsSeeder...');

        // Create Categories
        $this->command->info('Creating categories...');
        $categories = $this->createCategories();
        
        // Create Tags
        $this->command->info('Creating tags...');
        $tags = $this->createTags();
        
        // Create Sub-Tags
        $this->command->info('Creating sub-tags...');
        $subTags = $this->createSubTags($tags);
        
        // Create Products with Variants
        $this->command->info('Creating products and variants...');
        $this->createProducts($categories, $subTags);
        
        $this->command->info('✅ ProductsSeeder completed successfully!');
        $this->command->info('📊 Summary:');
        $this->command->info('   - Categories: ' . count($categories));
        $this->command->info('   - Tags: ' . count($tags));
        $this->command->info('   - Sub-Tags: ' . count($subTags));
        $this->command->info('   - Products: ' . ProductMaster::count());
        $this->command->info('   - Variants: ' . ProductVariant::count());
        $this->command->info('');
        $this->command->info('🌐 Visit: http://localhost/products');
    }

    /**
     * Create Categories
     */
    private function createCategories()
    {
        $categoriesData = [
            ['title' => 'Downlighters', 'slug' => 'downlighters'],
            ['title' => 'Track Lights', 'slug' => 'track-lights'],
            ['title' => 'Spot Lights', 'slug' => 'spot-lights'],
            ['title' => 'Linear Lights', 'slug' => 'linear-lights'],
            ['title' => 'Wall Washers', 'slug' => 'wall-washers'],
            ['title' => 'Panel Lights', 'slug' => 'panel-lights'],
        ];

        $categories = [];
        foreach ($categoriesData as $data) {
            $category = Category::firstOrCreate(
                ['slug' => $data['slug']],
                [
                    'title' => $data['title'],
                    'uri' => $data['slug'],
                    'is_active' => 'yes',
                    'in_menu' => 'yes',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
            $categories[] = $category;
            $this->command->info("   ✓ Category: {$data['title']}");
        }

        return $categories;
    }

    /**
     * Create Tags
     */
    private function createTags()
    {
        $tagsData = [
            ['display_name' => 'Indoor Lighting', 'name' => 'indoor-lighting', 'slug' => 'indoor-lighting'],
            ['display_name' => 'Outdoor Lighting', 'name' => 'outdoor-lighting', 'slug' => 'outdoor-lighting'],
            ['display_name' => 'Commercial Lighting', 'name' => 'commercial-lighting', 'slug' => 'commercial-lighting'],
        ];

        $tags = [];
        foreach ($tagsData as $data) {
            $tag = Tag::firstOrCreate(
                ['slug' => $data['slug']],
                [
                    'display_name' => $data['display_name'],
                    'name' => $data['name'],
                    'is_active' => 'yes',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
            $tags[] = $tag;
            $this->command->info("   ✓ Tag: {$data['display_name']}");
        }

        return $tags;
    }

    /**
     * Create Sub-Tags (Product Lines)
     */
    private function createSubTags($tags)
    {
        $subTagsData = [
            [
                'tag_id' => $tags[0]->id, // Indoor
                'display_name' => 'LED Downlights',
                'name' => 'led-downlights',
                'slug' => 'led-downlights'
            ],
            [
                'tag_id' => $tags[0]->id, // Indoor
                'display_name' => 'Track Lights Series',
                'name' => 'track-lights-series',
                'slug' => 'track-lights-series'
            ],
            [
                'tag_id' => $tags[0]->id, // Indoor
                'display_name' => 'Spot Light Collection',
                'name' => 'spot-light-collection',
                'slug' => 'spot-light-collection'
            ],
            [
                'tag_id' => $tags[1]->id, // Outdoor
                'display_name' => 'Wall Washers',
                'name' => 'wall-washers',
                'slug' => 'wall-washers'
            ],
            [
                'tag_id' => $tags[2]->id, // Commercial
                'display_name' => 'Panel Lights Pro',
                'name' => 'panel-lights-pro',
                'slug' => 'panel-lights-pro'
            ],
        ];

        $subTags = [];
        foreach ($subTagsData as $data) {
            $subTag = SubTag::firstOrCreate(
                ['slug' => $data['slug']],
                [
                    'tag_id' => $data['tag_id'],
                    'display_name' => $data['display_name'],
                    'name' => $data['name'],
                    'is_active' => 'yes',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
            $subTags[] = $subTag;
            $this->command->info("   ✓ Sub-Tag: {$data['display_name']}");
        }

        return $subTags;
    }

    /**
     * Create Products with Variants
     */
    private function createProducts($categories, $subTags)
    {
        $productsData = [
            // Product 1: LED Downlight Series
            [
                'title' => 'Lux Downlight',
                'category_id' => $categories[0]->id,
                'sub_tag_id' => $subTags[0]->id,
                'description' => 'High-efficiency LED downlight for commercial and residential spaces',
                'variants' => [
                    ['name' => '6W', 'lumens' => '600', 'efficacy' => '100', 'beam_angle' => '60', 'led_power_watts' => '6', 'system_power_watts' => '6.5'],
                    ['name' => '9W', 'lumens' => '900', 'efficacy' => '100', 'beam_angle' => '60', 'led_power_watts' => '9', 'system_power_watts' => '9.5'],
                    ['name' => '12W', 'lumens' => '1200', 'efficacy' => '100', 'beam_angle' => '60', 'led_power_watts' => '12', 'system_power_watts' => '12.5'],
                    ['name' => '15W', 'lumens' => '1500', 'efficacy' => '100', 'beam_angle' => '60', 'led_power_watts' => '15', 'system_power_watts' => '15.5'],
                ]
            ],
            
            // Product 2: Track Light
            [
                'title' => 'ProTrack Spotlight',
                'category_id' => $categories[1]->id,
                'sub_tag_id' => $subTags[1]->id,
                'description' => 'Adjustable track light for accent and display lighting',
                'variants' => [
                    ['name' => '10W', 'lumens' => '1000', 'efficacy' => '100', 'beam_angle' => '24', 'led_power_watts' => '10', 'system_power_watts' => '10.5'],
                    ['name' => '15W', 'lumens' => '1500', 'efficacy' => '100', 'beam_angle' => '36', 'led_power_watts' => '15', 'system_power_watts' => '15.5'],
                    ['name' => '20W', 'lumens' => '2000', 'efficacy' => '100', 'beam_angle' => '24', 'led_power_watts' => '20', 'system_power_watts' => '20.5'],
                ]
            ],
            
            // Product 3: Spot Light
            [
                'title' => 'Elite Spot Light',
                'category_id' => $categories[2]->id,
                'sub_tag_id' => $subTags[2]->id,
                'description' => 'Precision spot light for art galleries and museums',
                'variants' => [
                    ['name' => '7W', 'lumens' => '700', 'efficacy' => '100', 'beam_angle' => '15', 'led_power_watts' => '7', 'system_power_watts' => '7.5'],
                    ['name' => '12W', 'lumens' => '1200', 'efficacy' => '100', 'beam_angle' => '24', 'led_power_watts' => '12', 'system_power_watts' => '12.5'],
                ]
            ],
            
            // Product 4: Wall Washer
            [
                'title' => 'Cascade Wall Washer',
                'category_id' => $categories[4]->id,
                'sub_tag_id' => $subTags[3]->id,
                'description' => 'High-power wall washer for outdoor architectural lighting',
                'variants' => [
                    ['name' => '18W', 'lumens' => '1800', 'efficacy' => '100', 'beam_angle' => '120', 'led_power_watts' => '18', 'system_power_watts' => '19'],
                    ['name' => '24W', 'lumens' => '2400', 'efficacy' => '100', 'beam_angle' => '120', 'led_power_watts' => '24', 'system_power_watts' => '25'],
                    ['name' => '36W', 'lumens' => '3600', 'efficacy' => '100', 'beam_angle' => '120', 'led_power_watts' => '36', 'system_power_watts' => '37'],
                ]
            ],
            
            // Product 5: Panel Light
            [
                'title' => 'Slim Panel Pro',
                'category_id' => $categories[5]->id,
                'sub_tag_id' => $subTags[4]->id,
                'description' => 'Ultra-slim panel light for modern office spaces',
                'variants' => [
                    ['name' => '18W - 300x300', 'lumens' => '1800', 'efficacy' => '100', 'beam_angle' => '120', 'led_power_watts' => '18', 'system_power_watts' => '19'],
                    ['name' => '36W - 600x600', 'lumens' => '3600', 'efficacy' => '100', 'beam_angle' => '120', 'led_power_watts' => '36', 'system_power_watts' => '37'],
                    ['name' => '40W - 300x1200', 'lumens' => '4000', 'efficacy' => '100', 'beam_angle' => '120', 'led_power_watts' => '40', 'system_power_watts' => '42'],
                ]
            ],
            
            // Product 6: Recessed Downlight
            [
                'title' => 'Zenith Recessed',
                'category_id' => $categories[0]->id,
                'sub_tag_id' => $subTags[0]->id,
                'description' => 'Recessed downlight with high CRI for color accuracy',
                'variants' => [
                    ['name' => '8W', 'lumens' => '800', 'efficacy' => '100', 'beam_angle' => '60', 'led_power_watts' => '8', 'system_power_watts' => '8.5'],
                    ['name' => '10W', 'lumens' => '1000', 'efficacy' => '100', 'beam_angle' => '60', 'led_power_watts' => '10', 'system_power_watts' => '10.5'],
                ]
            ],
            
            // Product 7: Linear Light
            [
                'title' => 'FlexiLine Linear',
                'category_id' => $categories[3]->id,
                'sub_tag_id' => $subTags[0]->id,
                'description' => 'Continuous linear lighting system for offices and retail',
                'variants' => [
                    ['name' => '20W - 600mm', 'lumens' => '2000', 'efficacy' => '100', 'beam_angle' => '120', 'led_power_watts' => '20', 'system_power_watts' => '21'],
                    ['name' => '30W - 900mm', 'lumens' => '3000', 'efficacy' => '100', 'beam_angle' => '120', 'led_power_watts' => '30', 'system_power_watts' => '31'],
                    ['name' => '40W - 1200mm', 'lumens' => '4000', 'efficacy' => '100', 'beam_angle' => '120', 'led_power_watts' => '40', 'system_power_watts' => '42'],
                ]
            ],
            
            // Product 8: Adjustable Downlight
            [
                'title' => 'Tilt Pro Downlight',
                'category_id' => $categories[0]->id,
                'sub_tag_id' => $subTags[0]->id,
                'description' => 'Adjustable beam downlight with tilt mechanism',
                'variants' => [
                    ['name' => '12W', 'lumens' => '1200', 'efficacy' => '100', 'beam_angle' => '38', 'led_power_watts' => '12', 'system_power_watts' => '12.5'],
                    ['name' => '18W', 'lumens' => '1800', 'efficacy' => '100', 'beam_angle' => '38', 'led_power_watts' => '18', 'system_power_watts' => '19'],
                ]
            ],
        ];

        foreach ($productsData as $index => $productData) {
            // Create Product
            $product = ProductMaster::create([
                'title' => $productData['title'],
                'category_id' => $productData['category_id'],
                'sub_tag_ids' => (string)$productData['sub_tag_id'],
                'featured_image' => 'sample-product.jpg', // Placeholder
                'icon_id' => 1,
                'project_id' => 1,
                'is_active' => 'yes',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $this->command->info("   ✓ Product {$index + 1}: {$productData['title']}");

            // Create Variants
            foreach ($productData['variants'] as $variantIndex => $variantData) {
                $slug = Str::slug($productData['title'] . ' ' . $variantData['name']);
                
                ProductVariant::create([
                    'product_id' => $product->id,
                    'variant_name' => $variantData['name'],
                    'slug' => $slug,
                    'led_fitted' => 'Yes',
                    'co_related_color' => '3000,4000,5000',
                    'lumens' => $variantData['lumens'],
                    'efficacy' => $variantData['efficacy'],
                    'beam_angle' => $variantData['beam_angle'],
                    'led_power_watts' => $variantData['led_power_watts'],
                    'system_power_watts' => $variantData['system_power_watts'],
                    'operating_voltage' => '220-240V',
                    'power_factor' => '>0.9',
                    'is_active' => 'yes',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                
                $this->command->info("      → Variant: {$variantData['name']}");
            }
        }
    }
}
