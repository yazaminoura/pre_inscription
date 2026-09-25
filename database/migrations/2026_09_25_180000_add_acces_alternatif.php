<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Voie d'accès alternative d'une formation (ex. Master : Bac+3, OU Bac+2 avec 3 ans d'expérience),
     * et années d'expérience déclarées par le candidat.
     */
    public function up(): void
    {
        Schema::table('formations', function (Blueprint $table) {
            $table->string('alternatif_niveau', 10)->nullable()->after('niveau_acces');
            $table->unsignedTinyInteger('alternatif_experience')->nullable()->after('alternatif_niveau');
        });
        Schema::table('candidats', function (Blueprint $table) {
            $table->unsignedTinyInteger('annees_experience')->nullable()->after('scan_bac');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('formations', fn (Blueprint $table) => $table->dropColumn(['alternatif_niveau', 'alternatif_experience']));
        Schema::table('candidats', fn (Blueprint $table) => $table->dropColumn('annees_experience'));
    }
};
