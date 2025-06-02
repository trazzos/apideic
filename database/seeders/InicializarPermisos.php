<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\DB;

class InicializarPermisos extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();
        DB::table('permissions')->truncate();
        DB::table('roles')->truncate();
        DB::table('model_has_roles')->truncate();
        DB::table('model_has_permissions')->truncate();
        Schema::enableForeignKeyConstraints();

        $permisos =  [
            [
                'title' => 'Autoridades',
                'name' => 'autoridad',
                'children' => [
                    [
                        'title' => 'Agregar',
                        'name'  => 'autoridad.agregar'
                    ],
                    [
                        'title' => 'Editar',
                        'name'  => 'autoridad.editar'
                    ],
                    [
                        'title' => 'Eliminar',
                        'name'  => 'autoridad.eliminar'
                    ]
                ]
            ],
            [
                'title' => 'Beneficiarios',
                'name' => 'beneficiario',
                'children' => [
                    [
                        'title' => 'Agregar',
                        'name'  => 'beneficiario.agregar'
                    ],
                    [
                        'title' => 'Editar',
                        'name'  => 'beneficiario.editar'
                    ],
                    [
                        'title' => 'Eliminar',
                        'name'  => 'beneficiario.eliminar'
                    ]
                ]
            ],
            [
                'title' => 'Capacitadores',
                'name' => 'capacitador',
                'children' => [
                    [
                        'title' => 'Agregar',
                        'name'  => 'capacitador.agregar'
                    ],
                    [
                        'title' => 'Editar',
                        'name'  => 'capacitador.editar'
                    ],
                    [
                        'title' => 'Eliminar',
                        'name'  => 'capacitador.eliminar'
                    ]
                ]
            ],
            [
                'title' => 'Departamentos',
                'name' => 'departamento',
                'children' => [
                    [
                        'title' => 'Agregar',
                        'name'  => 'departamento.agregar'
                    ],
                    [
                        'title' => 'Editar',
                        'name'  => 'departamento.editar'
                    ],
                    [
                        'title' => 'Eliminar',
                        'name'  => 'departamento.eliminar'
                    ]
                ]
            ],
            [
                'title' => 'Tipos de actividad',
                'name' => 'tipo_actividad',
                'children' => [
                    [
                        'title' => 'Agregar',
                        'name'  => 'tipo_actividad.agregar'
                    ],
                    [
                        'title' => 'Editar',
                        'name'  => 'tipo_actividad.editar'
                    ],
                    [
                        'title' => 'Eliminar',
                        'name'  => 'tipo_actividad.eliminar'
                    ]
                ]
            ],
            [
                'title' => 'Tipos de documento',
                'name' => 'tipo_documento',
                'children' => [
                    [
                        'title' => 'Agregar',
                        'name'  => 'tipo_documento.agregar'
                    ],
                    [
                        'title' => 'Editar',
                        'name'  => 'tipo_documento.editar'
                    ],
                    [
                        'title' => 'Eliminar',
                        'name'  => 'tipo_documento.eliminar'
                    ]
                ]
            ],
            [
                'title' => 'Tipos de proyecto',
                'name' => 'tipo_proyecto',
                'children' => [
                    [
                        'title' => 'Agregar',
                        'name'  => 'tipo_proyecto.agregar'
                    ],
                    [
                        'title' => 'Editar',
                        'name'  => 'tipo_proyecto.editar'
                    ],
                    [
                        'title' => 'Eliminar',
                        'name'  => 'tipo_proyecto.eliminar'
                    ]
                ]
            ],
            [
                'title' => 'Gestion de roles',
                'name' => 'role',
                'children' => [
                    [
                        'title' => 'Agregar',
                        'name'  => 'role.agregar'
                    ],
                    [
                        'title' => 'Editar',
                        'name'  => 'role.editar'
                    ],
                    [
                        'title' => 'Eliminar',
                        'name'  => 'role.eliminar'
                    ]

                ]
            ],
            [
                'title' => 'Gestión de usuarios',
                'name' => 'usuario',
                'children' => [
                    [
                        'title' => 'Agregar',
                        'name'  => 'usuario.agregar'
                    ],
                    [
                        'title' => 'Editar',
                        'name'  => 'usuario.editar'
                    ],
                    [
                        'title' => 'Eliminar',
                        'name'  => 'usuario.eliminar'
                    ],
                    [
                        'title' => 'Cambiar contraseña',
                        'name'  => 'usuario.cambiar_password'
                    ]

                ]
            ],
            [
                'title' => 'Proyectos',
                'name' => 'proyecto',
                'children' => [
                    [
                        'title' => 'Agregar',
                        'name'  => 'proyecto.agregar'
                    ],
                    [
                        'title' => 'Editar',
                        'name'  => 'proyecto.editar'
                    ],
                    [
                        'title' => 'Eliminar',
                        'name'  => 'proyecto.eliminar'
                    ],
                    [
                        'title' => 'Actividades',
                        'name'  => 'proyecto.actividad',
                        'children' => [
                            [
                                'title' => 'Agregar',
                                'name'  => 'proyecto.actividad.agregar'
                            ],
                            [
                                'title' => 'Editar',
                                'name'  => 'proyecto.actividad.editar'
                            ],
                            [
                                'title' => 'Editar',
                                'name'  => 'proyecto.actividad.eliminar'
                            ],
                        ]
                    ],
                    [
                        'title' => 'Checklist',
                        'name'  => 'proyecto.checklist',
                        'children' => [
                            [
                                'title' => 'Agregar',
                                'name'  => 'proyecto.checklist.agregar'
                            ],
                            [
                                'title' => 'Editar',
                                'name'  => 'proyecto.checklis.editar'
                            ],
                            [
                                'title' => 'Completar',
                                'name'  => 'proyecto.checklist.completar'
                            ],
                            [
                                'title' => 'Eliminar',
                                'name'  => 'proyecto.checklist.eliminar'
                            ],
                        ]
                    ],

                ]
            ],
        ];

        $this->crearPermisosRecursivos($permisos);
    }

    /**
     * @param array $permissionsData
     * @param int|null $parentId
     * @return void
     */
    function crearPermisosRecursivos(array $permissionsData, ?int $parentId = null): void
    {

        foreach ($permissionsData as $permissionData) {

            $permission = Permission::firstOrCreate([
                'name' => Arr::get($permissionData, 'name'),
                'guard_name' => 'web'
            ], [
                'title' => Arr::get($permissionData, 'title'),
                'parent_id' => $parentId
            ]);


            if (isset($permissionData['children']) && is_array($permissionData['children'])) {
                $this->crearPermisosRecursivos($permissionData['children'], $permission->id);
            }
        }

    }
}
