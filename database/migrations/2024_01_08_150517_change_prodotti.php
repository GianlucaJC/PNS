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

			$table->integer('sign_ft')->after('file_etic')->nullable();
			$table->date('data_ft')->after('sign_ft')->nullable();
			$table->string('file_ft')->after('data_ft')->nullable();

		});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
