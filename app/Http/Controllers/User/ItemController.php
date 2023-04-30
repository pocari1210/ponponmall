<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Jobs\SendThanksMail;
use App\Mail\ThanksMail;
use App\Mail\TestMail;
use App\Models\Product;
use App\Models\Stock;
use App\Models\PrimaryCategory;


class ItemController extends Controller
{

    // 売り切れなど販売していない商品を検索した際、
    // 表示ができてしまう不具合を解消
    public function __construct()
    {
        $this->middleware('auth:users');

        $this->middleware(function ($request, $next) {

            $id = $request->route()->parameter('item'); 

            // $idがnullでない場合の処理
            if(!is_null($id)){

                // ルートパラメーターで入ってきたものが、availableItems()で
                // 表示できる商品かwhereで条件検索している
                $itemId = Product::availableItems()->where('products.id', $id)->exists();

                // $itemIdが存在しない場合、404エラーを返す
                if(!$itemId){ 
                    abort(404);
                }
            }
            return $next($request);
        });
    }
    
    public function index(Request $request)
    {

        //非同期に送信
        // SendThanksMail::dispatch();

        $categories = PrimaryCategory::with('secondary')
        ->get();

        $products = Product::AvailableItems()
        ->selectCategory($request->category ?? '0')
        ->searchKeyword($request->keyword)
        ->sortOrder($request->sort)
        ->paginate($request->pagination ?? '20');

        return view('user.items.index',
        compact('categories','products'));
    }

    public function show($id)
    {

        // 引数を$idとし、商品のid情報を取得
        $product = Product::findOrFail($id);
        $quantity = Stock::where('product_id', $product->id)
        ->sum('quantity');

        // 在庫の数が9個以上あった場合は、
        // ビューのプルダウンで選べる数を9で固定する
        if($quantity > 9){
            $quantity = 9;
        }

        return view('user.items.show', 
        compact('product', 'quantity'));
    }    
}
