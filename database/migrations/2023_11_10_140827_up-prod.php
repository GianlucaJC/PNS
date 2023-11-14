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
		Schema::table('prodotti', function ($table) {
			$table->text('progetto_rd_motivazione_no')->after('progetto_rd_sn')->nullable();
		});
	
	}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {

    }
};
