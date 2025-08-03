<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Schema;
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
                'title' => 'Dashboard',
                'name' => 'dashboard',
            ],
            [
                'title' => 'Catálogo',
                'name' => 'catalogos',
                'children' => [
                    [
                        'title' => 'Autoridades',
                        'name' => 'catalogos.autoridades',
                        'children' => [
                            [
                                'title' => 'Listar',
                                'name'  => 'catalogos.autoridades.listar'
                            ],
                            [
                                'title' => 'Agregar',
                                'name'  => 'catalogos.autoridades.agregar'
                            ],
                            [
                                'title' => 'Editar',
                                'name'  => 'catalogos.autoridades.editar'
                            ],
                            [
                                'title' => 'Eliminar',
                                'name'  => 'catalogos.autoridades.eliminar'
                            ]
                        ]
                    ],
                    [
                        'title' => 'Beneficiarios',
                        'name' => 'catalogos.beneficiarios',
                        'children' => [
                            [
                                'title' => 'Listar',
                                'name'  => 'catalogos.beneficiarios.listar'
                            ],
                            [
                                'title' => 'Agregar',
                                'name'  => 'catalogos.beneficiarios.agregar'
                            ],
                            [
                                'title' => 'Editar',
                                'name'  => 'catalogos.beneficiarios.editar'
                            ],
                            [
                                'title' => 'Eliminar',
                                'name'  => 'catalogos.beneficiarios.eliminar'
                            ]
                        ]
                    ],
                    [
                        'title' => 'Capacitadores',
                        'name' => 'catalogos.capacitadores',
                        'children' => [
                            [
                                'title' => 'Listar',
                                'name'  => 'catalogos.capacitadores.listar'
                            ],
                            [
                                'title' => 'Agregar',
                                'name'  => 'catalogos.capacitadores.agregar'
                            ],
                            [
                                'title' => 'Editar',
                                'name'  => 'catalogos.capacitadores.editar'
                            ],
                            [
                                'title' => 'Eliminar',
                                'name'  => 'catalogos.capacitadores.eliminar'
                            ]
                        ]
                    ],
                    [
                        'title' => 'Departamentos',
                        'name' => 'catalogos.departamentos',
                        'children' => [
                            [
                                'title' => 'Listar',
                                'name'  => 'catalogos.departamentos.agregar'
                            ],
                            [
                                'title' => 'Agregar',
                                'name'  => 'catalogos.departamentos.agregar'
                            ],
                            [
                                'title' => 'Editar',
                                'name'  => 'catalogos.departamentos.editar'
                            ],
                            [
                                'title' => 'Eliminar',
                                'name'  => 'catalogos.departamentos.eliminar'
                            ]
                        ]
                    ],
                    [
                        'title' => 'Tipos de actividad',
                        'name' => 'catalogos.tipos_actividad',
                        'children' => [
                            [
                                'title' => 'Listar',
                                'name'  => 'catalogos.tipos_actividad.listar'
                            ],
                            [
                                'title' => 'Agregar',
                                'name'  => 'catalogos.tipos_actividad.agregar'
                            ],
                            [
                                'title' => 'Editar',
                                'name'  => 'catalogos.tipos_actividad.editar'
                            ],
                            [
                                'title' => 'Eliminar',
                                'name'  => 'catalogos.tipos_actividad.eliminar'
                            ]
                        ]
                    ],
                    [
                        'title' => 'Tipos de documento',
                        'name' => 'catalogos.tipos_documento',
                        'children' => [
                            [
                                'title' => 'Listar',
                                'name'  => 'catalogos.tipos_documento.listar'
                            ],
                            [
                                'title' => 'Agregar',
                                'name'  => 'catalogos.tipos_documento.agregar'
                            ],
                            [
                                'title' => 'Editar',
                                'name'  => 'catalogos.tipos_documento.editar'
                            ],
                            [
                                'title' => 'Eliminar',
                                'name'  => 'catalogos.tipos_documento.eliminar'
                            ]
                        ]
                    ],
                    [
                        'title' => 'Tipos de proyecto',
                        'name' => 'catalogos.tipos_proyecto',
                        'children' => [
                            [
                                'title' => 'Listar',
                                'name'  => 'catalogos.tipos_proyecto.listar'
                            ],
                            [
                                'title' => 'Agregar',
                                'name'  => 'catalogos.tipos_proyecto.agregar'
                            ],
                            [
                                'title' => 'Editar',
                                'name'  => 'catalogos.tipos_proyecto.editar'
                            ],
                            [
                                'title' => 'Eliminar',
                                'name'  => 'catalogos.tipos_proyecto.eliminar'
                            ]
                        ]
                    ],
                ]
            ],
            [
                
                'title' => 'Gestión de cuentas',
                'name' => 'gestion_cuentas',
                'children' => [
                    [
                        'title' => 'Roles de usuario',
                        'name'  => 'gestion_cuentas.roles',
                        'children' => [
                            [
                                'title' => 'Agregar',
                                'name'  => 'gestion_cuentas.roles.agregar'
                            ],
                            [
                                'title' => 'Editar',
                                'name'  => 'gestion_cuentas.roles.editar'
                            ],
                            [
                                'title' => 'Eliminar',
                                'name'  => 'gestion_cuentas.roles.eliminar'
                            ] 
                        ]
                    ],
                    [
                        'title' => 'Personas',
                        'name' => 'gestion_cuentas.personas',
                        'children' => [
                            [
                                    'title' => 'Agregar',
                                    'name'  => 'gestion_cuentas.personas.agregar'
                                ],
                                [
                                    'title' => 'Editar',
                                    'name'  => 'gestion_cuentas.personas.editar'
                                ],
                                [
                                    'title' => 'Eliminar',
                                    'name'  => 'gestion_cuentas.personas.eliminar'
                                ],
                                [
                                    'title' => 'Cambiar contraseña',
                                    'name'  => 'gestion_cuentas.personas.cambiar_password'
                                ]
                        ]
                    ]
                ]
            ],
            [
                'title' => 'Proyectos',
                'name' => 'proyectos',
                'children' => [
                    [
                        'title' => 'Agregar',
                        'name'  => 'proyectos.agregar'
                    ],
                    [
                        'title' => 'Editar',
                        'name'  => 'proyectos.editar'
                    ],
                    [
                        'title' => 'Eliminar',
                        'name'  => 'proyectos.eliminar'
                    ],
                    [
                        'title' => 'Actividades',
                        'name'  => 'proyectos.actividades',
                        'children' => [
                            [
                                'title' => 'Agregar',
                                'name'  => 'proyectos.actividades.agregar'
                            ],
                            [
                                'title' => 'Editar',
                                'name'  => 'proyectos.actividades.editar'
                            ],
                            [
                                'title' => 'Editar',
                                'name'  => 'proyectos.actividades.eliminar'
                            ],
                        ]
                    ],
                    [
                        'title' => 'Checklist',
                        'name'  => 'proyectos.checklist',
                        'children' => [
                            [
                                'title' => 'Agregar',
                                'name'  => 'proyectos.checklist.agregar'
                            ],
                            [
                                'title' => 'Editar',
                                'name'  => 'proyectos.checklist.editar'
                            ],
                            [
                                'title' => 'Completar',
                                'name'  => 'proyectos.checklist.completar'
                            ],
                            [
                                'title' => 'Eliminar',
                                'name'  => 'proyectos.checklist.eliminar'
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
