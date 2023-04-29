<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use App\Models\Owner; // Eloquent エロクアント
use App\Models\Shop;
use Illuminate\Support\Facades\DB; // QueryBuilder クエリビルダ
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Throwable; 

class OwnersController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function index()
    {

        $owners = Owner::select('id','name', 'email', 'created_at')
        ->paginate(3);

        return view('admin.owners.index', 

        // compactで変数をviewに渡す
        compact('owners'));
    }

    public function create()
    {
        return view('admin.owners.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:owners',
            'password' => 'required|string|confirmed|min:8',
        ]);

        // ★トランザクション★
        // ownerを作った時点でshopも作成をする
        // エラーが出た場合、Throwableで例外取得

        try{
            DB::transaction(function () use($request) {
            $owner = Owner::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                ]);
        
                Shop::create([
                'owner_id' => $owner->id,
                'name' => '店名を入力してください',
                'information' => '',
                'filename' => '',
                'is_selling' => true,
                ]);
            }, 2);
                
            }catch(Throwable $e){
                Log::error($e);
                throw $e;
        }        

        return redirect()
        ->route('admin.owners.index')
        ->with(['message' => 'オーナー登録を実施しました。',
        'status' => 'info']);
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {

        // Ownerモデルのidを取得、なければ404エラーを返す
        $owner = Owner::findOrFail($id);

        return view('admin.owners.edit',
        compact('owner'));
    }

    public function update(Request $request, $id)
    {
        $owner = Owner::findOrFail($id);
        $owner->name = $request->name;
        $owner->email = $request->email;
        $owner->password = Hash::make($request->password);
        $owner->save();

        return redirect()
        ->route('admin.owners.index')
        ->with(['message' => 'オーナー情報を更新しました。',
        'status' => 'info']);
    }

    public function destroy($id)
    {
        Owner::findOrFail($id)->delete(); //ソフトデリート

        return redirect()
        ->route('admin.owners.index')
        ->with(['message' => 'オーナー情報を削除しました。',
        'status' => 'alert']);
    }

    
    public function expiredOwnerIndex(){

        // ソフトデリートしたもののみを表示させる
        $expiredOwners = Owner::onlyTrashed()->get();

        return view('admin.expired-owners', 
        compact('expiredOwners'));
    }
    
    public function expiredOwnerDestroy($id){

        // ソフトデリートしたものを、完全に削除する
        Owner::onlyTrashed()->findOrFail($id)->forceDelete();
        
        return redirect()
        ->route('admin.expired-owners.index')
        ->with(['message' => 'SHOPを削除しました。',
        'status' => 'alert']);
    }

    public function restoreExpiredOwner($id){
        $restoredOwner=Owner::onlyTrashed()->findOrFail($id)->restore();
        
        return redirect()->route('admin.owners.index',
        compact('restoredOwner'))
        ->with(['message' => 'SHOPを復元しました。',
        'status' => 'info']);
    }
}
