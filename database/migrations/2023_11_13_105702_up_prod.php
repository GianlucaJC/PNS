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
			$table->dropColumn('techinal_file');
			$table->dropColumn('registrazione_ministero');
			$table->dropColumn('registrazione_eudamed');			
			
			$table->text('udi_di')->after('url_cert')->nullable();
			$table->integer('sign_udi')->after('udi_di')->nullable();
			$table->integer('sign_altro')->after('altri_doc')->nullable();
			$table->integer('sign_tecnica')->after('sign_altro')->nullable();
			$table->text('tecnica_file_note')->after('sign_tecnica')->nullable();
			$table->date('tecnica_file_data')->after('tecnica_file_note')->nullable();
			$table->date('tecnica_ministero_data')->after('tecnica_file_data')->nullable();
			$table->text('tecnica_repertorio')->after('tecnica_ministero_data')->nullable();			

			$table->text('tecnica_basic_udi')->after('tecnica_repertorio')->nullable();
			$table->text('tecnica_eudamed_note')->after('tecnica_basic_udi')->nullable();
			$table->date('tecnica_eudamed_data')->after('tecnica_eudamed_note')->nullable();
			$table->date('tecnica_sign_date')->after('tecnica_eudamed_data')->nullable();	
		

		});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
    }
};
