<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Niveau auquel la formation recrute : il décide des diplômes demandés au candidat.
     * bac = après le bac (Licence 3 ans, DUT…), bac2 = Bac+2 (Licence pro 1 an…), bac3 = Bac+3 (Master), bac5 = Bac+5 (Doctorat).
     */
    public function up(): void
    {
        Schema::table('formations', function (Blueprint $table) {
            $table->string('niveau_acces', 10)->default('bac')->after('type_formation');
        });
        DB::table('formations')->whereIn('type_formation', ['Master', 'Master spécialisé'])->update(['niveau_acces' => 'bac3']);
        DB::table('formations')->where('type_formation', 'Licence professionnelle')->update(['niveau_acces' => 'bac2']);
        DB::table('formations')->where('type_formation', 'Doctorat')->update(['niveau_acces' => 'bac5']);

        // Un candidat recruté après le bac n'a pas forcément de Bac+2 (syntaxe MySQL, ignorée par la base SQLite des tests)
        if (DB::getDriverName() === 'sqlite') {
            return;
        }
        DB::statement('ALTER TABLE diplomes MODIFY type_diplome_bac_2 VARCHAR(255) NULL, MODIFY annee_diplome_bac_2 VARCHAR(255) NULL,
            MODIFY filiere_diplome_bac_2 VARCHAR(255) NULL, MODIFY scan_bac_2 VARCHAR(255) NULL, MODIFY etablissement_bac_2 VARCHAR(255) NULL');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('formations', fn (Blueprint $table) => $table->dropColumn('niveau_acces'));
    }
};
