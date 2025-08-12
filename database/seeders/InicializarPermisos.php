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
                                'title' => 'Acceso',
                                'name'  => 'catalogos.autoridades.acceso'
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
                                'title' => 'Acceso',
                                'name'  => 'catalogos.beneficiarios.acceso'
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
                                'title' => 'Acceso',
                                'name'  => 'catalogos.capacitadores.acceso'
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
                                'title' => 'Acceso',
                                'name'  => 'catalogos.departamentos.acceso'
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
                                'title' => 'Acceso',
                                'name'  => 'catalogos.tipos_actividad.acceso'
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
                                'title' => 'Acceso',
                                'name'  => 'catalogos.tipos_documento.acceso'
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
                                'title' => 'Acceso',
                                'name'  => 'catalogos.tipos_proyecto.acceso'
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
                                'title' => 'Acceso',
                                'name'  => 'gestion_cuentas.roles.acceso'
                            ],
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
                                    'title' => 'Acceso',
                                    'name'  => 'gestion_cuentas.personas.acceso'
                                ],
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
                                    'title' => 'Administrar cuenta',
                                    'name'  => 'gestion_cuentas.personas.administrar_cuenta'
                                ]
                        ]
                    ]
                ]
            ],
            [
                'title' => 'Gestión de proyectos',
                'name' => 'gestion_proyectos',
                'children' => [
                        [
                            'title' => 'Proyectos',
                            'name'  => 'gestion_proyectos.proyectos',
                            'children' =>  [
                                [
                                    'title' => 'Acceso',
                                    'name'  => 'gestion_proyectos.proyectos.acceso'
                                ],
                                [
                                    'title' => 'Agregar',
                                    'name'  => 'gestion_proyectos.proyectos.agregar'
                                ],
                                [
                                    'title' => 'Editar',
                                    'name'  => 'gestion_proyectos.proyectos.editar'
                                ],
                                [
                                    'title' => 'Eliminar',
                                    'name'  => 'gestion_proyectos.proyectos.eliminar'
                                ],
                                [
                                    'title' => 'Actividades',
                                    'name'  => 'gestion_proyectos.proyectos.actividades',
                                    'children' => [
                                        [
                                            'title' => 'Agregar',
                                            'name'  => 'gestion_proyectos.proyectos.actividades.agregar'
                                        ],
                                        [
                                            'title' => 'Editar',
                                            'name'  => 'gestion_proyectos.proyectos.actividades.editar'
                                        ],
                                        [
                                            'title' => 'Editar',
                                            'name'  => 'gestion_proyectos.proyectos.actividades.eliminar'
                                        ],
                                    ]
                                ],
                                [
                                    'title' => 'Checklist',
                                    'name'  => 'gestion_proyectos.proyectos.checklist',
                                    'children' => [
                                        [
                                            'title' => 'Agregar',
                                            'name'  => 'gestion_proyectos.proyectos.checklist.agregar'
                                        ],
                                        [
                                            'title' => 'Editar',
                                            'name'  => 'gestion_proyectos.proyectos.checklist.editar'
                                        ],
                                        [
                                            'title' => 'Completar',
                                            'name'  => 'gestion_proyectos.proyectos.checklist.completar'
                                        ],
                                        [
                                            'title' => 'Eliminar',
                                            'name'  => 'gestion_proyectos.proyectos.checklist.eliminar'
                                        ],
                                    ]
                                ],

                            ]
                            ],
                            [
                                'title' => 'Tablero',
                                'name'  => 'gestion_proyectos.tablero'
                            ]
                ]
            ]
        ];

        $this->crearPermisosRecursivos($permisos);
        //Crear el rol de Super Admin
        $superAdminRole = \Spatie\Permission\Models\Role::firstOrCreate(['title' => 'Super Admin', 'name' => 'superadmin', 'guard_name' => 'web']);
        $user = \App\Models\User::where('email', 'super@codisoft.com.mx')->first();
        if ($user) {
            $user->assignRole($superAdminRole);
        }
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
