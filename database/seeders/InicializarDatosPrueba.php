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
        
        $capacitadores = [
            'Juan Pérez',
            'María López',
            'Carlos Sánchez',
            'Ana Gómez',
            'Luis Rodríguez',
        ];
        // Generar array de secretarias segun el estado de chiapas, mexico
        $secretarias = [
            'Secretaría de Gobierno',
            'Secretaría de Finanzas',
            'Secretaría de Educación',
            'Secretaría de Salud',
            'Secretaría de Desarrollo Social',
            'Secretaría de Infraestructura y Transporte',
            'Secretaría de Medio Ambiente e Historia Natural',
        ];

        // Generar array de subsecretarias segun el estado de chiapas, mexico y sus respectivas secretarias
        $subsecretarias = [
            ['nombre' => 'Subsecretaría de Administración', 'secretaria' => 'Secretaría de Finanzas'],
            ['nombre' => 'Subsecretaría de Ingresos', 'secretaria' => 'Secretaría de Finanzas'],
            ['nombre' => 'Subsecretaría de Educación Básica', 'secretaria' => 'Secretaría de Educación'],
            ['nombre' => 'Subsecretaría de Educación Media Superior y Superior', 'secretaria' => 'Secretaría de Educación'],
            ['nombre' => 'Subsecretaría de Promoción de la Salud', 'secretaria' => 'Secretaría de Salud'],
            ['nombre' => 'Subsecretaría de Prevención y Control de Enfermedades', 'secretaria' => 'Secretaría de Salud'],
            ['nombre' => 'Subsecretaría de Desarrollo Rural', 'secretaria' => 'Secretaría de Desarrollo Social'],
            ['nombre' => 'Subsecretaría de Asistencia Social', 'secretaria' => 'Secretaría de Desarrollo Social'],
            ['nombre' => 'Subsecretaría de Obras Públicas', 'secretaria' => 'Secretaría de Infraestructura y Transporte'],
            ['nombre' => 'Subsecretaría de Transporte', 'secretaria' => 'Secretaría de Infraestructura y Transporte'],
            ['nombre' => 'Subsecretaría de Conservación de la Biodiversidad', 'secretaria' => 'Secretaría de Medio Ambiente e Historia Natural'],
            ['nombre' => 'Subsecretaría de Cambio Climático y Desarrollo Sustentable', 'secretaria' => 'Secretaría de Medio Ambiente e Historia Natural'],
        ];

        // Genera array de direcciones segun las subsecretarias del estado de chiapas, mexico
        $direcciones = [
            ['nombre' => 'Dirección de Recursos Humanos', 'subsecretaria' => 'Subsecretaría de Administración'],
            ['nombre' => 'Dirección de Contabilidad', 'subsecretaria' => 'Subsecretaría de Administración'],
            ['nombre' => 'Dirección de Recaudación Fiscal', 'subsecretaria' => 'Subsecretaría de Ingresos'],
            ['nombre' => 'Dirección de Educación Primaria', 'subsecretaria' =>  'Subsecretaría de Educación Básica'],
            ['nombre' => 'Dirección de Educación Secundaria', 'subsecretaria' =>  'Subsecretaría de Educación Básica'],
            ['nombre' => 'Dirección de Educación Media Superior', 'subsecretaria' =>    'Subsecretaría de Educación Media Superior y Superior'],
            ['nombre' => 'Dirección de Educación Superior', 'subsecretaria' =>    'Subsecretaría de Educación Media Superior y Superior'],
            ['nombre' => 'Dirección de Promoción de la Salud', 'subsecretaria' => 'Subsecretaría de Promoción de la Salud'],
            ['nombre' => 'Dirección de Epidemiología', 'subsecretaria' => 'Subsecretaría de Prevención y Control de Enfermedades'],
            ['nombre' => 'Dirección de Desarrollo Rural', 'subsecretaria' => 'Subsecretaría de Desarrollo Rural'],
            ['nombre' => 'Dirección de Asistencia Social', 'subsecretaria' => 'Subsecretaría de Asistencia Social'],
            ['nombre' => 'Dirección de Obras Públicas', 'subsecretaria' => 'Subsecretaría de Obras Públicas'],
            ['nombre' => 'Dirección de Transporte Terrestre', 'subsecretaria' => 'Subsecretaría de Transporte'],
            ['nombre' => 'Dirección de Conservación de la Biodiversidad', 'subsecretaria' => 'Subsecretaría de Conservación de la Biodiversidad'],
            ['nombre' => 'Dirección de Cambio Climático', 'subsecretaria' => 'Subsecretaría de Cambio Climático y Desarrollo Sustentable'],
        ];

        // Genera array de departamentos segun las direcciones del estado de chiapas, mexico
        $departamentos = [
            ['nombre' => 'Departamento de Nóminas', 'direccion' => 'Dirección de Recursos Humanos'],
            ['nombre' => 'Departamento de Capacitación', 'direccion' => 'Dirección de Recursos Humanos'],
            ['nombre' => 'Departamento de Cuentas por Pagar', 'direccion' => 'Dirección de Contabilidad'],
            ['nombre' => 'Departamento de Auditoría', 'direccion' => 'Dirección de Contabilidad'],
            ['nombre' => 'Departamento de Fiscalización', 'direccion' => 'Dirección de Recaudación Fiscal'],
            ['nombre' => 'Departamento de Cobranza', 'direccion' => 'Dirección de Recaudación Fiscal'],
            ['nombre' => 'Departamento de Primaria Urbana', 'direccion' => 'Dirección de Educación Primaria'],
            ['nombre' => 'Departamento de Primaria Rural', 'direccion' => 'Dirección de Educación Primaria'],
            ['nombre' => 'Departamento de Secundaria Urbana', 'direccion' => 'Dirección de Educación Secundaria'],
            ['nombre' => 'Departamento de Secundaria Rural', 'direccion' => 'Dirección de Educación Secundaria'],
            ['nombre' => 'Departamento de Bachillerato', 'direccion' =>  'Dirección de Educación Media Superior'],
            ['nombre' => 'Departamento de Universidades', 'direccion' =>  'Dirección de Educación Superior'],
            ['nombre' => 'Departamento de Investigación y Posgrado', 'direccion' =>  'Dirección de Educación Superior'],
            ['nombre' => 'Departamento de Promoción y Prevención', 'direccion' =>  'Dirección de Promoción de la Salud'],
            ['nombre' => 'Departamento de Atención Médica', 'direccion' =>  'Dirección de Promoción de la Salud'],
            ['nombre' => 'Departamento de Vigilancia Epidemiológica', 'direccion' =>  'Dirección de Epidemiología'],
            ['nombre' => 'Departamento de Control Sanitario', 'direccion' =>  'Dirección de Epidemiología'],
            ['nombre' => 'Departamento de Agricultura', 'direccion' =>  'Dirección de Desarrollo Rural'],
            ['nombre' => 'Departamento de Ganadería', 'direccion' =>  'Dirección de Desarrollo Rural'],
            ['nombre' => 'Departamento de Asistencia Social Urbana', 'direccion' =>  'Dirección de Asistencia Social'],
            ['nombre' => 'Departamento de Asistencia Social Rural', 'direccion' =>  'Dirección de Asistencia Social'],
            ['nombre' => 'Departamento de Planeación de Obras', 'direccion' =>  'Dirección de Obras Públicas'],
            ['nombre' => 'Departamento de Supervisión de Obras', 'direccion' =>  'Dirección de Obras Públicas'],
            ['nombre' => 'Departamento de Transporte Público', 'direccion' =>  'Dirección de Transporte Terrestre'],
            ['nombre' => 'Departamento de Transporte Privado', 'direccion' =>  'Dirección de Transporte Terrestre'],
            ['nombre' => 'Departamento de Áreas Naturales Protegidas', 'direccion' =>  'Dirección de Conservación de la Biodiversidad'],
            ['nombre' => 'Departamento de Vida Silvestre', 'direccion' =>  'Dirección de Conservación de la Biodiversidad'],
            ['nombre' => 'Departamento de Mitigación del Cambio Climático', 'direccion' =>  'Dirección de Cambio Climático'],
            ['nombre' => 'Departamento de Adaptación al Cambio Climático', 'direccion' =>  'Dirección de Cambio Climático'],
        ];

      

        foreach ($secretarias as $secretaria) {
            \App\Models\Secretaria::create(['nombre' => $secretaria,'descripcion' => 'Descripción de ' . $secretaria]);
        }
        foreach ($subsecretarias as $subsecretaria) {
            $secretaria = \App\Models\Secretaria::where('nombre', $subsecretaria['secretaria'])->first();
            if ($secretaria) {
                \App\Models\Subsecretaria::create([
                    'nombre' => $subsecretaria['nombre'],
                    'descripcion' => 'Descripción de ' . $subsecretaria['nombre'],
                    'secretaria_id' => $secretaria->id,
                ]);
            }
        }
        foreach ($direcciones as $direccion) {
            $subsecretaria = \App\Models\Subsecretaria::where('nombre', $direccion['subsecretaria'])->first();
            if ($subsecretaria) {
                \App\Models\Direccion::create([
                    'nombre' => $direccion['nombre'],
                    'descripcion' => 'Descripción de ' . $direccion['nombre'],
                    'subsecretaria_id' => $subsecretaria->id,
                ]);
            }
        }   
        foreach ($departamentos as $departamento) {
            $direccion = \App\Models\Direccion::where('nombre', $departamento['direccion'])->first();
            if ($direccion) {
                \App\Models\Departamento::create([
                    'nombre' => $departamento['nombre'],
                    'descripcion' => 'Descripción de ' . $departamento['nombre'],
                    'direccion_id' => $direccion->id,
                ]);
            }
        }
    }
}
