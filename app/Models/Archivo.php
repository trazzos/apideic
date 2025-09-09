<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

/**
 * Modelo para la gestión de archivos del sistema.
 * 
 * @package App\Models
 * @author Sistema DEIC
 * @since 1.0.0
 */
class Archivo extends Model implements Auditable
{
    use SoftDeletes, \OwenIt\Auditing\Auditable;

    protected $table = 'archivos';

    protected $guarded = [];

    protected $casts = [
        'tamanio' => 'integer',
    ];

    /**
     * Obtener el campo que se usará para el route model binding.
     */
    public function getRouteKeyName(): string
    {
        return 'uuid';
    }

    /**
     * Relación polimórfica con cualquier modelo que pueda tener archivos.
     */
    public function archivable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Relación con el tipo de documento.
     */
    public function tipoDocumento(): BelongsTo
    {
        return $this->belongsTo(TipoDocumento::class, 'tipo_documento_id');
    }

    /**
     * Obtener la URL completa del archivo.
     */
    public function getUrlCompleta(): string
    {
        if ($this->url) {
            return $this->url;
        }
        
        return asset('storage/' . $this->ruta);
    }

    /**
     * Obtener el tamaño formateado del archivo.
     */
    public function getTamanioFormateado(): string
    {
        $bytes = $this->tamanio;
        
        if ($bytes >= 1073741824) {
            return number_format($bytes / 1073741824, 2) . ' GB';
        } elseif ($bytes >= 1048576) {
            return number_format($bytes / 1048576, 2) . ' MB';
        } elseif ($bytes >= 1024) {
            return number_format($bytes / 1024, 2) . ' KB';
        } else {
            return $bytes . ' bytes';
        }
    }
}
