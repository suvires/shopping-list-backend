<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Http\Resources\ProductResource;

class PantryController extends Controller
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
        $products = $this->user->products()->where('status', 2)->get();
        $formattedProducts = ProductResource::collection($products);
        return response()->json($formattedProducts->response()->getData(), 200);
    }

    public function returnProductToShopingList(Product $product)
    {
        // Ensure the product belongs to the authenticated user
        if (!$this->user->products->contains($product)) {
            return response()->json(['error' => 'Product not found for the user'], 404);
        }

        $product->status = 0;
        $product->save();

        $formattedProduct = new ProductResource($product);

        return response()->json($formattedProduct->response()->getData(), 200);
    }
}
