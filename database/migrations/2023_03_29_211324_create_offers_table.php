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
        Schema::create('offers', function (Blueprint $table) {
            $table->id();
            $table->integer('simulation_id');
            $table->string('instituicaoFinanceira');
            $table->string('modalidadeCredito');
            $table->decimal('valorAPagar',10,2);
            $table->decimal('taxaJuros',10,4);
            $table->integer('qntParcelas');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('offers');
    }
};
