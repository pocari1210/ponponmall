<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    use HasFactory;

    // テーブル名を変える際は、model側でprotected $tableで
    // 指定する必要がある(seederする際、読み込まれないため)
    protected $table = 't_stocks';

    protected $fillable = [
        'product_id',
        'type',
        'quantity'
    ];
}
