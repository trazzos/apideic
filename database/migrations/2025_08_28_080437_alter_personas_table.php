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
        Schema::table('personas', function (Blueprint $table) {
            // Eliminar la relación antigua con departamentos
            $table->dropForeign(['departamento_id']);
            $table->dropColumn('departamento_id');
            
            // Cambiar nombre del campo responsable_departamento a es_titular
            // Manteniendo el tipo string con valores "Si"/"No"
            $table->renameColumn('responsable_departamento', 'es_titular');
            
            // Agregar campos para relación polimórfica con dependencias
            // Esto permitirá que una persona pertenezca a cualquier tipo de dependencia
            $table->nullableMorphs('dependencia'); // Crea dependencia_type y dependencia_id
            
            // Agregar índices para mejorar rendimiento
            $table->index(['dependencia_type', 'dependencia_id'], 'personas_dependencia_index');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('personas', function (Blueprint $table) {
            // Restaurar la estructura original
            $table->dropIndex('personas_dependencia_index');
            $table->dropMorphs('dependencia');
            
            // Restaurar relación con departamentos
            $table->foreignId('departamento_id')->constrained('departamentos');
            
            // Cambiar es_titular de vuelta a responsable_departamento
            $table->renameColumn('es_titular', 'responsable_departamento');
        });
    }
};
