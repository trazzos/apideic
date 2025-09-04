<?php

namespace App\Services;

use App\Http\Resources\Proyecto\ProyectoResource;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Repositories\Eloquent\ProyectoRepository;
use App\Dtos\Proyecto\CreateProyectoDto;
use App\Dtos\Proyecto\UpdateProyectoDto;
use App\Dtos\Proyecto\ProyectoFiltroDto;
use App\Services\Traits\Searchable;
use App\Services\Search\SearchCriteria;
use Illuminate\Pagination\LengthAwarePaginator;

class ProyectoService extends BaseService {

    use Searchable;
    /**
     * @param ProyectoRepository $proyectoRepository
     */
    public function __construct(private readonly ProyectoRepository $proyectoRepository)
    {
        $this->repository = $this->proyectoRepository;
        $this->customResourceCollection = "App\\Http\\Resources\\Proyecto\\ProyectoCollection";
        $this->customResource = "App\\Http\\Resources\\Proyecto\\ProyectoResource";
    }


    /**
    * @param CreateProyectoDto $createProyectoDto
    * @return JsonResource
    */
    public function createFromDto(CreateProyectoDto $createProyectoDto): JsonResource
    {

        $data = [
            'tipo_proyecto_id' => $createProyectoDto->tipoProyectoId,
            'departamento_id' => $createProyectoDto->departamentoId,
            'uuid' => $createProyectoDto->uuid,
            'nombre' => $createProyectoDto->nombre,
            'descripcion' => $createProyectoDto->descripcion,
        ];

        $jsonResource =  parent::create($data);
        return ProyectoResource::make($jsonResource);
    }


    /**
     * @param UpdateProyectoDto $updateProyectoDto
     * @param int $id
     * @return JsonResource
     */
    public function updateFromDto(UpdateProyectoDto $updateProyectoDto, $id): JsonResource
    {

        $data = [
            'tipo_proyecto_id' => $updateProyectoDto->tipoProyectoId,
            'departamento_id' => $updateProyectoDto->departamentoId,
            'nombre' => $updateProyectoDto->nombre,
            'descripcion' => $updateProyectoDto->descripcion,
        ];

        $jsonResource = parent::update($id, $data);

        return ProyectoResource::make($jsonResource);
    }

    /**
     * Paginar proyectos con filtros jerárquicos.
     * 
     * @param ProyectoFiltroDto $dto
     * @return LengthAwarePaginator
     */
    public function paginateWithHierarchicalFilters(ProyectoFiltroDto $dto): LengthAwarePaginator
    {
        // Crear criterios de búsqueda usando el sistema centralizado
        $criteria = $this->buildSearchCriteriaFromDto($dto);
        
        // Usar el método searchWithPagination del trait Searchable
        return $this->repository->searchWithPagination($criteria, $dto->perPage);
    }

    /**
     * Construir SearchCriteria desde el DTO de filtros.
     * @param ProyectoFiltroDto $dto
     * @return SearchCriteria
     */
    private function buildSearchCriteriaFromDto(ProyectoFiltroDto $dto): SearchCriteria
    {
        $criteria = new SearchCriteria();
        
        // Configurar relaciones necesarias
        $criteria->setRelations([
            'tipoProyecto',
            'departamento',
            'actividades'
        ]);
        
        // Configurar ordenamiento
        $criteria->setSort('created_at', 'desc');
        
        // Aplicar filtros específicos
        
        // Filtro por tipo de proyecto
        if ($dto->shouldFilterByTipoProyecto()) {
            $criteria->addFilter('tipo_proyecto_id', $dto->tipoProyectoId);
        }
        
        // Filtro por estatus
        if ($dto->shouldFilterByEstatus()) {
            $criteria->addFilter('estatus', $dto->estatus);
        }

        // Filtro jerárquico por departamentos (con prioridad al request)
        $departamentoFilter = $dto->getFinalDepartamentoFilter();
        foreach ($departamentoFilter as $filterKey => $filterValue) {
            $criteria->addFilter($filterKey, $filterValue);
        }
        
        return $criteria;
    }
}
