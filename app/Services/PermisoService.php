<?php

namespace App\Services;
use App\Models\Permission;

class PermisoService
{
    public function getPermissionTree(): \Illuminate\Database\Eloquent\Collection
    {
        return Permission::select(['id','parent_id','title','name'])->whereNull('parent_id')->with('children:id,parent_id,title,name')->get();
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
