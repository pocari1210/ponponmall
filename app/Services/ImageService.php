<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use InterventionImage;

class ImageService
{

  // uploadメソッドで
  // 画像$imageFileが登録されていたら、$folderNameに保存する
  public static function upload($imageFile, $folderName){
    
    // is_array:配列か否か判定
    if(is_array($imageFile))
    {
      $file = $imageFile['image'];
    } else {
      $file = $imageFile;
    }

    // ユニークIDを作りずつ重複しないファイル名を作成
    $fileName = uniqid(rand().'_');

    // 拡張子を取得
    $extension = $file->extension();

    // ファイル名 + 拡張子を文字列結合
    $fileNameToStore = $fileName. '.' . $extension;

    // 画像のサイズを横幅が1920,縦幅が1080に指定
    // encode()で画像として使用できるようになる
    $resizedImage = InterventionImage::make($file)->resize(1920, 1080)->encode();

    // 画像を保存処理する
    Storage::put('public/' . $folderName . '/' . $fileNameToStore, $resizedImage );
    // Storage::putFileAs('public/' . $folderName . '/' , $file, $fileNameToStore );
    
    return $fileNameToStore;
  }
}