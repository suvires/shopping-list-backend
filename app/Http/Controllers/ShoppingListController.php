<?php

namespace App\Http\Controllers;

use App\Models\ShoppingList;
use Illuminate\Http\Request;

class ShoppingListController extends Controller
{
    // List all shopping lists for the authenticated user
    public function index()
    {
        return auth()->user()->shoppingLists;
    }

    // Create a new shopping list
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $shoppingList = auth()->user()->shoppingLists()->create($request->all());
        return response()->json($shoppingList, 201);
    }

    // Display a specific shopping list
    public function show(ShoppingList $shoppingList)
    {
        return $shoppingList;
    }

    // Update a specific shopping list
    public function update(Request $request, ShoppingList $shoppingList)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $shoppingList->update($request->all());
        return $shoppingList;
    }

    // Delete a specific shopping list
    public function destroy(ShoppingList $shoppingList)
    {
        $shoppingList->delete();
        return response()->json(null, 204);
    }
}
