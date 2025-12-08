<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('tarif_listrik', function (Blueprint $table) {
            $table->id();
            $table->integer('daya_va');      // contoh: 450, 900, 1300, 2200
            $table->decimal('tarif_per_kwh', 10, 2);
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('tarif_listrik');
    }
};
