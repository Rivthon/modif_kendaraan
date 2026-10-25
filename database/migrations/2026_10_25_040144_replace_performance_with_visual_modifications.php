<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $performanceCategory = DB::table('modification_categories')->where('slug', 'performa')->first();

        if (! $performanceCategory) {
            return;
        }

        DB::table('modification_categories')->where('id', $performanceCategory->id)->delete();

        $now = now();
        $visualCategories = [
            ['name' => 'Suspensi & Stance', 'slug' => 'suspensi-stance', 'icon' => '↓', 'sort_order' => 4],
            ['name' => 'Kap Mesin', 'slug' => 'kap-mesin', 'icon' => '◩', 'sort_order' => 5],
            ['name' => 'Decal & Livery', 'slug' => 'decal-livery', 'icon' => '✦', 'sort_order' => 6],
        ];

        foreach ($visualCategories as $category) {
            $categoryId = DB::table('modification_categories')->insertGetId([
                ...$category,
                'created_at' => $now,
                'updated_at' => $now,
            ]);

            foreach ($this->itemsFor($category['slug']) as $item) {
                DB::table('modification_items')->insert([
                    ...$item,
                    'category_id' => $categoryId,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('modification_categories')
            ->whereIn('slug', ['suspensi-stance', 'kap-mesin', 'decal-livery'])
            ->delete();

        $now = now();
        $categoryId = DB::table('modification_categories')->insertGetId([
            'name' => 'Performa', 'slug' => 'performa', 'icon' => '⚡', 'sort_order' => 4,
            'created_at' => $now, 'updated_at' => $now,
        ]);

        DB::table('modification_items')->insert([
            ['category_id' => $categoryId, 'vehicle_type' => 'mobil', 'name' => 'Stage 1 ECU Tune', 'slug' => 'stage-1-ecu-tune-mobil', 'price' => 7500000, 'preview_key' => 'engine', 'description' => 'Responsive power delivery', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['category_id' => $categoryId, 'vehicle_type' => 'motor', 'name' => 'Full System Exhaust', 'slug' => 'full-system-exhaust-motor', 'price' => 5200000, 'preview_key' => 'engine', 'description' => 'Sharper sound & response', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['category_id' => $categoryId, 'vehicle_type' => 'semua', 'name' => 'Stock Performance', 'slug' => 'stock-performance-semua', 'price' => 0, 'preview_key' => 'engine', 'description' => 'Tanpa perubahan performa', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
        ]);
    }

    /** @return array<int, array<string, int|string|bool|null>> */
    private function itemsFor(string $categorySlug): array
    {
        return match ($categorySlug) {
            'suspensi-stance' => [
                ['vehicle_type' => 'semua', 'name' => 'Standard Height', 'slug' => 'standard-height-semua', 'price' => 0, 'color' => null, 'preview_key' => 'stance', 'description' => 'Ketinggian bawaan', 'is_active' => true],
                ['vehicle_type' => 'semua', 'name' => 'Street Low', 'slug' => 'street-low-semua', 'price' => 6500000, 'color' => null, 'preview_key' => 'stance', 'description' => 'Lebih rendah dan padat', 'is_active' => true],
            ],
            'kap-mesin' => [
                ['vehicle_type' => 'mobil', 'name' => 'OEM Paint Hood', 'slug' => 'oem-paint-hood-mobil', 'price' => 0, 'color' => null, 'preview_key' => 'hood', 'description' => 'Kap warna bodi', 'is_active' => true],
                ['vehicle_type' => 'mobil', 'name' => 'Carbon Fiber Hood', 'slug' => 'carbon-fiber-hood-mobil', 'price' => 14500000, 'color' => null, 'preview_key' => 'hood', 'description' => 'Carbon twill gloss', 'is_active' => true],
            ],
            'decal-livery' => [
                ['vehicle_type' => 'semua', 'name' => 'Clean Look', 'slug' => 'clean-look-semua', 'price' => 0, 'color' => null, 'preview_key' => 'livery', 'description' => 'Tanpa grafis tambahan', 'is_active' => true],
                ['vehicle_type' => 'semua', 'name' => 'Midnight Racing Livery', 'slug' => 'midnight-racing-livery-semua', 'price' => 3800000, 'color' => null, 'preview_key' => 'livery', 'description' => 'Stripe dan grafis samping', 'is_active' => true],
            ],
            default => [],
        };
    }
};
