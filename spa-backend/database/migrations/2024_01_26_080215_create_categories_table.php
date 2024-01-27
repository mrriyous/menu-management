<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('code', 50)->index()->nullable();
            $table->string('name', 150)->nullable();
            $table->unsignedBigInteger('parent_category_id')->nullable()->index();
            $table->unsignedBigInteger('parent_category_id_2')->nullable()->index();
            $table->unsignedBigInteger('parent_category_id_3')->nullable()->index();
            $table->unsignedBigInteger('parent_category_id_4')->nullable()->index();
            $table->timestamps();
            $table->softDeletes()->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};
