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
        Schema::create('log_event', function (Blueprint $table) {
            $table->id();
			$table->integer('id_pns')->index();
			$table->integer('user')->index();
			$table->string('operazione');
			$table->string('modulo');
			$table->text('dettaglio');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('log_event');
    }
};
