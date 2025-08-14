<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;

class InicializarDatosPrueba extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $autoridades = [
            'Gobierno del estado',
            'Secretaría de Finanzas',
            'Secretaría de Recursos Humanos',
            'Secretaría de Innovación Tecnológica',
            'Secretaría de Desarrollo Agrario, Territorial y Urbano',
        ];

        $beneficiarios = [
            'Estudiantes',
            'Locatarios',
            'Comerciantes',
            'Congregaciones religiosas',
            'Emprendedores',
        ];

        $tiposProyecto = [
            'Infraestructura',
            'Educación',
            'Salud',
            'Ciencia y Tecnología',
            'Cultura y Recreación',
        ];

        $tiposActividad = [
            'Construcción',
            'Mantenimiento',
            'Capacitación',
            'Asesoría técnica',
            'Promoción social',
        ];

        $tiposDocumento = [
            'Informe de avance',
            'Acta de reunión',
            'Presupuesto',
            'Contrato',
            'Plan de trabajo',
        ];

        $departamentos = [
            'Administración',
            'Finanzas',
            'Recursos Humanos',
            'Tecnologías de la Información',
            'Recursos materiales',
        ];

        $capacitadores = [
            'Juan Pérez',
            'María López',
            'Carlos García',
            'Ana Martínez',
            'Luis Fernández',
        ];

        $personas = [
            'Pedro Sánchez',
            'Lucía Gómez',
            'Miguel Torres',
            'Sofía Ramírez',
            'Diego Flores',
        ];

        foreach ($autoridades as $autoridad) {
            \App\Models\Autoridad::create(['nombre' => $autoridad,'descripcion' => 'Descripción de ' . $autoridad]);
        }

        foreach ($beneficiarios as $beneficiario) {
            \App\Models\Beneficiario::create(['nombre' => $beneficiario,'descripcion' => 'Descripción de ' . $beneficiario]);
        }

        foreach ($tiposProyecto as $tipo) {
            \App\Models\TipoProyecto::create(['nombre' => $tipo,'descripcion' => 'Descripción de ' . $tipo,]);
        }

        foreach ($tiposActividad as $actividad) {
            \App\Models\TipoActividad::create(['nombre' => $actividad,'descripcion' => 'Descripción de ' . $actividad]);
        }

        foreach ($tiposDocumento as $documento) {
            \App\Models\TipoDocumento::create(['nombre' => $documento,'descripcion' => 'Descripción de ' . $documento]);
        }

        foreach ($departamentos as $departamento) {
            \App\Models\Departamento::create(['nombre' => $departamento,'descripcion' => 'Descripción de ' . $departamento]);
        }

        foreach ($capacitadores as $capacitador) {
            \App\Models\Capacitador::create(['nombre' => $capacitador,'descripcion' => 'Descripción de ' . $capacitador]);
        }
        foreach ($personas as $persona) {
            \App\Models\Persona::create([
                'nombre' => explode(' ', $persona)[0],
                'apellido_paterno' => explode(' ', $persona)[1] ?? '',
                'apellido_materno' => explode(' ', $persona)[2] ?? '',
                'departamento_id' => rand(1, count($departamentos)),
            ]);
        }


        // generame un set de proyectos  donde nombre tipo_proyecto_id  y departamento_id, son obligatorios al igual que su descripcion
        $proyectos = [
            [
                'nombre' => 'Construcción de un nuevo puente',
                'tipo_proyecto_id' => 1,
                'departamento_id' => 1,
                'descripcion' => 'Proyecto para la construcción de un nuevo puente en la ciudad.'
            ],
            [
                'nombre' => 'Capacitación en habilidades digitales',
                'tipo_proyecto_id' => 2,
                'departamento_id' => 2,
                'descripcion' => 'Programa de capacitación para empleados en habilidades digitales.'
            ],
            [
                'nombre' => 'Mantenimiento de áreas verdes',
                'tipo_proyecto_id' => 3,
                'departamento_id' => 3,
                'descripcion' => 'Proyecto de mantenimiento y mejora de áreas verdes en la ciudad.'
            ],
            [
                'nombre' => 'Desarrollo de una app móvil',
                'tipo_proyecto_id' => 4,
                'departamento_id' => 4,
                'descripcion' => 'Proyecto para el desarrollo de una aplicación móvil para ciudadanos.'
            ],
            [
                'nombre' => 'Festival de cultura local',
                'tipo_proyecto_id' => 5,
                'departamento_id' => 5,
                'descripcion' => 'Organización de un festival para promover la cultura local.'
            ],
        ];

        foreach ($proyectos as $proyecto) {
            $proyecto['uuid'] = \Illuminate\Support\Str::uuid();
            $modelProyecto = \App\Models\Proyecto::create($proyecto);
            // a cada proyecto creale actividades
            $actividades = [
                [
                    'proyecto_uuid' => $proyecto['uuid'],
                    'tipo_actividad_id' => rand(1, 5),
                    'capacitador_id' => rand(1, 5),
                    'beneficiario_id' => rand(1, 5),
                    'responsable_id' => rand(1, 5),
                    'nombre' => 'Actividad de prueba',
                    'fecha_inicio' => now(),
                    'fecha_final' => now()->addDays(30),
                    'persona_beneficiada' =>[
                        ['nombre' => 'Hombre', 'total' => 5],
                        ['nombre' => 'Mujer', 'total' => 3],
                        ['nombre' => 'Otro', 'total' => 33],
                    ],
                    'prioridad' => 'Alta',
                    'autoridad_participante' => [
                        rand(1, 5),
                        rand(1, 5),
                        rand(1, 5),
                        rand(1, 5),
                    ],
                    'link_drive' => 'https://drive.google.com',
                    'fecha_solicitud_constancia' => now(),
                    'fecha_envio_constancia' => now()->addDays(5),
                    'fecha_vencimiento_envio_encuesta' => now()->addDays(10),
                    'fecha_envio_encuesta' => now()->addDays(15),
                    'fecha_copy_creativo' => now()->addDays(20),
                    'fecha_inicio_difusion_banner' => now()->addDays(25),
                    'fecha_fin_difusion_banner' => now()->addDays(30),
                    'link_registro' => '',
                    'registro_nafin' => '',
                    'link_zoom' => '',
                    'link_panelista' => '',
                    'comentario' => '',
                ],
                [
                    'proyecto_uuid' => $proyecto['uuid'],
                    'tipo_actividad_id' => rand(1, 5),
                    'capacitador_id' => rand(1, 5),
                    'beneficiario_id' => rand(1, 5),
                    'responsable_id' => rand(1, 5),
                    'nombre' => 'Actividad de prueba 2',
                    'fecha_inicio' => now(),
                    'fecha_final' => now()->addDays(30),
                    'persona_beneficiada' => [
                        ['nombre' => 'Hombre', 'total' => 5],
                        ['nombre' => 'Mujer', 'total' => 3],
                        ['nombre' => 'Otro', 'total' => 3],
                    ],
                    'prioridad' => 'Alta',
                    'autoridad_participante' => json_encode(['nombre' => 'Autoridad Participante']),
                    'link_drive' => 'https://drive.google.com',
                    'fecha_solicitud_constancia' => now(),
                    'fecha_envio_constancia' => now()->addDays(5),
                    'fecha_vencimiento_envio_encuesta' => now()->addDays(10),
                    'fecha_envio_encuesta' => now()->addDays(15),
                    'fecha_copy_creativo' => now()->addDays(20),
                    'fecha_inicio_difusion_banner' => now()->addDays(25),
                    'fecha_fin_difusion_banner' => now()->addDays(30),
                    'link_registro' => '',
                    'registro_nafin' => '',
                    'link_zoom' => '',
                    'link_panelista' => '',
                    'comentario' => '',
                ]
            ];
            foreach ($actividades as $actividad) {
                $actividad['uuid'] = \Illuminate\Support\Str::uuid();
                $actividad['proyecto_id'] = $modelProyecto->id;
                \App\Models\Actividad::create($actividad);
            }
        }

    }
}
