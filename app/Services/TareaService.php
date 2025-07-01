<?php

namespace App\Services;

use App\Repositories\Eloquent\TareaRepository;
use Illuminate\Http\Resources\Json\ResourceCollection;
class TareaService extends BaseService {

    /**
     * @param TareaRepository $tareaRepository
     */
    public function __construct(private readonly TareaRepository $tareaRepository)
    {
        $this->repository = $this->tareaRepository;
        $this->customResourceCollection = "App\\Http\\Resources\\Tarea\\TareaCollection";
        $this->customResource = "App\\Http\\Resources\\Tarea\\TareaResource";
    }

    public function listByActividadUuid(string $uuid):ResourceCollection
    {
        $rows = $this->repository->findByActividadUuid($uuid);

        if ($this->customResourceCollection) {
            return new $this->customResourceCollection($rows);
        }
        return ($this->customResourceCollection)::make($rows);
    }

}
