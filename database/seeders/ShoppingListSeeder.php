<?php

namespace Database\Seeders;

use App\Models\ShoppingList;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ShoppingListSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();

        foreach ($users as $user) {
            ShoppingList::factory()->create(['user_id' => $user->id]);
        }
    }
}
