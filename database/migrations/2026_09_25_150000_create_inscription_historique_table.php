<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Historique des statuts d'une candidature : qui a décidé quoi, quand, et si le candidat a été prévenu.
     */
    public function up(): void
    {
        Schema::create('inscription_historique', function (Blueprint $table) {
            $table->id();
            $table->foreignId('inscription_id')->constrained('inscriptions')->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('statut', 20);
            $table->text('motif')->nullable();
            $table->boolean('notifie')->default(false);
            $table->timestamp('created_at')->nullable();
        });

        // Reprise de l'existant : le dépôt, puis la décision actuelle s'il y en a une
        foreach (DB::table('inscriptions')->get() as $i) {
            DB::table('inscription_historique')->insert([
                'inscription_id' => $i->id, 'statut' => 'en_attente', 'created_at' => $i->created_at,
            ]);
            if ($i->statut !== 'en_attente') {
                DB::table('inscription_historique')->insert([
                    'inscription_id' => $i->id, 'statut' => $i->statut, 'motif' => $i->motif,
                    'created_at' => $i->statut_at ?? $i->updated_at,
                ]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inscription_historique');
    }
};
