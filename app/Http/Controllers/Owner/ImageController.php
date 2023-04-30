<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\Image;
use App\Models\Product;
use App\Services\ImageService;
use App\Http\Requests\UploadImageRequest;



class ImageController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:owners');

        $this->middleware(function ($request, $next) {
            // dd($request->route()->parameter('shop')); //文字列
            // dd(Auth::id()); //数字

            $id = $request->route()->parameter('image'); //imageのid取得

            if(!is_null($id)){ // null判定
            $imagesOwnerId = Image::findOrFail($id)->owner->id;
                $imagesId = (int)$imagesOwnerId; // キャスト 文字列→数値に型変換
                $ownerId = Auth::id();
                if($imagesId !== $ownerId){ // 同じでなかったら
                    abort(404); // 404画面表示
                }
            }
            return $next($request);
        });        
    }    


    public function index()
    {
        // ownerのid情報を取得
        $images = Image::where('owner_id', Auth::id())

        // 更新されたものを軸に降順にしている
        ->orderBy('updated_at', 'desc')
        ->paginate(20);

        return view('owner.images.index', 
        compact('images'));        
    }


    public function create()
    {
        return view('owner.images.create');        
    }


    public function store(UploadImageRequest $request)
    {

        // formからわたってきた"files[][image]"を取得
        $imageFiles = $request->file('files');
        
        if(!is_null($imageFiles)){

            // foreachで画像を一枚ずつ表示している
            foreach($imageFiles as $imageFile){

                // インタベーションで画像のサイズを設定し、アップロードしている
                $fileNameToStore = ImageService::upload($imageFile, 'products');    
                Image::create([
                    'owner_id' => Auth::id(),
                    'filename' => $fileNameToStore  
                ]);
            }
        }

        return redirect()
        ->route('owner.images.index')
        ->with(['message' => '画像登録を実施しました。',
        'status' => 'info']);        
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        $image = Image::findOrFail($id);

        return view('owner.images.edit', 
        compact('image'));        
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'string|max:50'
        ]);

        $image = Image::findOrFail($id);
        $image->title = $request->title;
        $image->save();

        return redirect()
        ->route('owner.images.index')
        ->with(['message' => '画像情報を更新しました。',
        'status' => 'info']);        
    }

public function destroy($id)
{
    $image = Image::findOrFail($id);

    $imageInProducts = Product::where('image1', $image->id)
    ->orWhere('image2', $image->id)
    ->orWhere('image3', $image->id)
    ->orWhere('image4', $image->id)
    ->get();

    // eachをつかうとコレクションの中身を一つずつ処理ができる

    if($imageInProducts){
        $imageInProducts->each(function($product) use($image){
            if($product->image1 === $image->id){
                $product->image1 = null;
                $product->save();
            }
            if($product->image2 === $image->id){
                $product->image2 = null;
                $product->save();
            }
            if($product->image3 === $image->id){
                $product->image3 = null;
                $product->save();
            }
            if($product->image4 === $image->id){
                $product->image4 = null;
                $product->save();
            }
        });
    }        
    $filePath = 'public/products/' . $image->filename;

        // ファイルがあった場合、削除を行う
        if(Storage::exists($filePath)){
            Storage::delete($filePath);
        }

        Image::findOrFail($id)->delete(); 

        return redirect()
        ->route('owner.images.index')
        ->with(['message' => '画像を削除しました。',
        'status' => 'alert']);
    }
}
