<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\ShoppingList;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $shoppingLists = ShoppingList::all();

        foreach ($shoppingLists as $shoppingList) {
            Product::factory()->count(5)->create(['shopping_list_id' => $shoppingList->id]);
        }
    }
}
