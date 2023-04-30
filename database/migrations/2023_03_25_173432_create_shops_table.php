<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shops', function (Blueprint $table) {
            $table->id();

            // ownerテーブルと外部キー制約
            // 外部キー制約でテーブルを紐づけた場合、
            // 削除や更新をする場合、cascadeをつける必要がある
            $table->foreignId('owner_id')
            ->constrained()
            ->onUpdate('cascade')
            ->onDelete('cascade');
            
            $table->string('name'); 
            $table->text('information'); 
            $table->string('filename'); 
            $table->boolean('is_selling');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('shops');
    }
};
