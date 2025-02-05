<?php

use App\Http\Controllers\Category\CategoryController;
use App\Http\Controllers\Home\HomeController;
use App\Http\Controllers\Negotiation\NegotiationController;
use App\Http\Controllers\Negotiation\Product\NegotiationProductController;
use App\Http\Controllers\Party\PartyController;
use App\Http\Controllers\Product\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\User\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');


Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::resource('categories', CategoryController::class);

    Route::get('/users', [UserController::class, 'index'])->name('users.index');

    Route::get('/parties', [PartyController::class, 'index'])->name('parties.index');
    Route::post('/parties', [PartyController::class, 'store'])->name('parties.store');
    Route::patch('/parties/{party}', [PartyController::class, 'update'])->name('parties.update');
    Route::delete('/parties/{party}', [PartyController::class, 'destroy'])->name('parties.destroy');

    Route::get('/products', [ProductController::class, 'index'])->name('products.index');
    Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show');
    Route::post('/products', [ProductController::class, 'store'])->name('products.store');
    Route::put('/products/{product}', [ProductController::class, 'update'])->name('products.update');
    Route::delete('/products/{product}', [ProductController::class, 'destroy'])->name('products.destroy');
    Route::get('/products/supplier/products', [ProductController::class, 'getSupplierProductsView'])->name('products.supplier.products');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/negotiation', [NegotiationController::class, 'index'])->name('negotiation.index');
    Route::get('/negotiation/supplier', [NegotiationController::class, 'supplierIndex'])->name('negotiation.supplierIndex');
    Route::post('/negotiation/create', [NegotiationController::class, 'store'])->name('negotiation.store');
    Route::patch('/negotiation/{negotiation}', [NegotiationController::class, 'update'])->name('negotiation.update');
    Route::patch('/negotiation/{negotiation}/approve', [NegotiationController::class, 'approve'])->name('negotiation.approve');
    Route::patch('/negotiation/{negotiation}/decline', [NegotiationController::class, 'decline'])->name('negotiation.decline');
    Route::delete('/negotiation/{negotiation}', [NegotiationController::class, 'destroy'])->name('negotiation.destroy');

    Route::get('/negotiation/{negotiation}/products', [NegotiationProductController::class, 'index'])->name('negotiation-products.index');
    Route::post('/negotiation/{negotiation}/products', [NegotiationProductController::class, 'store'])->name('negotiation-products.store');
    Route::patch('/negotiation/{negotiation}/products/{product}', [NegotiationProductController::class, 'update'])->name('negotiation-products.update');
    Route::delete('/negotiation/{negotiation}/products/{product}', [NegotiationProductController::class, 'destroy'])->name('negotiation-products.destroy');
});

require __DIR__ . '/auth.php';
