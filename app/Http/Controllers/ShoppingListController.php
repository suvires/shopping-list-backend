<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ShoppingList;
use App\Http\Resources\ShoppingListResource;

class ShoppingListController extends Controller
{
    private $user;

    public function __construct()
    {
        $user = \App\Models\User::find(1);
        auth()->login($user);
        $this->user = auth()->user();
    }

    // List all shopping lists for the authenticated user
    public function index()
    {
        $shoppingLists = $this->user->shoppingLists;
        $formattedShoppingLists = ShoppingListResource::collection($shoppingLists);
        return response()->json($formattedShoppingLists->response()->getData(), 200);
    }

    // Create a new shopping list
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $shoppingList = $this->user->shoppingLists()->create($request->all());

        $formattedShoppingList = new ShoppingListResource($shoppingList);
        return response()->json($formattedShoppingList->response()->getData(), 201);
    }

    // Display a specific shopping list
    public function show(ShoppingList $shoppingList)
    {
        // Ensure the shopping list belongs to the authenticated user
        if (!$this->user->shoppingLists->contains($shoppingList)) {
            return response()->json(['error' => 'Shopping list not found for the user'], 404);
        }

        $formattedShoppingList = new ShoppingListResource($shoppingList);
        return response()->json($formattedShoppingList->response()->getData(), 200);
    }

    // Update a specific shopping list
    public function update(Request $request, ShoppingList $shoppingList)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        // Ensure the shopping list belongs to the authenticated user
        if (!$this->user->shoppingLists->contains($shoppingList)) {
            return response()->json(['error' => 'Shopping list not found for the user'], 404);
        }

        $shoppingList->update($request->all());

        $formattedShoppingList = new ShoppingListResource($shoppingList);
        return response()->json($formattedShoppingList->response()->getData(), 200);
    }

    // Delete a specific shopping list
    public function destroy(ShoppingList $shoppingList)
    {
        // Ensure the shopping list belongs to the authenticated user
        if (!$this->user->shoppingLists->contains($shoppingList)) {
            return response()->json(['error' => 'Shopping list not found for the user'], 404);
        }

        $shoppingList->delete();

        return response()->json(null, 204);
    }
}
