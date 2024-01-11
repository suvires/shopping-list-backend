<?php

use App\Http\Controllers\PantryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ShoppingListController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
//Route::middleware(['auth:sanctum'])->group(function () {
Route::group([], function () {
    // Routes for Pantry
    Route::put('/pantry/update-stock/{product}', [PantryController::class, 'updateStock']);
    Route::get('/pantry/return-to-shopping-list/{product}', [PantryController::class, 'returnToShopingList']);
    Route::get('/pantry', [PantryController::class, 'index']);

    // Routes for Products
    Route::get('/shopping-lists/{shoppingList}/products/stash', [ProductController::class, 'stash']);
    Route::get('/shopping-lists/{shoppingList}/products/{product}', [ProductController::class, 'show']);
    Route::get('/shopping-lists/{shoppingList}/products/', [ProductController::class, 'index']);
    Route::get('/shopping-lists/{shoppingList}/products/{product}', [ProductController::class, 'show']);
    Route::post('/shopping-lists/{shoppingList}/products/', [ProductController::class, 'store']);
    Route::put('/shopping-lists/{shoppingList}/products/{product}', [ProductController::class, 'update']);
    Route::delete('/shopping-lists/{shoppingList}/products/{product}', [ProductController::class, 'destroy']);

    // Routes for Shopping Lists
    Route::get('/shopping-lists/{shoppingList}', [ShoppingListController::class, 'show']);
    Route::put('/shopping-lists/{shoppingList}', [ShoppingListController::class, 'update']);
    Route::delete('/shopping-lists/{shoppingList}', [ShoppingListController::class, 'destroy']);
    Route::get('/shopping-lists', [ShoppingListController::class, 'index']);
    Route::post('/shopping-lists', [ShoppingListController::class, 'store']);
});

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});
