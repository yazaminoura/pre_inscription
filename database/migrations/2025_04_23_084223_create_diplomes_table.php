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
        Schema::create('diplomes', function (Blueprint $table) {
            $table->id();
    
            $table->string('type_diplome_bac_2');
            $table->string('annee_diplome_bac_2');
            $table->string('filiere_diplome_bac_2');
            $table->string('scan_bac_2');
            $table->string('etablissement_bac_2');
            $table->string('type_diplome_bac_3')->nullable();
            $table->string('annee_diplome_bac_3')->nullable();
            $table->string('filiere_diplome_bac_3')->nullable();
            $table->string('etablissement_bac_3')->nullable();
            $table->string('scan_bac_3')->nullable();
            $table->unsignedBigInteger('candidat_id');
            $table->foreign('candidat_id')->references('id')->on('candidats')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('diplomes');
    }
};
