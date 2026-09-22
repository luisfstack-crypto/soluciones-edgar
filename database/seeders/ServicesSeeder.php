<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Service;

class ServicesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $services = [
            // ACTAS
            [
                'code' => 'acta-nacimiento-830',
                'name' => 'Acta de Nacimiento',
                'description' => 'SERVICIO ACTIVO Todos los días de 8:00 AM a 8:00 PM (Si ordena fuera de horario, el documento se entregará cuando el servicio esté activo Y NO CUENTAN ESAS HRS DE PROCESO) ORDENAR SOLO CON LA CURP',
                'cost' => 10.00,
                'price' => 65.00,
                'service_type' => 'ACTAS',
                'processing_time' => '1-30 Minutos',
                'form_schema' => [['name' => 'curp', 'label' => 'CURP', 'type' => 'text', 'required' => true, 'regex' => '/^[A-Z]{4}\d{6}[HM][A-Z]{2}[A-Z]{3}[A-Z0-9]{2}$/']],
            ],
            [
                'code' => 'acta-defuncion-830',
                'name' => 'Acta de Defunción',
                'description' => 'SERVICIO ACTIVO Todos los días de 8:00 AM a 8:00 PM (Si ordena fuera de horario, el documento se entregará cuando el servicio esté activo Y NO CUENTAN ESAS HRS DE PROCESO) ORDENAR SOLO CON LA CURP',
                'cost' => 10.00,
                'price' => 65.00,
                'service_type' => 'ACTAS',
                'processing_time' => '1-30 Minutos',
                'form_schema' => [['name' => 'curp', 'label' => 'CURP', 'type' => 'text', 'required' => true, 'regex' => '/^[A-Z]{4}\d{6}[HM][A-Z]{2}[A-Z]{3}[A-Z0-9]{2}$/']],
            ],
            [
                'code' => 'acta-divorcio-830',
                'name' => 'Acta de Divorcio',
                'description' => 'SERVICIO ACTIVO Todos los días de 8:00 AM a 8:00 PM (Si ordena fuera de horario, el documento se entregará cuando el servicio esté activo Y NO CUENTAN ESAS HRS DE PROCESO) ORDENAR SOLO CON LA CURP DE ALGUNO DE LOS ESPOSOS',
                'cost' => 10.00,
                'price' => 65.00,
                'service_type' => 'ACTAS',
                'processing_time' => '1-30 Minutos',
                'form_schema' => [['name' => 'curp', 'label' => 'CURP de alguno de los esposos', 'type' => 'text', 'required' => true, 'regex' => '/^[A-Z]{4}\d{6}[HM][A-Z]{2}[A-Z]{3}[A-Z0-9]{2}$/']],
            ],
            [
                'code' => 'acta-matrimonio-830',
                'name' => 'Acta de Matrimonio',
                'description' => 'SERVICIO ACTIVO Todos los días de 8:00 AM a 8:00 PM (Si ordena fuera de horario, el documento se entregará cuando el servicio esté activo Y NO CUENTAN ESAS HRS DE PROCESO) REQUIERE CURP DE AMBOS ESPOSOS',
                'cost' => 10.00,
                'price' => 65.00,
                'service_type' => 'ACTAS',
                'processing_time' => '1-30 Minutos',
                'form_schema' => [
                    ['name' => 'curpEsposo', 'label' => 'CURP Esposo', 'type' => 'text', 'required' => true, 'regex' => '/^[A-Z]{4}\d{6}[HM][A-Z]{2}[A-Z]{3}[A-Z0-9]{2}$/'],
                    ['name' => 'curpEsposa', 'label' => 'CURP Esposa', 'type' => 'text', 'required' => true, 'regex' => '/^[A-Z]{4}\d{6}[HM][A-Z]{2}[A-Z]{3}[A-Z0-9]{2}$/'],
                ],
            ],
            // SAT
            [
                'code' => 'csf-curp-clon',
                'name' => 'CSF Clon con CURP',
                'description' => 'LEER INFORMACION SERVICIO ACTIVO Todos los días 24/7 ORDENAR CON CURP Y LUGAR DE EMISIÓN',
                'cost' => 20.00,
                'price' => 60.00,
                'service_type' => 'SAT',
                'processing_time' => '5 Minutos',
                'form_schema' => [
                    ['name' => 'curp', 'label' => 'CURP', 'type' => 'text', 'required' => true, 'regex' => '/^[A-Z]{4}\d{6}[HM][A-Z]{2}[A-Z]{3}[A-Z0-9]{2}$/'],
                    ['name' => 'lugarEmision', 'label' => 'Lugar de Emisión (Ej. Cuauhtémoc, Ciudad de México)', 'type' => 'text', 'required' => true],
                ],
            ],
            [
                'code' => 'constancia-fiscal-rfc-idcif',
                'name' => 'CSF con RFC y IDCIF',
                'description' => 'LEER INFORMACION SERVICIO ACTIVO Todos los días 24/7 ORDENAR SOLO CON EL RFC Y IDCIF',
                'cost' => 20.00,
                'price' => 55.00,
                'service_type' => 'SAT',
                'processing_time' => '5 Minutos',
                'form_schema' => [
                    ['name' => 'rfc', 'label' => 'RFC', 'type' => 'text', 'required' => true, 'regex' => '/^[A-ZÑ&]{3,4}\d{6}[A-Z0-9]{3}$/'],
                    ['name' => 'idcif', 'label' => 'IDCIF', 'type' => 'text', 'required' => true],
                ],
            ],
            [
                'code' => 'localizar-idcif-estandar',
                'name' => 'LOCALIZAR IDCIF',
                'description' => 'LEER INFORMACION SERVICIO ACTIVO DE LUNES A VIERNES DE 8:00 AM a 8:00 PM. ORDENAR CON CURP SEGÚN REGLAS DOCMX',
                'cost' => 46.00,
                'price' => 95.00,
                'service_type' => 'SAT',
                'processing_time' => '1-6 Horas',
                'form_schema' => [['name' => 'curp', 'label' => 'CURP', 'type' => 'text', 'required' => true, 'regex' => '/^[A-Z]{4}\d{6}[HM][A-Z]{2}[A-Z]{3}[A-Z0-9]{2}$/']],
            ],
            // IMSS
            [
                'code' => 'vigencia-derechos-curp',
                'name' => 'Constancia Vigencia Derechos NSS PDF por CURP',
                'description' => 'LEER INFORMACION SERVICIO ACTIVO Todos los días 24/7 ORDENAR SOLO CON LA CURP',
                'cost' => 10.00,
                'price' => 40.00,
                'service_type' => 'SINDOS IMSS',
                'processing_time' => '5 Minutos',
                'form_schema' => [['name' => 'curp', 'label' => 'CURP', 'type' => 'text', 'required' => true, 'regex' => '/^[A-Z]{4}\d{6}[HM][A-Z]{2}[A-Z]{3}[A-Z0-9]{2}$/']],
            ],
            [
                'code' => 'localizar-nss',
                'name' => 'Localizar NSS con CURP',
                'description' => 'LEER INFORMACION SERVICIO ACTIVO Todos los días 24/7 ORDENAR SOLO CON LA CURP',
                'cost' => 10.00,
                'price' => 20.00,
                'service_type' => 'SINDOS IMSS',
                'processing_time' => '5 Minutos',
                'form_schema' => [['name' => 'curp', 'label' => 'CURP', 'type' => 'text', 'required' => true, 'regex' => '/^[A-Z]{4}\d{6}[HM][A-Z]{2}[A-Z]{3}[A-Z0-9]{2}$/']],
            ],
            [
                'code' => 'semanas-cotizadas-detalladas',
                'name' => 'Semanas Cotizadas por CURP',
                'description' => 'LEER INFORMACION SERVICIO ACTIVO Todos los días 24/7 ORDENAR SOLO CON LA CURP',
                'cost' => 20.00,
                'price' => 40.00,
                'service_type' => 'SINDOS IMSS',
                'processing_time' => '10 Minutos',
                'form_schema' => [['name' => 'curp', 'label' => 'CURP', 'type' => 'text', 'required' => true, 'regex' => '/^[A-Z]{4}\d{6}[HM][A-Z]{2}[A-Z]{3}[A-Z0-9]{2}$/']],
            ],
            // SERVICIOS
            [
                'code' => 'afore-localizacion',
                'name' => 'Localizar AFORE (Saber el Banco o Institución)',
                'description' => 'LEER INFORMACION SERVICIO ACTIVO Todos los días 24/7 ORDENAR SOLO CON LA CURP',
                'cost' => 9.00,
                'price' => 20.00,
                'service_type' => 'SERVICIOS',
                'processing_time' => '5 Minutos',
                'form_schema' => [['name' => 'curp', 'label' => 'CURP', 'type' => 'text', 'required' => true, 'regex' => '/^[A-Z]{4}\d{6}[HM][A-Z]{2}[A-Z]{3}[A-Z0-9]{2}$/']],
            ],
            [
                'code' => 'curp-actualizada',
                'name' => 'CURP Actualizada',
                'description' => 'LEER INFORMACION SERVICIO ACTIVO Todos los días 24/7 ORDENAR SOLO CON LA CURP',
                'cost' => 4.00,
                'price' => 8.00,
                'service_type' => 'SERVICIOS',
                'processing_time' => '5 Minutos',
                'form_schema' => [['name' => 'curp', 'label' => 'CURP', 'type' => 'text', 'required' => true, 'regex' => '/^[A-Z]{4}\d{6}[HM][A-Z]{2}[A-Z]{3}[A-Z0-9]{2}$/']],
            ],
            [
                'code' => 'recibo-cfe',
                'name' => 'Recibo CFE PDF',
                'description' => 'LEER INFORMACION SERVICIO ACTIVO. ORDENAR SOLO CON EL NUMERO DEL SERVICIO',
                'cost' => 6.00,
                'price' => 8.00,
                'service_type' => 'SERVICIOS',
                'processing_time' => '1-10 Minutos',
                'form_schema' => [['name' => 'numeroServicioCfe', 'label' => 'Número de Servicio', 'type' => 'text', 'required' => true]],
            ],
            [
                'code' => 'antecedentes-no-penales-federal',
                'name' => 'Antecedentes no Penales',
                'description' => 'LEER INFORMACION SERVICIO ACTIVO Todos los días 24/7. REQUIERE CURP Y NOMBRE INSTITUCIÓN',
                'cost' => 256.00,
                'price' => 70.00,
                'service_type' => 'SERVICIOS',
                'processing_time' => '5 Minutos',
                'form_schema' => [
                    ['name' => 'curp', 'label' => 'CURP', 'type' => 'text', 'required' => true, 'regex' => '/^[A-Z]{4}\d{6}[HM][A-Z]{2}[A-Z]{3}[A-Z0-9]{2}$/'],
                    ['name' => 'nombreInstitucion', 'label' => 'Nombre Institución', 'type' => 'text', 'required' => true],
                ],
            ],
            // INFONAVIT
            [
                'code' => 'estado-cuenta-mensual-infonavit',
                'name' => 'ESTADO DE CUENTA MENSUAL INFONAVIT',
                'description' => 'SERVICIO ACTIVO Todos los días de 8:00 AM a 8:00 PM. REQUIERE NSS',
                'cost' => 36.00,
                'price' => 100.00,
                'service_type' => 'INFONAVIT',
                'processing_time' => '1-60 Minutos',
                'form_schema' => [['name' => 'nss', 'label' => 'NSS (11 dígitos)', 'type' => 'text', 'required' => true, 'regex' => '/^\d{11}$/']],
            ],
            [
                'code' => 'reseteo-contrasena-infonavit',
                'name' => 'RECUPERAR CLAVE CUENTA INFONAVIT',
                'description' => 'SERVICIO ACTIVO Para solicitarlo solo se requiere: NSS',
                'cost' => 36.00,
                'price' => 100.00,
                'service_type' => 'INFONAVIT',
                'processing_time' => '1-20 Minutos',
                'form_schema' => [['name' => 'nss', 'label' => 'NSS (11 dígitos)', 'type' => 'text', 'required' => true, 'regex' => '/^\d{11}$/']],
            ],
            [
                'code' => 'estado-cuenta-historico-infonavit',
                'name' => 'REPORTE HISTORICO INFONAVIT',
                'description' => 'SERVICIO ACTIVO. ORDENAR SOLO CON EL NSS',
                'cost' => 36.00,
                'price' => 100.00,
                'service_type' => 'INFONAVIT',
                'processing_time' => '1-30 Minutos',
                'form_schema' => [['name' => 'nss', 'label' => 'NSS (11 dígitos)', 'type' => 'text', 'required' => true, 'regex' => '/^\d{11}$/']],
            ],
            [
                'code' => 'resumen-movimientos-infonavit',
                'name' => 'RESUMEN CREDITO INFONAVIT',
                'description' => 'SERVICIO ACTIVO. ORDENAR SOLO CON NSS',
                'cost' => 36.00,
                'price' => 100.00,
                'service_type' => 'INFONAVIT',
                'processing_time' => '1-30 Minutos',
                'form_schema' => [['name' => 'nss', 'label' => 'NSS (11 dígitos)', 'type' => 'text', 'required' => true, 'regex' => '/^\d{11}$/']],
            ],
            // VEHICULOS
            [
                'code' => 'FP-TCDMX',
                'name' => 'FORMATO PAGO DE TENENCIA CD MX',
                'description' => 'Para solicitarlo se Requiere: NÚMERO DE PLACA AÑO A PAGAR',
                'cost' => 30.00,
                'price' => 50.00,
                'service_type' => 'VEHICULOS',
                'processing_time' => '10 Minutos',
                'form_schema' => [
                    ['name' => 'plate', 'label' => 'Número de Placa', 'type' => 'text', 'required' => true, 'regex' => '/^[A-Z0-9-]{6,9}$/'],
                    ['name' => 'year', 'label' => 'Año a Pagar', 'type' => 'text', 'required' => true, 'regex' => '/^\d{4}$/'],
                ],
            ],
            [
                'code' => 'FP-TEDOMX',
                'name' => 'FORMATO PAGO DE TENENCIA EDOMEX',
                'description' => 'Para solicitarlo se Requiere: NÚMERO DE PLACA',
                'cost' => 30.00,
                'price' => 50.00,
                'service_type' => 'VEHICULOS',
                'processing_time' => '10 Minutos',
                'form_schema' => [['name' => 'plate', 'label' => 'Número de Placa', 'type' => 'text', 'required' => true, 'regex' => '/^[A-Z0-9-]{6,9}$/']],
            ],
            [
                'code' => 'HOJ-REP',
                'name' => 'HOJA REPUVE',
                'description' => 'SERVICIO ACTIVO. ORDENAR SOLO CON LA PLACA',
                'cost' => 30.00,
                'price' => 50.00,
                'service_type' => 'VEHICULOS',
                'processing_time' => '1-20 Minutos',
                'form_schema' => [['name' => 'plate', 'label' => 'Placa', 'type' => 'text', 'required' => true, 'regex' => '/^[A-Z0-9-]{6,9}$/']],
            ],
        ];

        // Mapeo manual de imágenes exactas
        $imageMapping = [
            'acta-nacimiento-830' => 'Obten-Tu-Acta-de-Nacimiento-en-Linea-Facilmente.jpg',
            'acta-defuncion-830' => 'Obten-Tu-Acta-de-Nacimiento-en-Linea-Facilmente.jpg',
            'acta-divorcio-830' => 'Obten-Tu-Acta-de-Nacimiento-en-Linea-Facilmente.jpg',
            'acta-matrimonio-830' => 'Obten-Tu-Acta-de-Nacimiento-en-Linea-Facilmente.jpg',
            'csf-curp-clon' => 'images..jpg',
            'constancia-fiscal-rfc-idcif' => 'images..jpg',
            'localizar-idcif-estandar' => 'IDCIF SAT.jpg',
            'vigencia-derechos-curp' => 'CD-IMSS.jpg',
            'localizar-nss' => 'CD-IMSS.jpg',
            'semanas-cotizadas-detalladas' => 'CD-IMSS.jpg',
            'afore-localizacion' => 'Afore.jpg',
            'curp-actualizada' => 'curp.jpg',
            'recibo-cfe' => 'cfe.jpg',
            'antecedentes-no-penales-federal' => 'antecedentes.jpg',
            'estado-cuenta-mensual-infonavit' => 'infonavit..jpg',
            'reseteo-contrasena-infonavit' => 'infonavit..jpg',
            'estado-cuenta-historico-infonavit' => 'infonavit..jpg',
            'resumen-movimientos-infonavit' => 'infonavit..jpg',
            'FP-TCDMX' => 'TENENCIA CDMX.jpg',
            'FP-TEDOMX' => 'TENENCIA CDMX.jpg',
            'HOJ-REP' => 'REPUVE-Registro-Publico-Vehicular.jpg',
        ];

        foreach ($services as $data) {
            $imageName = $imageMapping[$data['code']] ?? null;

            Service::updateOrCreate(
                ['code' => $data['code']],
                array_merge($data, [
                    'image_path' => $imageName ? "services/{$imageName}" : null,
                    'is_active' => true,
                    'active_schedule' => '8:00 AM a 8:00 PM',
                ])
            );
        }
    }
}