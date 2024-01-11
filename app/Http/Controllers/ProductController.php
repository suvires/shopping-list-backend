<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProductResource;
use Illuminate\Http\Request;
use App\Models\ShoppingList;
use App\Models\Product;

class ProductController extends Controller
{
    private $user;

    public function __construct()
    {
        $user = \App\Models\User::find(1);
        auth()->login($user);
        $this->user = auth()->user();
    }

    // List all products in the shopping list
    public function index(ShoppingList $shoppingList)
    {
        // Ensure the shopping list belongs to the authenticated user
        if (!$this->user->shoppingLists->contains($shoppingList)) {
            return response()->json(['error' => 'Shopping list not found for the user'], 404);
        }
        $products = $shoppingList->products()->whereIn('status', [0, 1])->get();
        $formattedProducts = ProductResource::collection($products);
        return response()->json($formattedProducts->response()->getData(), 200);
    }

    // Create a new product in the shopping list
    public function store(ShoppingList $shoppingList, Request $request)
    {
        // Ensure the shopping list belongs to the authenticated user
        if (!$this->user->shoppingLists->contains($shoppingList)) {
            return response()->json(['error' => 'Shopping list not found for the user'], 404);
        }

        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        //Get the shopping list id
        $shopping_list_id = $shoppingList->id;

        // Create the product
        $product = $shoppingList->products()->create([
            'name' => $request->input('name'),
            'shopping_list_id' => $shopping_list_id,
        ]);

        $formattedProduct = new ProductResource($product);

        return response()->json($formattedProduct->response()->getData(), 201);
    }


    // Display a specific product in the shopping list
    public function show(ShoppingList $shoppingList, Product $product)
    {
        // Ensure the shopping list belongs to the authenticated user
        if (!$this->user->shoppingLists->contains($shoppingList)) {
            return response()->json(['error' => 'Shopping list not found for the user'], 404);
        }

        // Ensure the product belongs to the specified shopping list
        if (!$shoppingList->products->contains($product)) {
            return response()->json(['error' => 'Product not found in the shopping list'], 404);
        }

        $formattedProduct = new ProductResource($product);
        return response()->json($formattedProduct->response()->getData(), 200);
    }

    // Update a specific product in the shopping list
    public function update(Request $request, ShoppingList $shoppingList, Product $product)
    {
        // Ensure the shopping list belongs to the authenticated user
        if (!$this->user->shoppingLists->contains($shoppingList)) {
            return response()->json(['error' => 'Shopping list not found for the user'], 404);
        }

        // Ensure the product belongs to the specified shopping list
        if (!$shoppingList->products->contains($product)) {
            return response()->json(['error' => 'Product not found in the shopping list'], 404);
        }

        //Ensure the new shopping list belongs to the authenticated user
        if ($request->input('shopping_list_id') && $shoppingList->id != $request->input('shopping_list_id')) {
            $newShoppingList = ShoppingList::find($request->input('shopping_list_id'));
            if (!$this->user->shoppingLists->contains($newShoppingList)) {
                return response()->json(['error' => 'New shopping list not found for the user'], 404);
            }
        }

        $rules = [
            'name' => 'required|string|max:255',
        ];

        if ($request->has('shopping_list_id')) {
            $rules['shopping_list_id'] = 'required|integer|exists:shopping_lists,id';
        }

        if ($request->has('status')) {
            $rules['status'] = 'required|integer';
        }

        $validator = \Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422); // 422 Unprocessable Entity
        }

        $product->name = $request->input('name');
        if ($request->has('shopping_list_id')) {
            $product->shopping_list_id = $request->input('shopping_list_id');
        }
        if ($request->has('status')) {
            $product->status = $request->input('status');
        }
        $product->save();

        $formattedProduct = new ProductResource($product);
        return response()->json($formattedProduct->response()->getData(), 200);
    }

    // Delete a specific product in the shopping list
    public function destroy(ShoppingList $shoppingList, Product $product)
    {
        // Ensure the shopping list belongs to the authenticated user
        if (!$this->user->shoppingLists->contains($shoppingList)) {
            return response()->json(['error' => 'Shopping list not found for the user'], 404);
        }

        // Ensure the product belongs to the specified shopping list
        if (!$shoppingList->products->contains($product)) {
            return response()->json(['error' => 'Product not found in the shopping list'], 404);
        }

        $product->delete();

        return response()->json('Product deleted', 204);
    }

    public function stash(ShoppingList $shoppingList)
    {
        // Ensure the shopping list belongs to the authenticated user
        if (!$this->user->shoppingLists->contains($shoppingList)) {
            return response()->json(['error' => 'Shopping list not found for the user'], 404);
        }

        $shoppingList->products()->where('status', 1)->update(['status' => 2]);

        return response()->json('Products stashed', 200);
    }
}
