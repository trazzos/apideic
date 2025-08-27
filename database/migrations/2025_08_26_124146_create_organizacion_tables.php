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
        Schema::create('secretarias', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->text('descripcion');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('subsecretarias', function (Blueprint $table) {
            $table->id();
            $table->foreignId('secretaria_id')->constrained('secretarias')->onDelete('cascade');
            $table->string('nombre');
            $table->text('descripcion');
            $table->timestamps();
            $table->softDeletes();
        });

         Schema::create('direcciones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('subsecretaria_id')->constrained('subsecretarias')->onDelete('cascade');
            $table->string('nombre');
            $table->text('descripcion');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::disableForeignKeyConstraints();
        Schema::table('departamentos', function (Blueprint $table) {
            $table->foreignId('direccion_id')
                ->after('id')
                ->constrained('direcciones')
                ->onDelete('cascade');
        });
        Schema::enableForeignKeyConstraints();

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
         Schema::table('departamentos', function (Blueprint $table) {
            $table->dropForeign(['direccion_id']);
            $table->dropColumn('direccion_id');
        });
        Schema::dropIfExists('direcciones');
        Schema::dropIfExists('subsecretarias');
        Schema::dropIfExists('secretarias');        
    }
};