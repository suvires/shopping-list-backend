<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    // List all products for the authenticated user
    public function index()
    {
        return auth()->user()->products;
    }

    // Create a new product
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:50',
            'shopping_list_id' => 'required|exists:shopping_lists,id',
        ]);

        $product = auth()->user()->products()->create($request->all());
        return response()->json($product, 201);
    }

    // Display a specific product
    public function show(Product $product)
    {
        return $product;
    }

    // Update a specific product
    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $product->update($request->all());
        return $product;
    }

    // Delete a specific product
    public function destroy(Product $product)
    {
        $product->delete();
        return response()->json(null, 204);
    }
}
