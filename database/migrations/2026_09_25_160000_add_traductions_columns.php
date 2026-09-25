<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Textes saisis par l'établissement, traduits à la main :
     * {"en": {"titre": "...", "description": "..."}, "ar": {...}}. Le français reste dans les colonnes normales.
     */
    public function up(): void
    {
        Schema::table('formations', function (Blueprint $table) {
            $table->json('traductions')->nullable()->after('debouches');
        });
        Schema::table('etablissement', function (Blueprint $table) {
            $table->json('traductions')->nullable()->after('presentation');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('formations', fn (Blueprint $table) => $table->dropColumn('traductions'));
        Schema::table('etablissement', fn (Blueprint $table) => $table->dropColumn('traductions'));
    }
};
