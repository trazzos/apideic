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
        Schema::create('actividades', function (Blueprint $table) {
            $table->id();
            $table->foreignId('proyecto_id')->constrained('proyectos');
            $table->foreignId('tipo_actividad_id')->constrained('tipos_actividad');
            $table->foreignId('capacitador_id')->constrained('capacitadores');
            $table->foreignId('beneficiario_id')->constrained('beneficiarios');
            $table->foreignId('responsable_id')->constrained('personas');
            $table->uuid('proyecto_uuid')->index();
            $table->uuid()->index()->unique();
            $table->string('nombre');
            $table->date('fecha_inicio');
            $table->date('fecha_final');
            $table->string('persona_beneficiada');
            $table->string('prioridad')->default('Normal');
            $table->json('autoridad_participante')->nullable()->default(null);
            $table->string('link_drive')->default('No');
            $table->date('fecha_solicitud_constancia')->nullable()->default(null);
            $table->date('fecha_envio_constancia')->nullable()->default(null);
            $table->date('fecha_vencimiento_envio_encuesta')->nullable()->default(null);
            $table->string('fecha_envio_encuesta')->nullable()->default(null);
            $table->date('fecha_copy_creativo')->nullable()->default(null);
            $table->date('fecha_inicio_difusion_banner')->nullable()->default(null);
            $table->date('fecha_fin_difusion_banner')->nullable()->default(null);
            $table->string('link_registro')->default('');
            $table->string('registro_nafin')->default('');
            $table->string('link_zoom')->default('');
            $table->string('link_panelista')->default('');
            $table->text('comentario')->default('');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('actividades');
    }
};
