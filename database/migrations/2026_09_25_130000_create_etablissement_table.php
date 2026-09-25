<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Identité de l'établissement, saisie depuis l'administration (une seule ligne).
     */
    public function up(): void
    {
        Schema::create('etablissement', function (Blueprint $table) {
            $table->id();
            $table->string('nom')->nullable();
            $table->string('sigle', 30)->nullable();
            $table->string('ville', 100)->nullable();
            $table->string('pays', 100)->nullable();
            $table->string('adresse')->nullable();
            $table->string('telephone', 40)->nullable();
            $table->string('email')->nullable();
            $table->string('site')->nullable();
            $table->string('slogan')->nullable();
            $table->text('presentation')->nullable();
            $table->string('couleur', 7)->nullable();
            $table->string('logo')->nullable();
            $table->timestamps();
        });

        Schema::table('formations', function (Blueprint $table) {
            $table->text('description')->nullable()->after('titre');
            $table->string('duree', 50)->nullable()->after('description');
            $table->unsignedInteger('places')->nullable()->after('duree');
            $table->text('conditions_acces')->nullable()->after('places');
            $table->text('modalites_selection')->nullable()->after('conditions_acces');
            $table->text('debouches')->nullable()->after('modalites_selection');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('formations', function (Blueprint $table) {
            $table->dropColumn(['description', 'duree', 'places', 'conditions_acces', 'modalites_selection', 'debouches']);
        });
        Schema::dropIfExists('etablissement');
    }
};
