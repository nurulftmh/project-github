<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::create('prediction_history', function (Blueprint $table) {
        $table->id();
        $table->foreignId('obat_id')->constrained()->onDelete('cascade');
        $table->integer('bulan_prediksi');
        $table->integer('tahun_prediksi');
        $table->decimal('nilai_prediksi', 10, 2);
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prediction_history');
    }
};
