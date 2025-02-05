<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use App\Models\Product\Product;
use App\Models\Category\Category;
use Illuminate\View\View;

class HomeController extends Controller {
    public function index(): View {
        $products = Product::take(8)->get();
        $categories = Category::take(4)->get();

        return view('welcome', compact('products', 'categories'));
    }
}
