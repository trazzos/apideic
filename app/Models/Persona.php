<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class Persona extends Model
{
    use SoftDeletes;

    protected $table = 'personas';

    protected $guarded = [];

    protected $appends = ['tipo_dependencia'];

    /**
     * Relación polimórfica con cualquier tipo de dependencia.
     * Puede ser: Secretaria, Subsecretaria, Direccion, o Departamento
     */
    public function dependencia(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Obtener la dependencia como Secretaria si es de ese tipo.
     */
    public function secretaria()
    {
        return $this->dependencia()->where('dependencia_type', Secretaria::class);
    }

    /**
     * Obtener la dependencia como Subsecretaria si es de ese tipo.
     */
    public function subsecretaria()
    {
        return $this->dependencia()->where('dependencia_type', Subsecretaria::class);
    }

    /**
     * Obtener la dependencia como Direccion si es de ese tipo.
     */
    public function direccion()
    {
        return $this->dependencia()->where('dependencia_type', Direccion::class);
    }

    /**
     * Verificar si la persona pertenece a una Secretaria.
     */
    public function perteneceASecretaria(): bool
    {
        return $this->dependencia_type === Secretaria::class;
    }

    /**
     * Verificar si la persona pertenece a una Subsecretaria.
     */
    public function perteneceASubsecretaria(): bool
    {
        return $this->dependencia_type === Subsecretaria::class;
    }

    /**
     * Verificar si la persona pertenece a una Direccion.
     */
    public function perteneceADireccion(): bool
    {
        return $this->dependencia_type === Direccion::class;
    }

    /**
     * Verificar si la persona pertenece a un Departamento.
     */
    public function perteneceADepartamento(): bool
    {
        return $this->dependencia_type === Departamento::class;
    }

    /**
     * Obtener el tipo de dependencia como string legible.
     */
    public function getTipoDependenciaAttribute(): string
    {
        return match($this->dependencia_type) {
            Secretaria::class => 'Secretaria',
            Subsecretaria::class => 'Subsecretaria', 
            Direccion::class => 'Direccion',
            Departamento::class => 'Departamento',
            default => 'No asignado'
        };
    }

    /**
     * Obtener la jerarquía completa de la dependencia.
     */
    public function getJerarquiaCompleta(): array
    {
        if (!$this->dependencia) {
            return [];
        }

        $jerarquia = [$this->dependencia];
        $actual = $this->dependencia;

        // Recorrer hacia arriba en la jerarquía
        while ($actual && method_exists($actual, 'getPadre') && $actual->getPadre()) {
            $padre = $actual->getPadre();
            array_unshift($jerarquia, $padre);
            $actual = $padre;
        }

        return $jerarquia;
    }

    /**
     * Verificar si la persona es titular de su dependencia.
     */
    public function esTitular(): bool
    {
        return $this->es_titular === 'Si';
    }

    /**
     * Establecer si la persona es titular de su dependencia.
     */
    public function setEsTitular(bool $esTitular): void
    {
        $this->es_titular = $esTitular ? 'Si' : 'No';
    }

    public function actividades(): HasMany
    {
        return $this->hasMany(Actividad::class, 'responsable_id');
    }

    public function user(): MorphOne
    {
        return $this->morphOne(User::class, 'owner');
    }

    public function getPublicUrlFotografiaAttribute(): ?string
    {
        if (!$this->url_fotografia) {
            return null;
        }

        // Si la URL ya contiene el dominio, devolverla tal como está
        if (str_starts_with($this->url_fotografia, 'http') || str_starts_with($this->url_fotografia, 'https')) {
            return $this->url_fotografia;
        }

        // Remover barras iniciales para evitar duplicados
        $path = ltrim($this->url_fotografia, '/');
        
        // Construir la URL pública usando la configuración del disco público
        // que apunta a public/storage gracias al enlace simbólico
        return asset('public/' . $path);
    }
}
