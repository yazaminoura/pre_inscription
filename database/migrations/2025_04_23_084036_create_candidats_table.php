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
        Schema::create('candidats', function (Blueprint $table) {
            $table->id();
            $table->string('email');
            $table->string('nom');
            $table->string('prenom');
            $table->string('nom_ar');
            $table->string('prenom_ar');
            $table->string('CNE');
            $table->string('CIN');
            $table->date('date_naissance');
            $table->string('ville_naissance');
            $table->string('ville_naissance_ar');
            $table->string('province');
            $table->string('pay_naissance');
            $table->string('nationalite');
            $table->enum('sexe', ['M', 'F']);
            $table->string('telephone_mob');
            $table->string('telephone_fix')->nullable();
            $table->string('ville');
            $table->string('pays');            
            $table->text('adresse');
            $table->string('CV');
            $table->string('demande');
            $table->string('scan_cartid');
            $table->string('photo');
            $table->string('serie_bac');
            $table->string('annee_bac');
            $table->string('scan_bac');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('candidats');
    }
};
