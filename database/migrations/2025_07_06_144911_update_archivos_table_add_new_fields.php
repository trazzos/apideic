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
        Schema::table('archivos', function (Blueprint $table) {
            // Agregar campos nuevos
            $table->uuid('uuid')->unique()->after('id');
            $table->renameColumn('path', 'ruta');
            $table->unsignedBigInteger('tamanio')->after('ruta'); // Tamaño en bytes
            $table->string('extension')->after('ruta'); // Extensión del archivo
            $table->unsignedBigInteger('tipo_documento_id')->nullable()->after('mime_type');
            $table->string('tipo_documento_nombre')->nullable()->after('tipo_documento_id');
            
            // Eliminar campo tipo_documento
            $table->dropColumn('tipo_documento');
            
            // Agregar índices
            $table->index('uuid');
            $table->index('tipo_documento_id');
            
            // Foreign key para tipo_documento_id si tienes tabla tipos_documento
            $table->foreign('tipo_documento_id')->references('id')->on('tipos_documento')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('archivos', function (Blueprint $table) {
            // Revertir cambios
            $table->dropForeign(['tipo_documento_id']);
            $table->dropIndex(['uuid']);
            $table->dropIndex(['tipo_documento_id']);
            
            $table->dropColumn([
                'uuid',
                'tamanio',
                'extension',
                'tipo_documento_id',
                'tipo_documento_nombre'
            ]);
            
            $table->renameColumn('ruta', 'path');
            $table->string('tipo_documento')->after('nombre_original');
        });
    }
};
