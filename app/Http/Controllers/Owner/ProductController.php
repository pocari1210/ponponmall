<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\ProductRequest;
use App\Models\Image;
use App\Models\Product;
use App\Models\Stock;
use App\Models\Shop;
use App\Models\PrimaryCategory;
use App\Models\Owner;

class ProductController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:owners'); //ownerか否か確認

        $this->middleware(function ($request, $next) {

            $id = $request->route()->parameter('product'); 
            if(!is_null($id)){ 
            $productsOwnerId = Product::findOrFail($id)->shop->owner->id;
                $productId = (int)$productsOwnerId; 
                if($productId !== Auth::id()){ 
                    abort(404);
                }
            }
            return $next($request);
        });
    }
    
    public function index()
    {
        // Eager Loading(N + 1問題の対策)
        // リレーション先の情報を取得 
        $ownerInfo = Owner::with('shop.product.imageFirst')

        // ログインしているOwnerの情報を取得
        ->where('id', Auth::id())->get();

        return view('owner.products.index',
        compact('ownerInfo'));
    
    }

    public function create()
    {

        // ログインをしているowner_idで条件指定
        $shops = Shop::where('owner_id', Auth::id())

        // idとnameのカラムを選択している
        ->select('id', 'name')
        ->get();

        $images = Image::where('owner_id', Auth::id())
        ->select('id', 'title', 'filename')
        ->orderBy('updated_at', 'desc')
        ->get();

        // リレーション先を取得するのに、N+1問題があるため、
        // Eager Loadingとしてwithメソッドを用いて取得している
        $categories = PrimaryCategory::with('secondary')
        ->get();

        return view('owner.products.create', 
            compact('shops', 'images', 'categories'));
    }

    public function store(ProductRequest $request)
    {

        // トランザクションで1回の処理でProductとstockを
        // まとめて保存している
        try{
            DB::transaction(function () use($request) {
                $product = Product::create([
                    'name' => $request->name,
                    'information' => $request->information,
                    'price' => $request->price,
                    'sort_order' => $request->sort_order,
                    'shop_id' => $request->shop_id,
                    'secondary_category_id' => $request->category,
                    'image1' => $request->image1,
                    'image2' => $request->image2,
                    'image3' => $request->image3,
                    'image4' => $request->image4,
                    'is_selling' => $request->is_selling
                ]);

                Stock::create([
                    'product_id' => $product->id,
                    'type' => 1,
                    'quantity' => $request->quantity
                ]);
            }, 2);
        }catch(Throwable $e){
            Log::error($e);
            throw $e;
        }

        return redirect()
        ->route('owner.products.index')
        ->with(['message' => '商品登録しました。',
        'status' => 'info']);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $product = Product::findOrFail($id);

        // 量と在庫を合計した結果をsum('quantity')とする
        $quantity = Stock::where('product_id', $product->id)
        ->sum('quantity');

        $shops = Shop::where('owner_id', Auth::id())
        ->select('id', 'name')
        ->get();

        $images = Image::where('owner_id', Auth::id())
        ->select('id', 'title', 'filename')
        ->orderBy('updated_at', 'desc')
        ->get();

        $categories = PrimaryCategory::with('secondary')
        ->get();

        return view('owner.products.edit',
            compact('product', 'quantity', 'shops', 
            'images', 'categories'));        
    }

    public function update(ProductRequest $request, $id)
    {

        // ProductRequestに追加でバリデーションを行う
        $request->validate([
            'current_quantity' => 'required|integer',
        ]);

        $product = Product::findOrFail($id);
        $quantity = Stock::where('product_id', $product->id)
        ->sum('quantity');

        // ★楽観的ロック★
        // 画面表示後に在庫数が変わっている可能性がある 
        // (Edit～updateの間でユーザーが購入した場合など) 
        // 在庫が同じか確認し違っていたらeditに戻す
        if($request->current_quantity !== $quantity){
            $id = $request->route()->parameter('product');
            return redirect()->route('owner.products.edit', [ 'product' => $id])
            ->with(['message' => '在庫数が変更されています。再度確認してください。',
                'status' => 'alert']);            

        } else {

            // ProductとStockをトランザクションで同時更新する

            try{
                DB::transaction(function () use($request, $product) {
                    
                        $product->name = $request->name;
                        $product->information = $request->information;
                        $product->price = $request->price;
                        $product->sort_order = $request->sort_order;
                        $product->shop_id = $request->shop_id;
                        $product->secondary_category_id = $request->category;
                        $product->image1 = $request->image1;
                        $product->image2 = $request->image2;
                        $product->image3 = $request->image3;
                        $product->image4 = $request->image4;
                        $product->is_selling = $request->is_selling;
                        $product->save();

                    if($request->type === \Constant::PRODUCT_LIST['add']){
                        $newQuantity = $request->quantity;
                    }
                    if($request->type === \Constant::PRODUCT_LIST['reduce']){
                        $newQuantity = $request->quantity * -1;
                    }
                    
                    Stock::create([
                        'product_id' => $product->id,
                        'type' => $request->type,
                        'quantity' => $newQuantity
                    ]);
                }, 2);
            }catch(Throwable $e){
                Log::error($e);
                throw $e;
            }
    
            return redirect()
            ->route('owner.products.index')
            ->with(['message' => '商品情報を更新しました。',
            'status' => 'info']);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Product::findOrFail($id)->delete(); 

        return redirect()
        ->route('owner.products.index')
        ->with(['message' => '商品を削除しました。',
        'status' => 'alert']);
    }
}
