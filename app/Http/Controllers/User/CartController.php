<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\User;
use App\Models\Stock;
use Illuminate\Support\Facades\Auth;
use App\Services\CartService;
use App\Jobs\SendThanksMail;
use App\Jobs\SendOrderedMail;


class CartController extends Controller
{

    public function index()
    {
        // ログインしているユーザー情報を取得
        $user = User::findOrFail(Auth::id());

        // ユーザーに紐づいているproductsを取得
        $products = $user->products;

        // 合計金額の初期値設定
        $totalPrice = 0;

        foreach($products as $product){
            $totalPrice += $product->price * $product->pivot->quantity;
        }

        return view('user.cart', 
            compact('products', 'totalPrice'));
    }

    public function add(Request $request){

        // カードに商品があるか確認
        // ＆条件で両方満たしていたら$itemInCartにデータが入る
        $itemInCart = Cart::where('product_id', $request->product_id)
        ->where('user_id', Auth::id())->first();

        // カートに商品があった場合、同じ商品であれば、
        // 数量を追加し、保存する
        if($itemInCart){
            $itemInCart->quantity += $request->quantity;
            $itemInCart->save();

        // カートに商品がなければ、
        // 新規でカートに商品を登録する
        } else {
            Cart::create([
                'user_id' => Auth::id(),
                'product_id' => $request->product_id,
                'quantity' => $request->quantity
            ]);
        }
        
        return redirect()->route('user.cart.index');
    }

    public function delete($id)
    {
        Cart::where('product_id', $id)
        ->where('user_id', Auth::id())
        ->delete();

        return redirect()->route('user.cart.index');
    }
    
    public function checkout()
    {

        // ログインしているアカウントと、userに紐づいている
        // 商品情報を取得している
        $user = User::findOrFail(Auth::id());
        $products = $user->products;
        
        $lineItems = [];
        foreach($products as $product){
            $quantity = '';
            $quantity = Stock::where('product_id', $product->id)->sum('quantity');

            // カート内の商品の数がStockテーブルより多かったら購入できないので、
            // user.cart.indexにリダイレクトで戻す
            if($product->pivot->quantity > $quantity){
                return redirect()->route('user.cart.index');
            
            } else {

                // $lineItemの連想配列のキーは、
                // stripeのリファレンスに合わせる必要がある
                $lineItem = [
                    'name' => $product->name,
                    'description' => $product->information,
                    'amount' => $product->price,
                    'currency' => 'jpy',
                    'quantity' => $product->pivot->quantity,
                ];
                
                array_push($lineItems, $lineItem);    
            }

        }

        // 在庫を減らす処理(stripeで決済をする前に、減算をする)
        foreach($products as $product){
            Stock::create([
                'product_id' => $product->id,
                'type' => \Constant::PRODUCT_LIST['reduce'],
                'quantity' => $product->pivot->quantity * -1
            ]);
        }

        // Stripe::setApiKeyはシークレットキーを取得する必要がある
        \Stripe\Stripe::setApiKey(env('STRIPE_SECRET_KEY'));

        $session = \Stripe\Checkout\Session::create([
            'payment_method_types' => ['card'],
            //foreachで格納した$lineItemsを受け取る
            'line_items' => [$lineItems],

            //1回払いの場合は,modeをpaymentとする
            'mode' => 'payment',

            //決済処理をした後のルーティングの処理
            'success_url' => route('user.cart.success'),
            'cancel_url' => route('user.cart.cancel'),
        ]);

        //公開キーを渡している
        $publicKey = env('STRIPE_PUBLIC_KEY');

        return view('user.checkout', 
            compact('session', 'publicKey'));
    }

    public function success()
    {
        ////
        $items = Cart::where('user_id', Auth::id())->get();
        $products = CartService::getItemsInCart($items);
        $user = User::findOrFail(Auth::id());

        SendThanksMail::dispatch($products, $user);
        foreach($products as $product)
        {
            SendOrderedMail::dispatch($product, $user);
        }
        ////

        // 処理成功後、カートに残っているものを削除するために、
        // Cartに紐づいているユーザー情報を削除する
        Cart::where('user_id', Auth::id())->delete();

        return redirect()->route('user.items.index');
    }

    public function cancel()
    {
        $user = User::findOrFail(Auth::id());

        foreach($user->products as $product){

            // キャンセルをしたら在庫を戻す
            Stock::create([
                'product_id' => $product->id,
                'type' => \Constant::PRODUCT_LIST['add'],
                'quantity' => $product->pivot->quantity
            ]);
        }

        return redirect()->route('user.cart.index');
    }
}
