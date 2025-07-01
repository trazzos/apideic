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

        Schema::create('tipos_proyecto', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->longText('descripcion');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('tipos_actividad', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->longText('descripcion');
            $table->char('mostrar_en_calendario')->default('No');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('tipos_documento', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->longText('descripcion');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('departamentos', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->longText('descripcion');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('capacitadores', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->longText('descripcion');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('beneficiarios', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->longText('descripcion');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('autoridades', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->longText('descripcion');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('proyectos', function (Blueprint $table) {

            $table->id();
            $table->uuid()->unique()->index();
            $table->foreignId('departamento_id')->constrained('departamentos');
            $table->foreignId('tipo_proyecto_id')->constrained('tipos_proyecto');
            $table->string('nombre');
            $table->longText('descripcion')->default('');
            $table->timestamps();
            $table->softDeletes();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('autoridades');
        Schema::dropIfExists('beneficiarios');
        Schema::dropIfExists('capacitadores');
        Schema::dropIfExists('departamentos');
        Schema::dropIfExists('tipos_actividad');
        Schema::dropIfExists('tipos_documento');
        Schema::dropIfExists('tipos_proyecto');
        Schema::dropIfExists('proyectos');
        Schema::enableForeignKeyConstraints();
    }
};
