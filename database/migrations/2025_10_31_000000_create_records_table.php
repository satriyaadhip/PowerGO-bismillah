<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('records', function (Blueprint $table) {
            $table->id();
        
            // Tambahkan kolom foreign key
            $table->foreignId('device_id')
                  ->nullable() // supaya nggak error dulu saat awal
                  ->constrained('devices')
                  ->onDelete('cascade');
        
            $table->float('voltage');   // V
            $table->float('amperage');  // A
            $table->float('watt');      // Watt
            $table->timestamp('timestamp');  // waktu record dari Firebase
            $table->timestamps();
        });
        
    }

    public function down(): void
    {
        Schema::dropIfExists('records');
    }
};
