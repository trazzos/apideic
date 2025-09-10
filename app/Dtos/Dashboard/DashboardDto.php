<?php

namespace App\Dtos\Dashboard;

use App\Dtos\Reporte\BaseReporteDto;
use App\Models\User;

class DashboardDto extends BaseReporteDto
{
    public function __construct(?User $user = null)
    {
        parent::__construct($user);
    }

    /**
     * Crear DTO desde el usuario actual
     */
    public static function fromUser(User $user): self
    {
        return new self(user: $user);
    }

    /**
     * Verificar si hay restricciones jerárquicas para proyectos
     */
    public function hasProjectRestrictions(): bool
    {
        return !$this->canViewAllActivities();
    }

    /**
     * Verificar si hay restricciones jerárquicas para actividades
     */
    public function hasActivityRestrictions(): bool
    {
        return !$this->canViewAllActivities();
    }
}
