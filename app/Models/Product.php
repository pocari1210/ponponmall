<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Shop;
use App\Models\SecondaryCategory;
use App\Models\Image;
use App\Models\Stock;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'shop_id',
        'name',
        'information',
        'price',
        'is_selling',
        'sort_order',
        'secondary_category_id',
        'image1',
        'image2',
        'image3',
        'image4',
    ];    

    public function shop()
    {
        return $this->belongsTo(Shop::class);
    }

    public function category()
    {
        return $this->belongsTo(SecondaryCategory::class, 'secondary_category_id');
    }


    // カラム名とテーブル名は同じ名称にできないため、
    // imageFirstとする
    public function imageFirst()
    {
        return $this->belongsTo(Image::class,'image1','id');
        // ⇒image1とidをつけていない場合、Laravel側で推測ができないため、
        // 第三引数をimageモデルのidとし、紐づけている
    }

    public function imageSecond()
    {
        return $this->belongsTo(Image::class,'image2','id');
    }

    public function imageThird()
    {
        return $this->belongsTo(Image::class,'image3','id');
    }

    public function imageFourth()
    {
        return $this->belongsTo(Image::class,'image4','id');
    }    
    
    public function stock()
    {
        return $this->hasMany(Stock::class);
    }

    public function users()
    {
        // ★中間テーブル★
        // belongsToManyは多対多のリレーションで使われる
        // withPivotで中間テーブル(carts)の必要なカラムを取得
        return $this->belongsToMany(User::class, 'carts')
        ->withPivot(['id', 'quantity']);
    }

    // ★商品を表示する条件を絞り込む★
    // shopが販売中で在庫がある商品を表示させる
    public function scopeAvailableItems($query)
    {
        $stocks = DB::table('t_stocks')
        ->select('product_id', 

        // 在庫の合計数をquantityとする
        DB::raw('sum(quantity) as quantity'))

        // product_idの重複を出さないようにしている
        ->groupBy('product_id')

        // 集計結果に対する絞り込みを行っている
        ->having('quantity', '>', 1);

        return $query

        // products.idとstock.product.id
        // を紐づける(テーブル結合)
        ->joinSub($stocks, 'stock', function($join){
            $join->on('products.id', '=', 'stock.product_id');
        })

        // join句でproductsと紐づけている
        ->join('shops', 'products.shop_id', '=', 'shops.id')
        ->join('secondary_categories', 'products.secondary_category_id', '=', 'secondary_categories.id')
        ->join('images as image1', 'products.image1', '=', 'image1.id')

        // whereで条件指定をしている(販売中)
        ->where('shops.is_selling', true)
        ->where('products.is_selling', true)
        ->select('products.id as id', 'products.name as name', 'products.price'
        ,'products.sort_order as sort_order'
        ,'products.information', 'secondary_categories.name as category'
        ,'image1.filename as filename');
    }

    public function scopeSortOrder($query, $sortOrder)
    {
        if($sortOrder === null || $sortOrder === \Constant::SORT_ORDER['recommend']){
            return $query->orderBy('sort_order', 'asc') ;
        }
        if($sortOrder === \Constant::SORT_ORDER['higherPrice']){
            return $query->orderBy('price', 'desc') ;
        }
        if($sortOrder === \Constant::SORT_ORDER['lowerPrice']){
            return $query->orderBy('price', 'asc') ;
        }
        if($sortOrder === \Constant::SORT_ORDER['later']){
            return $query->orderBy('products.created_at', 'desc') ;
        }
        if($sortOrder === \Constant::SORT_ORDER['older']){
            return $query->orderBy('products.created_at', 'asc') ;
        }
    }

    public function scopeSelectCategory($query, $categoryId)
    {
        if($categoryId !== '0')
        {
            return $query->where('secondary_category_id', $categoryId);
        } else {
            return ;
        }
    }

    public function scopeSearchKeyword($query, $keyword)
    {
        if(!is_null($keyword))
        {
            
            $spaceConvert = mb_convert_kana($keyword,'s');

            
            $keywords = preg_split('/[\s]+/', $spaceConvert,-1,PREG_SPLIT_NO_EMPTY);

            
            foreach($keywords as $word)
            {
                $query->where('products.name','like','%'.$word.'%');
            }

            return $query;  

        } else {
            return;
        }
    }    

}
