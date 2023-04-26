<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Product;
use App\Models\Stock;
use App\Models\PrimaryCategory;


class NologinItemController extends Controller
{
    
    public function index(Request $request)
    {

        $categories = PrimaryCategory::with('secondary')
        ->get();

        $products = Product::AvailableItems()
        ->selectCategory($request->category ?? '0')
        ->searchKeyword($request->keyword)
        ->sortOrder($request->sort)
        ->paginate($request->pagination ?? '20');

        return view('welcome',
        compact('categories','products'));
    }

    public function show($id)
    {
        $product = Product::findOrFail($id);
        $quantity = Stock::where('product_id', $product->id)
        ->sum('quantity');

        if($quantity > 9){
            $quantity = 9;
        }

        return view('item.show', 
        compact('product', 'quantity'));
    }

}
