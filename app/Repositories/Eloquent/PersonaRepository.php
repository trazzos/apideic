<?php

namespace App\Repositories\Eloquent;

use App\Interfaces\Repositories\PersonaRepositoryInterface;
use App\Interfaces\Repositories\SearchableRepositoryInterface;
use App\Models\Persona;
use App\Repositories\Traits\Searchable;

class PersonaRepository extends BaseEloquentRepository implements PersonaRepositoryInterface, SearchableRepositoryInterface
{
    use Searchable;

    public function __construct(Persona $model)
    {
        parent::__construct($model);
        
        // Configurar campos de búsqueda específicos para personas
        $this->setSearchFields([
            'nombre',
            'apellido_paterno',
            'apellido_materno',
        ]);
    }

    /**
     * Obtener campos de búsqueda específicos para personas.
     */
    protected function getSearchFields(): array
    {
        return $this->defaultSearchFields;
    }

    /**
     * Obtener campos de búsqueda públicamente.
     */
    public function getPublicSearchFields(): array
    {
        return $this->getSearchFields();
    }
}
