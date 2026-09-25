<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * type_formation n'est plus limité à Licence / Master (DUT, cycle ingénieur, doctorat…).
     */
    public function up(): void
    {
        DB::statement("ALTER TABLE formations MODIFY type_formation VARCHAR(60) NOT NULL");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("ALTER TABLE formations MODIFY type_formation ENUM('Licence','Master') NOT NULL");
    }
};
