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
        Schema::create('feed_backs', function (Blueprint $table) {
            $table->id();
            $table->string("name");
            $table->string("email");
            $table->bigInteger('rating');
            $table->string('product_service');
            $table->text("comment");
            $table->text("response")->nullable();
            $table->enum('status', ['répondu', 'non répondu'])->nullable()->default('non répondu');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('feed_backs');
    }
};
