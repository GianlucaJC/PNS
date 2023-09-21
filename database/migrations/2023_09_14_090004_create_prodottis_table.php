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
        Schema::create('prodotti', function (Blueprint $table) {
            $table->id();
			$table->integer('dele');
			$table->text('motivazione_dele')->nullable();
			$table->text('motivazione_ripristino')->nullable();
			$table->string('codice')->unique();
			$table->string('descrizione');
			$table->string('ivd');
			$table->string('cliente')->nullable();
			$table->string('codice_sl')->index()->nullable();
			$table->double('tot_distinta_base_sl',10,2)->nullable();
			$table->double('tot_distinta_base_pf',10,2)->nullable();
			$table->string('temperatura_conservazione')->nullable();
			$table->integer('gg_validita')->nullable();
			$table->double('minimo_ordine',10,2)->nullable();
			$table->text('gspr_applicabili')->nullable();
			$table->text('risk_management')->nullable();
			$table->string('progetto_rd_sn',1)->nullable();
			$table->text('progetto_rd')->nullable();
			$table->integer('sign_recensione')->nullable();
			$table->date('data_recensione')->nullable();
			$table->integer('sign_etichetta')->nullable();
			$table->date('data_etichetta')->nullable();
			$table->integer('sign_scheda_t')->nullable();
			$table->date('data_scheda_t')->nullable();
			$table->integer('sign_scheda_s')->nullable();
			$table->date('data_scheda_s')->nullable();
			$table->integer('sign_cert')->nullable();
			$table->date('data_cert')->nullable();
			$table->text('altri_doc')->nullable();
			$table->text('techinal_file')->nullable();
			$table->text('registrazione_ministero')->nullable();
			$table->text('registrazione_eudamed')->nullable();
			$table->integer('sign_qa')->nullable();
			$table->date('data_qa')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prodotti');
    }
};
