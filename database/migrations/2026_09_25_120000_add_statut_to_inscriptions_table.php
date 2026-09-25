<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('inscriptions', function (Blueprint $table) {
            $table->string('reference', 20)->nullable()->unique()->after('id');
            $table->string('statut', 20)->default('en_attente')->index()->after('formation_id');
            $table->text('motif')->nullable()->after('statut');
            $table->timestamp('statut_at')->nullable()->after('motif');
        });

        // Donner une référence aux inscriptions existantes
        foreach (DB::table('inscriptions')->whereNull('reference')->get(['id', 'created_at']) as $row) {
            $year = $row->created_at ? substr($row->created_at, 0, 4) : date('Y');
            DB::table('inscriptions')->where('id', $row->id)
                ->update(['reference' => 'PI' . $year . '-' . str_pad($row->id, 5, '0', STR_PAD_LEFT)]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('inscriptions', function (Blueprint $table) {
            $table->dropUnique(['reference']);
            $table->dropIndex(['statut']);
            $table->dropColumn(['reference', 'statut', 'motif', 'statut_at']);
        });
    }
};
