<?php

namespace App\Services;
use App\Models\Permission;

class PermisoService
{
    /**
     * Obtiene el árbol completo de permisos con todos los niveles de jerarquía
     * usando relaciones Eloquent recursivas.
     */
    public function getPermissionTree(): \Illuminate\Database\Eloquent\Collection
    {
        return Permission::select(['id','parent_id','title','name'])
            ->whereNull('parent_id')
            ->with('children:id,parent_id,title,name')
            ->get();
    }

    /**
     * Versión optimizada que construye el árbol manualmente.
     * Recomendada para jerarquías grandes o cuando hay problemas de rendimiento.
     */
    public function getPermissionTreeOptimized(): array
    {
        // Obtener todos los permisos de una vez
        $allPermissions = Permission::select(['id','parent_id','title','name'])->get();
        
        // Construir el árbol manualmente
        return $this->buildTree($allPermissions->toArray());
    }

    /**
     * Construye recursivamente el árbol de permisos.
     */
    private function buildTree(array $permissions, $parentId = null): array
    {
        $branch = [];
        
        foreach ($permissions as $permission) {
            if ($permission['parent_id'] == $parentId) {
                $children = $this->buildTree($permissions, $permission['id']);
                if (!empty($children)) {
                    $permission['children'] = $children;
                }
                $branch[] = $permission;
            }
        }
        
        return $branch;
    }

    public function getFlatPermissionsWithHierarchy(): array
    {
        $permissions = Permission::all()->toArray();
        $hierarchy = [];
        $this->buildPermissionHierarchy($permissions, $hierarchy);
        return $hierarchy;
    }

    private function buildPermissionHierarchy(array $permissions, array &$hierarchy, $parentId = null, $level = 0)
    {
        foreach ($permissions as $permission) {
            if ($permission['parent_id'] === $parentId) {
                $permission['level'] = $level;
                $hierarchy[] = $permission;
                $this->buildPermissionHierarchy($permissions, $hierarchy, $permission['id'], $level + 1);
            }
        }
    }
}
