<?php

namespace Database\Factories;
use App\Models\ShoppingList;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $shoppingLists = ShoppingList::all();

        return [
            'name' => $this->faker->word,
        ];
    }

    public function forShoppingList($shopping_list_id)
    {
        return $this->state([
            'shopping_list_id' => $shopping_list_id,
        ]);
    }
}
