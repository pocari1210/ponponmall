<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
// 認証機能のモジュールをインポート
use Illuminate\Foundation\Auth\User as Authenticatable;

// 認証機能を継承する
class Admin extends Authenticatable
{
    use HasFactory;


    // app\Models\User.phpをベースに、
    // $fillable,$hidden,$castsをコピペする


    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
}
