<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $categories = [
            [
                'name' => 'Laptop',
                'slug' => 'laptop',
            ],
            [
                'name' => 'PC',
                'slug' => 'pc',
            ],
            [
                'name' => 'Tablet',
                'slug' => 'tablet',
            ],
        ];

        foreach ($categories as $item) {
            $category = new Category;
            $category = Category::updateOrCreate($item,
                $item
            );
            $category->status = Category::STATUS_ACTIVE;
            $category->save();
        }
    }
}
