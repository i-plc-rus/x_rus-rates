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
        Schema::create('rates', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->string('external_id', 10);
            $table->smallInteger('num_code');
            $table->string('char_code', 3);
            $table->smallInteger('nominal');
            $table->string('name');
            $table->decimal('value', 19, 4);
            $table->decimal('v_unit_rate', 19, 4);
            $table->timestamps();

            $table->unique(['date', 'char_code']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rates');
    }
};
