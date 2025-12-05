<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');

            $table->string('pelanggan_id', 20)->unique();

            $table->unsignedInteger('daya_va');     // 450 / 900 / 1300 / 2200
            $table->unsignedInteger('max_watt');    // sama dengan daya VA

            $table->enum('billing_type', ['prabayar', 'pascabayar']);

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('customers');
    }
};
