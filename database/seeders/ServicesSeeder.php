<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Service;

class ServicesSeeder extends Seeder
{
    public function run(): void
    {
        $services = [
            // ACTAS
            [
                'code' => 'acta-nacimiento-730',
                'name' => 'Acta de Nacimiento (Prioritario)',
                'description' => 'SERVICIO ACTIVO Todos los días de 8:00 AM a 8:00 PM (Si ordena fuera de horario, el documento se entregará cuando el servicio esté activo Y NO CUENTAN ESAS HRS DE PROCESO) ORDENAR SOLO CON LA CURP 1-30 Minutos Tiempo de Entrega',
                'cost' => 14.00,
                'price' => 75.00,
                'suggested_price' => 130.00,
                'service_type' => 'ACTAS',
                'processing_time' => '1-30 Minutos',
                'form_schema' => [
                    ['name' => 'curp', 'label' => 'CURP', 'type' => 'text', 'required' => true, 'regex' => '/^[A-Z]{4}\d{6}[HM][A-Z]{2}[A-Z]{3}[A-Z0-9]{2}$/'],
                ],
            ],
            [
                'code' => 'acta-nacimiento-830',
                'name' => 'Acta de Nacimiento (Regular)',
                'description' => '',
                'cost' => 11.00,
                'price' => 60.00,
                'suggested_price' => 110.00,
                'service_type' => 'ACTAS',
                'processing_time' => '1-30 Minutos',
                'form_schema' => [
                    ['name' => 'curp', 'label' => 'CURP', 'type' => 'text', 'required' => true, 'regex' => '/^[A-Z]{4}\d{6}[HM][A-Z]{2}[A-Z]{3}[A-Z0-9]{2}$/'],
                ],
            ],
            [
                'code' => 'acta-nacimiento-foliada-rapida',
                'name' => 'Acta de Nacimiento Foliada',
                'description' => 'SERVICIO ACTIVO Todos los días de 8:00 AM a 8:00 PM (Si ordena fuera de horario, el documento se entregará cuando el servicio esté activo Y NO CUENTAN ESAS HRS DE PROCESO) ORDENAR SOLO CON LA CURP 1-30 Minutos Tiempo de Entrega',
                'cost' => 19.00,
                'price' => 70.00,
                'suggested_price' => 120.00,
                'service_type' => 'ACTAS',
                'processing_time' => '1-30 Minutos',
                'form_schema' => [
                    ['name' => 'curp', 'label' => 'CURP', 'type' => 'text', 'required' => true, 'regex' => '/^[A-Z]{4}\d{6}[HM][A-Z]{2}[A-Z]{3}[A-Z0-9]{2}$/'],
                ],
            ],
            [
                'code' => 'acta-defuncion-730',
                'name' => 'Acta de Defunción (Prioritario)',
                'description' => '',
                'cost' => 14.00,
                'price' => 75.00,
                'suggested_price' => 130.00,
                'service_type' => 'ACTAS',
                'processing_time' => '1-30 Minutos',
                'form_schema' => [
                    ['name' => 'curp', 'label' => 'CURP', 'type' => 'text', 'required' => true, 'regex' => '/^[A-Z]{4}\d{6}[HM][A-Z]{2}[A-Z]{3}[A-Z0-9]{2}$/'],
                ],
            ],
            [
                'code' => 'acta-defuncion-830',
                'name' => 'Acta de Defunción (Regular)',
                'description' => 'SERVICIO ACTIVO Todos los días de 8:00 AM a 8:00 PM (Si ordena fuera de horario, el documento se entregará cuando el servicio esté activo Y NO CUENTAN ESAS HRS DE PROCESO) ORDENAR SOLO CON LA CURP 1-30 Minutos Tiempo de Entrega',
                'cost' => 11.00,
                'price' => 60.00,
                'suggested_price' => 110.00,
                'service_type' => 'ACTAS',
                'processing_time' => '1-30 Minutos',
                'form_schema' => [
                    ['name' => 'curp', 'label' => 'CURP', 'type' => 'text', 'required' => true, 'regex' => '/^[A-Z]{4}\d{6}[HM][A-Z]{2}[A-Z]{3}[A-Z0-9]{2}$/'],
                ],
            ],
            [
                'code' => 'acta-defuncion-foliada-rapida',
                'name' => 'Acta de Defunción Foliada',
                'description' => 'SERVICIO ACTIVO Todos los días de 8:00 AM a 8:00 PM (Si ordena fuera de horario, el documento se entregará cuando el servicio esté activo Y NO CUENTAN ESAS HRS DE PROCESO) ORDENAR SOLO CON LA CURP 1-30 Minutos Tiempo de Entrega',
                'cost' => 19.00,
                'price' => 70.00,
                'suggested_price' => 120.00,
                'service_type' => 'ACTAS',
                'processing_time' => '1-30 Minutos',
                'form_schema' => [
                    ['name' => 'curp', 'label' => 'CURP', 'type' => 'text', 'required' => true, 'regex' => '/^[A-Z]{4}\d{6}[HM][A-Z]{2}[A-Z]{3}[A-Z0-9]{2}$/'],
                ],
            ],
            [
                'code' => 'acta-defuncion-rapida',
                'name' => 'Acta de Defunción (Rápida)',
                'description' => '',
                'cost' => 16.00,
                'price' => 70.00,
                'suggested_price' => 120.00,
                'service_type' => 'ACTAS',
                'processing_time' => '1-30 Minutos',
                'form_schema' => [
                    ['name' => 'curp', 'label' => 'CURP', 'type' => 'text', 'required' => true, 'regex' => '/^[A-Z]{4}\d{6}[HM][A-Z]{2}[A-Z]{3}[A-Z0-9]{2}$/'],
                ],
            ],
            [
                'code' => 'acta-divorcio-730',
                'name' => 'Acta de Divorcio (Prioritario)',
                'description' => 'SERVICIO ACTIVO Todos los días de 8:00 AM a 8:00 PM (Si ordena fuera de horario, el documento se entregará cuando el servicio esté activo Y NO CUENTAN ESAS HRS DE PROCESO) ORDENAR SOLO CON LA CURP DE ALGUNO DE LOS ESPOSOS 1-30 Minutos Tiempo de Entrega',
                'cost' => 14.00,
                'price' => 75.00,
                'suggested_price' => 130.00,
                'service_type' => 'ACTAS',
                'processing_time' => '1-30 Minutos',
                'form_schema' => [
                    ['name' => 'curp', 'label' => 'CURP de alguno de los esposos', 'type' => 'text', 'required' => true, 'regex' => '/^[A-Z]{4}\d{6}[HM][A-Z]{2}[A-Z]{3}[A-Z0-9]{2}$/'],
                ],
            ],
            [
                'code' => 'acta-divorcio-830',
                'name' => 'Acta de Divorcio (Regular)',
                'description' => '',
                'cost' => 11.00,
                'price' => 60.00,
                'suggested_price' => 110.00,
                'service_type' => 'ACTAS',
                'processing_time' => '1-30 Minutos',
                'form_schema' => [
                    ['name' => 'curp', 'label' => 'CURP de alguno de los esposos', 'type' => 'text', 'required' => true, 'regex' => '/^[A-Z]{4}\d{6}[HM][A-Z]{2}[A-Z]{3}[A-Z0-9]{2}$/'],
                ],
            ],
            [
                'code' => 'acta-divorcio-foliada-rapida',
                'name' => 'Acta de Divorcio Foliada',
                'description' => 'SERVICIO ACTIVO Todos los días de 8:00 AM a 8:00 PM (Si ordena fuera de horario, el documento se entregará cuando el servicio esté activo Y NO CUENTAN ESAS HRS DE PROCESO) ORDENAR SOLO CON LA CURP DE ALGUNO DE LOS ESPOSOS 1-30 Minutos Tiempo de Entrega',
                'cost' => 19.00,
                'price' => 70.00,
                'suggested_price' => 120.00,
                'service_type' => 'ACTAS',
                'processing_time' => '1-30 Minutos',
                'form_schema' => [
                    ['name' => 'curp', 'label' => 'CURP de alguno de los esposos', 'type' => 'text', 'required' => true, 'regex' => '/^[A-Z]{4}\d{6}[HM][A-Z]{2}[A-Z]{3}[A-Z0-9]{2}$/'],
                ],
            ],
            [
                'code' => 'acta-divorcio-rapida',
                'name' => 'Acta de Divorcio (Rápida)',
                'description' => '',
                'cost' => 16.00,
                'price' => 70.00,
                'suggested_price' => 120.00,
                'service_type' => 'ACTAS',
                'processing_time' => '1-30 Minutos',
                'form_schema' => [
                    ['name' => 'curp', 'label' => 'CURP de alguno de los esposos', 'type' => 'text', 'required' => true, 'regex' => '/^[A-Z]{4}\d{6}[HM][A-Z]{2}[A-Z]{3}[A-Z0-9]{2}$/'],
                ],
            ],
            [
                'code' => 'acta-matrimonio-730',
                'name' => 'Acta de Matrimonio (Prioritario)',
                'description' => 'SERVICIO ACTIVO Todos los días de 8:00 AM a 8:00 PM (Si ordena fuera de horario, el documento se entregará cuando el servicio esté activo Y NO CUENTAN ESAS HRS DE PROCESO) ORDENAR SOLO CON LA CURP DE ALGUNO DE LOS ESPOSOS 1-30 Minutos Tiempo de Entrega',
                'cost' => 14.00,
                'price' => 75.00,
                'suggested_price' => 130.00,
                'service_type' => 'ACTAS',
                'processing_time' => '1-30 Minutos',
                'form_schema' => [
                    ['name' => 'curp', 'label' => 'CURP de alguno de los esposos', 'type' => 'text', 'required' => true, 'regex' => '/^[A-Z]{4}\d{6}[HM][A-Z]{2}[A-Z]{3}[A-Z0-9]{2}$/'],
                ],
            ],
            [
                'code' => 'acta-matrimonio-830',
                'name' => 'Acta de Matrimonio (Regular)',
                'description' => '',
                'cost' => 11.00,
                'price' => 65.00,
                'suggested_price' => 120.00,
                'service_type' => 'ACTAS',
                'processing_time' => '1-30 Minutos',
                'form_schema' => [
                    ['name' => 'curp', 'label' => 'CURP de alguno de los esposos', 'type' => 'text', 'required' => true, 'regex' => '/^[A-Z]{4}\d{6}[HM][A-Z]{2}[A-Z]{3}[A-Z0-9]{2}$/'],
                ],
            ],
            [
                'code' => 'acta-matrimonio-foliada-rapida',
                'name' => 'Acta de Matrimonio Foliada',
                'description' => 'SERVICIO ACTIVO Todos los días de 8:00 AM a 8:00 PM (Si ordena fuera de horario, el documento se entregará cuando el servicio esté activo Y NO CUENTAN ESAS HRS DE PROCESO) ORDENAR SOLO CON LA CURP DE ALGUNO DE LOS ESPOSOS 1-30 Minutos Tiempo de Entrega',
                'cost' => 19.00,
                'price' => 70.00,
                'suggested_price' => 120.00,
                'service_type' => 'ACTAS',
                'processing_time' => '1-30 Minutos',
                'form_schema' => [
                    ['name' => 'curp', 'label' => 'CURP de alguno de los esposos', 'type' => 'text', 'required' => true, 'regex' => '/^[A-Z]{4}\d{6}[HM][A-Z]{2}[A-Z]{3}[A-Z0-9]{2}$/'],
                ],
            ],
            [
                'code' => 'acta-matrimonio-rapida',
                'name' => 'Acta de Matrimonio (Rápida)',
                'description' => '',
                'cost' => 16.00,
                'price' => 70.00,
                'suggested_price' => 120.00,
                'service_type' => 'ACTAS',
                'processing_time' => '1-30 Minutos',
                'form_schema' => [
                    ['name' => 'curp', 'label' => 'CURP de alguno de los esposos', 'type' => 'text', 'required' => true, 'regex' => '/^[A-Z]{4}\d{6}[HM][A-Z]{2}[A-Z]{3}[A-Z0-9]{2}$/'],
                ],
            ],

            // SAT
            [
                'code' => 'csf-curp-clon',
                'name' => 'Constancia de Situación Fiscal con CURP (Genérico)',
                'description' => 'LEER INFORMACION SERVICIO ACTIVO Todos los días 24/7 ORDENAR SOLO CON LA CURP 5 Minutos Tiempo de Entrega',
                'cost' => 14.00,
                'price' => 60.00,
                'suggested_price' => 100.00,
                'service_type' => 'SAT',
                'processing_time' => '5 Minutos',
                'form_schema' => [
                    ['name' => 'curp', 'label' => 'CURP', 'type' => 'text', 'required' => true, 'regex' => '/^[A-Z]{4}\d{6}[HM][A-Z]{2}[A-Z]{3}[A-Z0-9]{2}$/'],
                    ['name' => 'lugarEmision', 'label' => 'Lugar de Emisión (Ej. Cuauhtémoc, Ciudad de México)', 'type' => 'text', 'required' => true],
                ],
            ],
            [
                'code' => 'csf-curp-dia-despues',
                'name' => 'Constancia de Situación Fiscal con CURP (Nocturno)', // NOTA: el Excel traía el texto cortado sin el paréntesis de cierre, se completó
                'description' => '',
                'cost' => 46.00,
                'price' => 100.00,
                'suggested_price' => 150.00,
                'service_type' => 'SAT',
                'processing_time' => '10 Minutos',
                'form_schema' => [
                    ['name' => 'curp', 'label' => 'CURP', 'type' => 'text', 'required' => true, 'regex' => '/^[A-Z]{4}\d{6}[HM][A-Z]{2}[A-Z]{3}[A-Z0-9]{2}$/'],
                ],
            ],
            [
                'code' => 'constancia-fiscal-rfc-idcif',
                'name' => 'Constancia de Situación Fiscal con RFC y IDCIF',
                'description' => 'LEER INFORMACION SERVICIO ACTIVO Todos los días 24/7 ORDENAR SOLO CON EL RFC Y IDCIF 5 Minutos Tiempo de Entrega',
                'cost' => 14.00,
                'price' => 55.00,
                'suggested_price' => 90.00,
                'service_type' => 'SAT',
                'processing_time' => '5 Minutos',
                'form_schema' => [
                    ['name' => 'rfc', 'label' => 'RFC', 'type' => 'text', 'required' => true, 'regex' => '/^[A-ZÑ&]{3,4}\d{6}[A-Z0-9]{3}$/'],
                    ['name' => 'idcif', 'label' => 'IDCIF', 'type' => 'text', 'required' => true],
                ],
            ],
            [
                'code' => 'csf-curp-estandar',
                'name' => 'Constancia de Situación Fiscal con CURP (Lento)',
                'description' => '',
                'cost' => 51.00,
                'price' => 110.00,
                'suggested_price' => 160.00,
                'service_type' => 'SAT',
                'processing_time' => '10 Minutos',
                'form_schema' => [
                    ['name' => 'curp', 'label' => 'CURP', 'type' => 'text', 'required' => true, 'regex' => '/^[A-Z]{4}\d{6}[HM][A-Z]{2}[A-Z]{3}[A-Z0-9]{2}$/'],
                ],
            ],
            [
                'code' => 'csf-curp-express',
                'name' => 'Constancia de Situación Fiscal con CURP (Rápido)',
                'description' => '',
                'cost' => 66.00,
                'price' => 130.00,
                'suggested_price' => 180.00,
                'service_type' => 'SAT',
                'processing_time' => '10 Minutos',
                'form_schema' => [
                    ['name' => 'curp', 'label' => 'CURP', 'type' => 'text', 'required' => true, 'regex' => '/^[A-Z]{4}\d{6}[HM][A-Z]{2}[A-Z]{3}[A-Z0-9]{2}$/'],
                ],
            ],
            [
                'code' => 'csf-curp-inmediato',
                'name' => 'Constancia de Situación Fiscal con CURP (Inmediato)',
                'description' => '',
                'cost' => 88.00,
                'price' => 135.00,
                'suggested_price' => 185.00,
                'service_type' => 'SAT',
                'processing_time' => '10 Minutos',
                'form_schema' => [
                    ['name' => 'curp', 'label' => 'CURP', 'type' => 'text', 'required' => true, 'regex' => '/^[A-Z]{4}\d{6}[HM][A-Z]{2}[A-Z]{3}[A-Z0-9]{2}$/'],
                ],
            ],
            [
                'code' => 'csf-curp-original',
                'name' => 'Constancia de Situación Fiscal con CURP (Timbrada)',
                'description' => '',
                'cost' => 121.00,
                'price' => 175.00,
                'suggested_price' => 225.00,
                'service_type' => 'SAT',
                'processing_time' => '10 Minutos',
                'form_schema' => [
                    ['name' => 'curp', 'label' => 'CURP', 'type' => 'text', 'required' => true, 'regex' => '/^[A-Z]{4}\d{6}[HM][A-Z]{2}[A-Z]{3}[A-Z0-9]{2}$/'],
                ],
            ],
            [
                'code' => 'localizar-idcif-dia-despues',
                'name' => 'Localización IDCIF (Nocturno)',
                'description' => 'LEER INFORMACION SERVICIO ACTIVO DE LUNES A VIERNES de 8:00 AM a 8:00 PM (Si ordena fuera de horario, la orden saldra cuando este dentro de horario y no contaran esas hrs en proceso) ORDENAR SOLO CON EL RFC 1-6 Horas Tiempo de Entrega',
                'cost' => 41.00,
                'price' => 90.00,
                'suggested_price' => 130.00,
                'service_type' => 'SAT',
                'processing_time' => '10 Minutos',
                'form_schema' => [
                    ['name' => 'curp', 'label' => 'CURP', 'type' => 'text', 'required' => true, 'regex' => '/^[A-Z]{4}\d{6}[HM][A-Z]{2}[A-Z]{3}[A-Z0-9]{2}$/'],
                ],
            ],
            [
                'code' => 'localizar-idcif-estandar',
                'name' => 'Localizar IDCIF (Lento)',
                'description' => '',
                'cost' => 46.00,
                'price' => 100.00,
                'suggested_price' => 150.00,
                'service_type' => 'SAT',
                'processing_time' => '10 Minutos',
                'form_schema' => [
                    ['name' => 'curp', 'label' => 'CURP', 'type' => 'text', 'required' => true, 'regex' => '/^[A-Z]{4}\d{6}[HM][A-Z]{2}[A-Z]{3}[A-Z0-9]{2}$/'],
                ],
            ],
            [
                'code' => 'localizar-idcif-express',
                'name' => 'Localizar IDCIF (Rápido)',
                'description' => '',
                'cost' => 61.00,
                'price' => 110.00,
                'suggested_price' => 160.00,
                'service_type' => 'SAT',
                'processing_time' => '10 Minutos',
                'form_schema' => [
                    ['name' => 'curp', 'label' => 'CURP', 'type' => 'text', 'required' => true, 'regex' => '/^[A-Z]{4}\d{6}[HM][A-Z]{2}[A-Z]{3}[A-Z0-9]{2}$/'],
                ],
            ],
            [
                'code' => 'localizar-idcif-inmediato',
                'name' => 'Localización IDCIF (Inmediato)',
                'description' => '',
                'cost' => 83.00,
                'price' => 128.00,
                'suggested_price' => 173.00,
                'service_type' => 'SAT',
                'processing_time' => '10 Minutos',
                'form_schema' => [
                    ['name' => 'curp', 'label' => 'CURP', 'type' => 'text', 'required' => true, 'regex' => '/^[A-Z]{4}\d{6}[HM][A-Z]{2}[A-Z]{3}[A-Z0-9]{2}$/'],
                ],
            ],

            // IMSS
            [
                'code' => 'asignacion-nss',
                'name' => 'Asignación NSS',
                'description' => '',
                'cost' => 21.00,
                'price' => 45.00,
                'suggested_price' => 70.00,
                'service_type' => 'IMSS',
                'processing_time' => '10 Minutos',
                'form_schema' => [
                    ['name' => 'curp', 'label' => 'CURP', 'type' => 'text', 'required' => true, 'regex' => '/^[A-Z]{4}\d{6}[HM][A-Z]{2}[A-Z]{3}[A-Z0-9]{2}$/'],
                ],
            ],
            [
                'code' => 'localizar-nss',
                'name' => 'Localizar NSS',
                'description' => 'LEER INFORMACION SERVICIO ACTIVO Todos los días 24/7 ORDENAR SOLO CON LA CURP EST ORDEN SOLO LE ARROJARA EL NSS DE UNA CURP 5 Minutos Tiempo de Entrega',
                'cost' => 9.00,
                'price' => 20.00,
                'suggested_price' => 30.00,
                'service_type' => 'IMSS',
                'processing_time' => '10 Minutos',
                'form_schema' => [
                    ['name' => 'nss', 'label' => 'NSS (11 dígitos)', 'type' => 'text', 'required' => true, 'regex' => '/^\d{11}$/'],
                ],
            ],
            [
                'code' => 'semanas-cotizadas-detalladas',
                'name' => 'Semanas Cotizadas (Detalladas)',
                'description' => '',
                'cost' => 16.00,
                'price' => 35.00,
                'suggested_price' => 50.00,
                'service_type' => 'IMSS',
                'processing_time' => '10 Minutos',
                'form_schema' => [
                    ['name' => 'curp', 'label' => 'CURP', 'type' => 'text', 'required' => true, 'regex' => '/^[A-Z]{4}\d{6}[HM][A-Z]{2}[A-Z]{3}[A-Z0-9]{2}$/'],
                ],
            ],
            [
                'code' => 'semanas-cotizadas-sencilla',
                'name' => 'Semanas Cotizadas (Sencilla)',
                'description' => '',
                'cost' => 16.00,
                'price' => 35.00,
                'suggested_price' => 50.00,
                'service_type' => 'IMSS',
                'processing_time' => '10 Minutos',
                'form_schema' => [
                    ['name' => 'curp', 'label' => 'CURP', 'type' => 'text', 'required' => true, 'regex' => '/^[A-Z]{4}\d{6}[HM][A-Z]{2}[A-Z]{3}[A-Z0-9]{2}$/'],
                ],
            ],
            [
                'code' => 'vigencia-derechos-detalladas',
                'name' => 'Vigencia de Derechos',
                'description' => '',
                'cost' => 11.00,
                'price' => 25.00,
                'suggested_price' => 35.00,
                'service_type' => 'IMSS',
                'processing_time' => '10 Minutos',
                'form_schema' => [
                    ['name' => 'curp', 'label' => 'CURP', 'type' => 'text', 'required' => true, 'regex' => '/^[A-Z]{4}\d{6}[HM][A-Z]{2}[A-Z]{3}[A-Z0-9]{2}$/'],
                ],
            ],

            // AFORE
            [
                'code' => 'afore-localizacion',
                'name' => 'Localización Afore',
                'description' => 'LEER INFORMACION SERVICIO ACTIVO Todos los días 24/7 ORDENAR SOLO CON LA CURP 5 Minutos Tiempo de Entrega',
                'cost' => 9.00,
                'price' => 20.00,
                'suggested_price' => 40.00,
                'service_type' => 'AFORE',
                'processing_time' => '10 Minutos',
                'form_schema' => [
                    ['name' => 'curp', 'label' => 'CURP', 'type' => 'text', 'required' => true, 'regex' => '/^[A-Z]{4}\d{6}[HM][A-Z]{2}[A-Z]{3}[A-Z0-9]{2}$/'],
                ],
            ],

            // SERVICIOS
            [
                'code' => 'curp-actualizada',
                'name' => 'CURP Actualizada',
                'description' => 'LEER INFORMACION SERVICIO ACTIVO Todos los días 24/7 ORDENAR SOLO CON LA CURP 5 Minutos Tiempo de Entrega',
                'cost' => 4.00,
                'price' => 10.00,
                'suggested_price' => 16.00,
                'service_type' => 'SERVICIOS',
                'processing_time' => '10 Minutos',
                'form_schema' => [
                    ['name' => 'curp', 'label' => 'CURP', 'type' => 'text', 'required' => true, 'regex' => '/^[A-Z]{4}\d{6}[HM][A-Z]{2}[A-Z]{3}[A-Z0-9]{2}$/'],
                ],
            ],
            [
                'code' => 'curp-a-rfc',
                'name' => 'CURP a RFC',
                'description' => '',
                'cost' => 4.00,
                'price' => 10.00,
                'suggested_price' => 16.00,
                'service_type' => 'SERVICIOS',
                'processing_time' => '5 Minutos',
                'form_schema' => [
                    ['name' => 'curp', 'label' => 'CURP', 'type' => 'text', 'required' => true, 'regex' => '/^[A-Z]{4}\d{6}[HM][A-Z]{2}[A-Z]{3}[A-Z0-9]{2}$/'],
                ],
            ],
            [
                'code' => 'recibo-cfe',
                'name' => 'Recibo CFE',
                'description' => 'LEER INFORMACION SERVICIO ACTIVO Todos los días de 8:00 AM a 8:00 PM (Si ordena fuera de horario, el documento se entregará cuando el servicio esté activo Y NO CUENTAN ESAS HRS DE PROCESO) NORMALMENTE DMEORA DE 1 A 10 MINUTOS PERO LUEGO EL SISTEMA DE CFE ENTRA EN MANTENIMIENTO Y PEUDE DMEORAR HASTA 30 MINUTOS ORDENAR SOLO CON EL NUMERO DEL SERVICIO',
                'cost' => 6.00,
                'price' => 9.00,
                'suggested_price' => 12.00,
                'service_type' => 'SERVICIOS',
                'processing_time' => '10 Minutos',
                'form_schema' => [
                    ['name' => 'curp', 'label' => 'CURP', 'type' => 'text', 'required' => true, 'regex' => '/^[A-Z]{4}\d{6}[HM][A-Z]{2}[A-Z]{3}[A-Z0-9]{2}$/'],
                ],
            ],

            // INFONAVIT
            [
                'code' => 'aviso-retencion',
                'name' => 'Aviso de Retención',
                'description' => '',
                'cost' => 36.00,
                'price' => 66.00,
                'suggested_price' => 90.00,
                'service_type' => 'INFONAVIT',
                'processing_time' => '20 Minutos',
                'form_schema' => [
                    ['name' => 'nss', 'label' => 'NSS (11 dígitos)', 'type' => 'text', 'required' => true, 'regex' => '/^\d{11}$/'],
                ],
            ],
            [
                'code' => 'constancia-intereses-infonavit',
                'name' => 'Constancia de Intereses',
                'description' => '',
                'cost' => 36.00,
                'price' => 66.00,
                'suggested_price' => 90.00,
                'service_type' => 'INFONAVIT',
                'processing_time' => '20 Minutos',
                'form_schema' => [
                    ['name' => 'nss', 'label' => 'NSS (11 dígitos)', 'type' => 'text', 'required' => true, 'regex' => '/^\d{11}$/'],
                ],
            ],
            [
                'code' => 'consulta-datos-infonavit',
                'name' => 'Consultar Datos',
                'description' => '',
                'cost' => 36.00,
                'price' => 66.00,
                'suggested_price' => 90.00,
                'service_type' => 'INFONAVIT',
                'processing_time' => '20 Minutos',
                'form_schema' => [
                    ['name' => 'nss', 'label' => 'NSS (11 dígitos)', 'type' => 'text', 'required' => true, 'regex' => '/^\d{11}$/'],
                ],
            ],
            [
                'code' => 'consulta-saldo-detallado-infonavit',
                'name' => 'Saldo Detallado',
                'description' => '',
                'cost' => 36.00,
                'price' => 66.00,
                'suggested_price' => 90.00,
                'service_type' => 'INFONAVIT',
                'processing_time' => '20 Minutos',
                'form_schema' => [
                    ['name' => 'nss', 'label' => 'NSS (11 dígitos)', 'type' => 'text', 'required' => true, 'regex' => '/^\d{11}$/'],
                ],
            ],
            [
                'code' => 'consulta-saldo-infonavit',
                'name' => 'Consultar Saldo',
                'description' => '',
                'cost' => 36.00,
                'price' => 66.00,
                'suggested_price' => 90.00,
                'service_type' => 'INFONAVIT',
                'processing_time' => '20 Minutos',
                'form_schema' => [
                    ['name' => 'nss', 'label' => 'NSS (11 dígitos)', 'type' => 'text', 'required' => true, 'regex' => '/^\d{11}$/'],
                ],
            ],
            [
                'code' => 'desvinculacion-dispositivo-infonavit',
                'name' => 'Desvincular/Desbloquear Cuenta',
                'description' => '',
                'cost' => 36.00,
                'price' => 66.00,
                'suggested_price' => 90.00,
                'service_type' => 'INFONAVIT',
                'processing_time' => '20 Minutos',
                'form_schema' => [
                    ['name' => 'nss', 'label' => 'NSS (11 dígitos)', 'type' => 'text', 'required' => true, 'regex' => '/^\d{11}$/'],
                ],
            ],
            [
                'code' => 'estado-cuenta-historico-infonavit',
                'name' => 'Estado Cuenta Histórico',
                'description' => '',
                'cost' => 36.00,
                'price' => 66.00,
                'suggested_price' => 90.00,
                'service_type' => 'INFONAVIT',
                'processing_time' => '20 Minutos',
                'form_schema' => [
                    ['name' => 'nss', 'label' => 'NSS (11 dígitos)', 'type' => 'text', 'required' => true, 'regex' => '/^\d{11}$/'],
                ],
            ],
            [
                'code' => 'estado-cuenta-mensual-infonavit',
                'name' => 'Estado Cuenta Mensual',
                'description' => '',
                'cost' => 36.00,
                'price' => 66.00,
                'suggested_price' => 90.00,
                'service_type' => 'INFONAVIT',
                'processing_time' => '20 Minutos',
                'form_schema' => [
                    ['name' => 'nss', 'label' => 'NSS (11 dígitos)', 'type' => 'text', 'required' => true, 'regex' => '/^\d{11}$/'],
                ],
            ],
            [
                'code' => 'registro-nuevo-infonavit',
                'name' => 'Registrar Usuario',
                'description' => 'SERVICIO ACTIVO Todos los días de 8:00 AM a 8:00 PM (Si ordena fuera de horario, el documento se entregará cuando el servicio esté activo Y NO CUENTAN ESAS HRS DE PROCESO) ORDENAR SOLO CON EL NSS 1-30 Minutes Tiempo de Entrega',
                'cost' => 36.00,
                'price' => 66.00,
                'suggested_price' => 90.00,
                'service_type' => 'INFONAVIT',
                'processing_time' => '20 Minutos',
                'form_schema' => [
                    ['name' => 'nss', 'label' => 'NSS (11 dígitos)', 'type' => 'text', 'required' => true, 'regex' => '/^\d{11}$/'],
                ],
            ],
            [
                'code' => 'reseteo-completo-infonavit',
                'name' => 'Reseteo Completo',
                'description' => 'SERVICIO ACTIVO Todos los días de 8:00 AM a 8:00 PM (Si ordena fuera de horario, el documento se entregará cuando el servicio esté activo Y NO CUENTAN ESAS HRS DE PROCESO) ORDENAR SOLO CON NSS O NUEMRO DE CREDITO 1-30 Minutos Tiempo de Entrega',
                'cost' => 111.00,
                'price' => 200.00,
                'suggested_price' => 260.00,
                'service_type' => 'INFONAVIT',
                'processing_time' => '20 Minutos',
                'form_schema' => [
                    ['name' => 'nss', 'label' => 'NSS (11 dígitos)', 'type' => 'text', 'required' => true, 'regex' => '/^\d{11}$/'],
                ],
            ],
            [
                'code' => 'reseteo-contrasena-infonavit',
                'name' => 'Reset Contraseña',
                'description' => '',
                'cost' => 36.00,
                'price' => 66.00,
                'suggested_price' => 90.00,
                'service_type' => 'INFONAVIT',
                'processing_time' => '20 Minutos',
                'form_schema' => [
                    ['name' => 'nss', 'label' => 'NSS (11 dígitos)', 'type' => 'text', 'required' => true, 'regex' => '/^\d{11}$/'],
                ],
            ],
            [
                'code' => 'resumen-movimientos-infonavit',
                'name' => 'Resumen de Movimientos',
                'description' => '',
                'cost' => 36.00,
                'price' => 66.00,
                'suggested_price' => 90.00,
                'service_type' => 'INFONAVIT',
                'processing_time' => '20 Minutos',
                'form_schema' => [
                    ['name' => 'nss', 'label' => 'NSS (11 dígitos)', 'type' => 'text', 'required' => true, 'regex' => '/^\d{11}$/'],
                ],
            ],

            // ============================================================
            // PENDIENTES DE REVISIÓN
            // El Excel no traía dato suficiente (costo faltante) o no especificaba
            // con certeza qué campo debe pedir el formulario (CURP/RFC/NSS/placa/etc.).
            // Se dejó un valor supuesto para que el seeder no truene, pero debe
            // confirmarse con el proveedor antes de usarse en producción.
            // ============================================================
            // REVISAR: el Excel no especifica "TIPO SERVICIO" para esta fila; se asumió ACTAS por el contexto.
            [
                'code' => 'acta-nacimiento-rapida',
                'name' => 'Acta de Nacimiento (Rápida)',
                'description' => '',
                'cost' => 19.00,
                'price' => 70.00,
                'suggested_price' => 120.00,
                'service_type' => 'ACTAS',
                'processing_time' => '1-30 Minutos',
                'form_schema' => [
                    ['name' => 'curp', 'label' => 'CURP', 'type' => 'text', 'required' => true, 'regex' => '/^[A-Z]{4}\d{6}[HM][A-Z]{2}[A-Z]{3}[A-Z0-9]{2}$/'],
                ],
            ],

            // REVISAR: se asumió que el dato requerido es el RFC; confirmar con el proveedor.
            [
                'code' => 'cedula-datos-fiscales',
                'name' => 'Cédula de Datos Fiscales',
                'description' => '',
                'cost' => 41.00,
                'price' => 82.00,
                'suggested_price' => 120.00,
                'service_type' => 'SAT',
                'processing_time' => '10 Minutos',
                'form_schema' => [
                    ['name' => 'rfc', 'label' => 'RFC', 'type' => 'text', 'required' => true, 'regex' => '/^[A-ZÑ&]{3,4}\d{6}[A-Z0-9]{3}$/'],
                ],
            ],

            // REVISAR: no está claro qué dato exacto pide el proveedor para agendar la cita (CURP/RFC); se dejó CURP como supuesto.
            [
                'code' => 'cita-contribuyente',
                'name' => 'Cita Contribuyente',
                'description' => '',
                'cost' => 121.00,
                'price' => 220.00,
                'suggested_price' => 300.00,
                'service_type' => 'SAT',
                'processing_time' => '10 Minutos',
                'form_schema' => [
                    ['name' => 'curp', 'label' => 'CURP', 'type' => 'text', 'required' => true, 'regex' => '/^[A-Z]{4}\d{6}[HM][A-Z]{2}[A-Z]{3}[A-Z0-9]{2}$/'],
                ],
            ],

            // REVISAR: se asumió CURP como dato requerido; confirmar.
            [
                'code' => 'constancia-no-inhabilitacion',
                'name' => 'Constancia de No Inhabilitación',
                'description' => '',
                'cost' => 46.00,
                'price' => 100.00,
                'suggested_price' => 150.00,
                'service_type' => 'SAT',
                'processing_time' => '10 Minutos',
                'form_schema' => [
                    ['name' => 'curp', 'label' => 'CURP', 'type' => 'text', 'required' => true, 'regex' => '/^[A-Z]{4}\d{6}[HM][A-Z]{2}[A-Z]{3}[A-Z0-9]{2}$/'],
                ],
            ],

            // REVISAR: se asumió que solo requiere RFC (persona moral, sin CURP); confirmar si necesita algún dato adicional (ej. contraseña del SAT).
            [
                'code' => 'csf-persona-moral-timbrada',
                'name' => 'Constancia de Situación Fiscal Persona Moral (Timbrada)',
                'description' => '',
                'cost' => 151.00,
                'price' => 210.00,
                'suggested_price' => 260.00,
                'service_type' => 'SAT',
                'processing_time' => '10 Minutos',
                'form_schema' => [
                    ['name' => 'rfc', 'label' => 'RFC', 'type' => 'text', 'required' => true, 'regex' => '/^[A-ZÑ&]{3,4}\d{6}[A-Z0-9]{3}$/'],
                ],
            ],

            // REVISAR: no está claro qué es "Cuenta Diario" ni qué dato requiere; se dejó RFC como supuesto, favor de confirmar.
            [
                'code' => 'cuenta-diario-sat',
                'name' => 'Cuenta Dario',
                'description' => '',
                'cost' => 51.00,
                'price' => 110.00,
                'suggested_price' => 160.00,
                'service_type' => 'SAT',
                'processing_time' => '10 Minutos',
                'form_schema' => [
                    ['name' => 'rfc', 'label' => 'RFC', 'type' => 'text', 'required' => true, 'regex' => '/^[A-ZÑ&]{3,4}\d{6}[A-Z0-9]{3}$/'],
                ],
            ],

            // REVISAR: se asumió que requiere RFC (y posiblemente contraseña del SAT, no incluida); confirmar.
            [
                'code' => 'opinion-cumplimiento',
                'name' => 'Opinión del Cumplimiento',
                'description' => '',
                'cost' => 81.00,
                'price' => 125.00,
                'suggested_price' => 170.00,
                'service_type' => 'SAT',
                'processing_time' => '10 Minutos',
                'form_schema' => [
                    ['name' => 'rfc', 'label' => 'RFC', 'type' => 'text', 'required' => true, 'regex' => '/^[A-ZÑ&]{3,4}\d{6}[A-Z0-9]{3}$/'],
                ],
            ],

            // REVISAR: se asumió que requiere NSS; confirmar.
            [
                'code' => 'certificado-derechos-su63',
                'name' => 'Certificado de Derechos (SU63)',
                'description' => '',
                'cost' => 71.00,
                'price' => 150.00,
                'suggested_price' => 200.00,
                'service_type' => 'IMSS',
                'processing_time' => '10 Minutos',
                'form_schema' => [
                    ['name' => 'nss', 'label' => 'NSS (11 dígitos)', 'type' => 'text', 'required' => true, 'regex' => '/^\d{11}$/'],
                ],
            ],

            // REVISAR: se asumió CURP como dato requerido; confirmar.
            [
                'code' => 'no-afiliacion-issste',
                'name' => 'No Afiliación ISSSTE',
                'description' => '',
                'cost' => 11.00,
                'price' => 22.00,
                'suggested_price' => 33.00,
                'service_type' => 'IMSS',
                'processing_time' => '10 Minutos',
                'form_schema' => [
                    ['name' => 'curp', 'label' => 'CURP', 'type' => 'text', 'required' => true, 'regex' => '/^[A-Z]{4}\d{6}[HM][A-Z]{2}[A-Z]{3}[A-Z0-9]{2}$/'],
                ],
            ],

            // REVISAR: se asumió CURP; confirmar si en realidad requiere NSS.
            [
                'code' => 'pantalla-asignacion',
                'name' => 'Pantalla de Asignación',
                'description' => 'LEER INFORMACION SERVICIO ACTIVO Todos los días 24/7 ORDENAR SOLO CON LA CURP Y EL NSS EJEMPLO DE COMO ORDENAR: MAGG930214HGRRRR03 :46496449745 10 Minutos Tiempo de Entrega',
                'cost' => 26.00,
                'price' => 55.00,
                'suggested_price' => 80.00,
                'service_type' => 'IMSS',
                'processing_time' => '10 Minutos',
                'form_schema' => [
                    ['name' => 'curp', 'label' => 'CURP', 'type' => 'text', 'required' => true, 'regex' => '/^[A-Z]{4}\d{6}[HM][A-Z]{2}[A-Z]{3}[A-Z0-9]{2}$/'],
                ],
            ],

            // REVISAR: no está claro qué dato requiere "Pantalla CFDI"; se dejó NSS como supuesto, confirmar.
            [
                'code' => 'pantalla-cfdi',
                'name' => 'Pantalla CFDI',
                'description' => '',
                'cost' => 26.00,
                'price' => 55.00,
                'suggested_price' => 80.00,
                'service_type' => 'IMSS',
                'processing_time' => '10 Minutos',
                'form_schema' => [
                    ['name' => 'nss', 'label' => 'NSS (11 dígitos)', 'type' => 'text', 'required' => true, 'regex' => '/^\d{11}$/'],
                ],
            ],

            // REVISAR: se asumió NSS; confirmar.
            [
                'code' => 'pantalla-detalle-prestamos',
                'name' => 'Pantalla Detalle de Préstamos',
                'description' => '',
                'cost' => 26.00,
                'price' => 55.00,
                'suggested_price' => 80.00,
                'service_type' => 'IMSS',
                'processing_time' => '10 Minutos',
                'form_schema' => [
                    ['name' => 'nss', 'label' => 'NSS (11 dígitos)', 'type' => 'text', 'required' => true, 'regex' => '/^\d{11}$/'],
                ],
            ],

            // REVISAR: se asumió NSS; confirmar.
            [
                'code' => 'pantalla-dictamen-st3',
                'name' => 'Pantalla de Dictamen ST3',
                'description' => '',
                'cost' => 26.00,
                'price' => 55.00,
                'suggested_price' => 80.00,
                'service_type' => 'IMSS',
                'processing_time' => '10 Minutos',
                'form_schema' => [
                    ['name' => 'nss', 'label' => 'NSS (11 dígitos)', 'type' => 'text', 'required' => true, 'regex' => '/^\d{11}$/'],
                ],
            ],

            // REVISAR: se asumió NSS; confirmar.
            [
                'code' => 'pantalla-dictamen-st4',
                'name' => 'Pantalla de Dictamen ST4',
                'description' => '',
                'cost' => 26.00,
                'price' => 55.00,
                'suggested_price' => 80.00,
                'service_type' => 'IMSS',
                'processing_time' => '10 Minutos',
                'form_schema' => [
                    ['name' => 'nss', 'label' => 'NSS (11 dígitos)', 'type' => 'text', 'required' => true, 'regex' => '/^\d{11}$/'],
                ],
            ],

            // REVISAR: se asumió CURP; confirmar si requiere NSS.
            [
                'code' => 'pantalla-direccion-telefono',
                'name' => 'Pantalla de Dirección y Teléfono',
                'description' => '',
                'cost' => 26.00,
                'price' => 55.00,
                'suggested_price' => 80.00,
                'service_type' => 'IMSS',
                'processing_time' => '10 Minutos',
                'form_schema' => [
                    ['name' => 'curp', 'label' => 'CURP', 'type' => 'text', 'required' => true, 'regex' => '/^[A-Z]{4}\d{6}[HM][A-Z]{2}[A-Z]{3}[A-Z0-9]{2}$/'],
                ],
            ],

            // REVISAR: no está claro a qué informe se refiere ni el dato requerido; se dejó NSS como supuesto, confirmar.
            [
                'code' => 'pantalla-informe',
                'name' => 'Pantalla de Informe',
                'description' => '',
                'cost' => 26.00,
                'price' => 55.00,
                'suggested_price' => 80.00,
                'service_type' => 'IMSS',
                'processing_time' => '10 Minutos',
                'form_schema' => [
                    ['name' => 'nss', 'label' => 'NSS (11 dígitos)', 'type' => 'text', 'required' => true, 'regex' => '/^\d{11}$/'],
                ],
            ],

            // REVISAR: se asumió NSS; confirmar.
            [
                'code' => 'pantalla-viudez-completa',
                'name' => 'Pantalla de Viudez Completa',
                'description' => '',
                'cost' => 26.00,
                'price' => 55.00,
                'suggested_price' => 80.00,
                'service_type' => 'IMSS',
                'processing_time' => '10 Minutos',
                'form_schema' => [
                    ['name' => 'nss', 'label' => 'NSS (11 dígitos)', 'type' => 'text', 'required' => true, 'regex' => '/^\d{11}$/'],
                ],
            ],

            // REVISAR: se asumió NSS; confirmar.
            [
                'code' => 'sindo-alfanumerico',
                'name' => 'SINDO Alfanumérico',
                'description' => '',
                'cost' => 36.00,
                'price' => 66.00,
                'suggested_price' => 96.00,
                'service_type' => 'IMSS',
                'processing_time' => '10 Minutos',
                'form_schema' => [
                    ['name' => 'nss', 'label' => 'NSS (11 dígitos)', 'type' => 'text', 'required' => true, 'regex' => '/^\d{11}$/'],
                ],
            ],

            // REVISAR: se asumió NSS; confirmar.
            [
                'code' => 'sindo-completo',
                'name' => 'SINDO Completo',
                'description' => '',
                'cost' => 146.00,
                'price' => 200.00,
                'suggested_price' => 250.00,
                'service_type' => 'IMSS',
                'processing_time' => '10 Minutos',
                'form_schema' => [
                    ['name' => 'nss', 'label' => 'NSS (11 dígitos)', 'type' => 'text', 'required' => true, 'regex' => '/^\d{11}$/'],
                ],
            ],

            // REVISAR: se asumió NSS; confirmar.
            [
                'code' => 'sindo-pantalla-f3',
                'name' => 'SINDO Pantalla F3',
                'description' => '',
                'cost' => 31.00,
                'price' => 55.00,
                'suggested_price' => 75.00,
                'service_type' => 'IMSS',
                'processing_time' => '10 Minutos',
                'form_schema' => [
                    ['name' => 'nss', 'label' => 'NSS (11 dígitos)', 'type' => 'text', 'required' => true, 'regex' => '/^\d{11}$/'],
                ],
            ],

            // REVISAR: se asumió NSS; confirmar.
            [
                'code' => 'sindo-pension',
                'name' => 'SINDO Pensión',
                'description' => '',
                'cost' => 71.00,
                'price' => 125.00,
                'suggested_price' => 175.00,
                'service_type' => 'IMSS',
                'processing_time' => '10 Minutos',
                'form_schema' => [
                    ['name' => 'nss', 'label' => 'NSS (11 dígitos)', 'type' => 'text', 'required' => true, 'regex' => '/^\d{11}$/'],
                ],
            ],

            // REVISAR: se asumió NSS; confirmar.
            [
                'code' => 'sindo-ultimo-retiro',
                'name' => 'SINDO Último Retiro',
                'description' => '',
                'cost' => 36.00,
                'price' => 56.00,
                'suggested_price' => 76.00,
                'service_type' => 'IMSS',
                'processing_time' => '10 Minutos',
                'form_schema' => [
                    ['name' => 'nss', 'label' => 'NSS (11 dígitos)', 'type' => 'text', 'required' => true, 'regex' => '/^\d{11}$/'],
                ],
            ],

            // REVISAR: se asumió NSS; confirmar.
            [
                'code' => 'sistrap-pension-general',
                'name' => 'SISTRAP Pensión General',
                'description' => '',
                'cost' => 96.00,
                'price' => 125.00,
                'suggested_price' => 155.00,
                'service_type' => 'IMSS',
                'processing_time' => '10 Minutos',
                'form_schema' => [
                    ['name' => 'nss', 'label' => 'NSS (11 dígitos)', 'type' => 'text', 'required' => true, 'regex' => '/^\d{11}$/'],
                ],
            ],

            // REVISAR: se asumió NSS; confirmar.
            [
                'code' => 'sistrap-pension-nomina',
                'name' => 'SISTRAP Pensión Nómina',
                'description' => '',
                'cost' => 96.00,
                'price' => 125.00,
                'suggested_price' => 155.00,
                'service_type' => 'IMSS',
                'processing_time' => '10 Minutos',
                'form_schema' => [
                    ['name' => 'nss', 'label' => 'NSS (11 dígitos)', 'type' => 'text', 'required' => true, 'regex' => '/^\d{11}$/'],
                ],
            ],

            // REVISAR: se asumió CURP; confirmar.
            [
                'code' => 'afore-contrasena',
                'name' => 'Contraseña Afore',
                'description' => '',
                'cost' => 46.00,
                'price' => 86.00,
                'suggested_price' => 116.00,
                'service_type' => 'AFORE',
                'processing_time' => '10 Minutos',
                'form_schema' => [
                    ['name' => 'curp', 'label' => 'CURP', 'type' => 'text', 'required' => true, 'regex' => '/^[A-Z]{4}\d{6}[HM][A-Z]{2}[A-Z]{3}[A-Z0-9]{2}$/'],
                ],
            ],

            // REVISAR: se asumió CURP; confirmar.
            [
                'code' => 'afore-perfil',
                'name' => 'Perfil Afore',
                'description' => '',
                'cost' => 66.00,
                'price' => 125.00,
                'suggested_price' => 180.00,
                'service_type' => 'AFORE',
                'processing_time' => '10 Minutos',
                'form_schema' => [
                    ['name' => 'curp', 'label' => 'CURP', 'type' => 'text', 'required' => true, 'regex' => '/^[A-Z]{4}\d{6}[HM][A-Z]{2}[A-Z]{3}[A-Z0-9]{2}$/'],
                ],
            ],

            // REVISAR: se asumió CURP; confirmar.
            [
                'code' => 'curp-estado-emergencia',
                'name' => 'CURP Estado de Emergencia',
                'description' => '',
                'cost' => 16.00,
                'price' => 32.00,
                'suggested_price' => 45.00,
                'service_type' => 'SERVICIOS',
                'processing_time' => '5 Minutos',
                'form_schema' => [
                    ['name' => 'curp', 'label' => 'CURP', 'type' => 'text', 'required' => true, 'regex' => '/^[A-Z]{4}\d{6}[HM][A-Z]{2}[A-Z]{3}[A-Z0-9]{2}$/'],
                ],
            ],

            // REVISAR: se asumió NSS; confirmar si requiere CURP en su lugar.
            [
                'code' => 'certificado-medico-imss',
                'name' => 'Certificado Médico IMSS',
                'description' => '',
                'cost' => 21.00,
                'price' => 45.00,
                'suggested_price' => 65.00,
                'service_type' => 'SERVICIOS',
                'processing_time' => '10 Minutos',
                'form_schema' => [
                    ['name' => 'nss', 'label' => 'NSS (11 dígitos)', 'type' => 'text', 'required' => true, 'regex' => '/^\d{11}$/'],
                ],
            ],
            // REVISAR: el Excel trae P. sugerido = 120 (con precio tienda 70 y sin costo), pero aquí el precio es 360.
            // Un sugerido de 120 quedaría por debajo del precio/costo, así que se dejó igual al precio (360). Ajustar si corresponde.
            [
                'code' => 'antecedentes-no-penales-federal',
                'name' => 'Antecedentes No Penales Federa',
                'description' => 'LEER INFORMACION SERVICIO ACTIVO Todos los días 24/7 ORDENAR CON TODOS LOS DATOS SOLICITADOS: *CURP *NOMBRES *APELLIDO PATERNO *APELLIDO MATERNO *DOMICILIO *CLAVE ELECTOR 5 Minutos Tiempo de Entrega',
                'cost' => 256,
                'price' => 360.00,
                'suggested_price' => 360.00,
                'service_type' => 'SERVICIOS',
                'processing_time' => '5 Minutos',
                'form_schema' => [
                    ['name' => 'curp', 'label' => 'CURP', 'type' => 'text', 'required' => true, 'regex' => '/^[A-Z]{4}\d{6}[HM][A-Z]{2}[A-Z]{3}[A-Z0-9]{2}$/'],
                    ['name' => 'nombreInstitucion', 'label' => 'Nombre Institución', 'type' => 'text', 'required' => true],
                ],
            ],

            // REVISAR: se asumió NSS; confirmar si Mejoravit requiere otro dato (usuario/correo).
            [
                'code' => 'cambio-contrasena-mejoravit',
                'name' => 'Cambio de Contraseña Mejoravit',
                'description' => '',
                'cost' => 136.00,
                'price' => 200.00,
                'suggested_price' => 250.00,
                'service_type' => 'INFONAVIT',
                'processing_time' => '20 Minutos',
                'form_schema' => [
                    ['name' => 'nss', 'label' => 'NSS (11 dígitos)', 'type' => 'text', 'required' => true, 'regex' => '/^\d{11}$/'],
                ],
            ],

            // REVISAR: se asumió que requiere NSS + RFC correcto; confirmar.
            [
                'code' => 'correccion-rfc-infonavit',
                'name' => 'Corrección RFC',
                'description' => '',
                'cost' => 151.00,
                'price' => 251.00,
                'suggested_price' => 321.00,
                'service_type' => 'INFONAVIT',
                'processing_time' => '20 Minutos',
                'form_schema' => [
                    ['name' => 'nss', 'label' => 'NSS (11 dígitos)', 'type' => 'text', 'required' => true, 'regex' => '/^\d{11}$/'],
                    ['name' => 'rfc', 'label' => 'RFC correcto', 'type' => 'text', 'required' => true, 'regex' => '/^[A-ZÑ&]{3,4}\d{6}[A-Z0-9]{3}$/'],
                ],
            ],

            // REVISAR: se asumió NSS; confirmar.
            [
                'code' => 'desbloqueo-buro-oci',
                'name' => 'Desbloqueo de Buró (OCI)',
                'description' => '',
                'cost' => 106.00,
                'price' => 200.00,
                'suggested_price' => 260.00,
                'service_type' => 'INFONAVIT',
                'processing_time' => '20 Minutos',
                'form_schema' => [
                    ['name' => 'nss', 'label' => 'NSS (11 dígitos)', 'type' => 'text', 'required' => true, 'regex' => '/^\d{11}$/'],
                ],
            ],

            // REVISAR: se asumió NSS; confirmar.
            [
                'code' => 'eliminar-cuenta-mejoravit',
                'name' => 'Eliminar Cuenta Mejoravit',
                'description' => '',
                'cost' => 136.00,
                'price' => 200.00,
                'suggested_price' => 250.00,
                'service_type' => 'INFONAVIT',
                'processing_time' => '20 Minutos',
                'form_schema' => [
                    ['name' => 'nss', 'label' => 'NSS (11 dígitos)', 'type' => 'text', 'required' => true, 'regex' => '/^\d{11}$/'],
                ],
            ],

            // REVISAR: se asumió NSS; confirmar.
            [
                'code' => 'precalificacion-bansefi',
                'name' => 'Precalificación BANSEFI',
                'description' => 'SERVICIO ACTIVO Todos los días de 8:00 AM a 8:00 PM (Si ordena fuera de horario, el documento se entregará cuando el servicio esté activo Y NO CUENTAN ESAS HRS DE PROCESO) ORDENAR CON NUMERO DE CREDITO 1-60 Minutos Tiempo de Entrega',
                'cost' => 21.00,
                'price' => 42.00,
                'suggested_price' => 62.00,
                'service_type' => 'INFONAVIT',
                'processing_time' => '10 Minutos',
                'form_schema' => [
                    ['name' => 'nss', 'label' => 'NSS (11 dígitos)', 'type' => 'text', 'required' => true, 'regex' => '/^\d{11}$/'],
                ],
            ],

            // REVISAR: se asumió NSS; confirmar.
            [
                'code' => 'precalificacion-mejoravit',
                'name' => 'Precalificación BANSEFI',
                'description' => 'SERVICIO ACTIVO Para solicitarlo solo se requiere: NSS (Simplemente complete los datos y proceda a pagar sus pedidos utilizando su saldo de crédito) 1-20 Minutos Tiempo de Entrega',
                'cost' => 66.00,
                'price' => 120.00,
                'suggested_price' => 170.00,
                'service_type' => 'INFONAVIT',
                'processing_time' => '10 Minutos',
                'form_schema' => [
                    ['name' => 'nss', 'label' => 'NSS (11 dígitos)', 'type' => 'text', 'required' => true, 'regex' => '/^\d{11}$/'],
                ],
            ],

            // REVISAR: en el Excel aparece como tipo INFONAVIT, se corrigió a VEHICULOS por ser un "Permiso de Conducir". Es "Sin Placa" (trámite para vehículo nuevo), por lo que probablemente requiera datos del vehículo/serie y no un número de placa; falta definir el formulario exacto.
            [
                'code' => 'permiso-conducir-aguascalientes',
                'name' => 'Permiso de Conducir Sin Placa Genérico (Aguascalientes)',
                'description' => '',
                'cost' => 66.00,
                'price' => 130.00,
                'suggested_price' => 190.00,
                'service_type' => 'VEHICULOS',
                'processing_time' => '20 Minutos',
                'form_schema' => [
                    ['name' => 'plate', 'label' => 'Número de Placa', 'type' => 'text', 'required' => true, 'regex' => '/^[A-Z0-9-]{6,9}$/'],
                ],
            ],

            // REVISAR: es "Sin Placa" (trámite para vehículo nuevo); falta definir el formulario exacto (probablemente serie/VIN, no placa).
            [
                'code' => 'permiso-conducir-chiapas',
                'name' => 'Permiso de Conducir Sin Placa Genérico (Chiapas)',
                'description' => '',
                'cost' => 66.00,
                'price' => 130.00,
                'suggested_price' => 180.00,
                'service_type' => 'VEHICULOS',
                'processing_time' => '20 Minutos',
                'form_schema' => [
                    ['name' => 'plate', 'label' => 'Número de Placa', 'type' => 'text', 'required' => true, 'regex' => '/^[A-Z0-9-]{6,9}$/'],
                ],
            ],

            // REVISAR: es "Sin Placa"; falta definir el formulario exacto.
            [
                'code' => 'permiso-conducir-colima',
                'name' => 'Permiso de Conducir Sin Placa Genérico (Colima)',
                'description' => '',
                'cost' => 96.00,
                'price' => 190.00,
                'suggested_price' => 250.00,
                'service_type' => 'VEHICULOS',
                'processing_time' => '20 Minutos',
                'form_schema' => [
                    ['name' => 'plate', 'label' => 'Número de Placa', 'type' => 'text', 'required' => true, 'regex' => '/^[A-Z0-9-]{6,9}$/'],
                ],
            ],

            // REVISAR: es "Sin Placa"; falta definir el formulario exacto.
            [
                'code' => 'permiso-conducir-guerrero',
                'name' => 'Permiso de Conducir Sin Placa (Copalillo, Guerrero)',
                'description' => 'Para solicitarlo se Requiere: NÚMERO DE PLACA AÑO A PAGAR 10 Minutos Tiempo de Entrega',
                'cost' => 46.00,
                'price' => 100.00,
                'suggested_price' => 150.00,
                'service_type' => 'VEHICULOS',
                'processing_time' => '20 Minutos',
                'form_schema' => [
                    ['name' => 'plate', 'label' => 'Número de Placa', 'type' => 'text', 'required' => true, 'regex' => '/^[A-Z0-9-]{6,9}$/'],
                ],
            ],

            // REVISAR: es "Sin Placa"; falta definir el formulario exacto.
            [
                'code' => 'permiso-conducir-huitzuco',
                'name' => 'Permiso de Conducir Sin Placa (Huitzuco, Guerrero)',
                'description' => '"Para solicitarlo se Requiere: NÚMERO DE PLACA 10 Minutos Tiempo de Entrega',
                'cost' => 46.00,
                'price' => 100.00,
                'suggested_price' => 150.00,
                'service_type' => 'VEHICULOS',
                'processing_time' => '20 Minutos',
                'form_schema' => [
                    ['name' => 'plate', 'label' => 'Número de Placa', 'type' => 'text', 'required' => true, 'regex' => '/^[A-Z0-9-]{6,9}$/'],
                ],
            ],

            // REVISAR: es "Sin Placa"; falta definir el formulario exacto.
            [
                'code' => 'permiso-conducir-jalisco',
                'name' => 'Permiso de Conducir Sin Placa (Jalisco)',
                'description' => 'SERVICIO ACTIVO Todos los días de 8:00 AM a 8:00 PM (Si ordena fuera de horario, el documento se entregará cuando el servicio esté activo Y NO CUENTAN ESAS HRS DE PROCESO) ORDENAR SOLO CON LA PLACA 1-20 Minutos Tiempo de Entrega',
                'cost' => 66.00,
                'price' => 130.00,
                'suggested_price' => 180.00,
                'service_type' => 'VEHICULOS',
                'processing_time' => '20 Minutos',
                'form_schema' => [
                    ['name' => 'plate', 'label' => 'Número de Placa', 'type' => 'text', 'required' => true, 'regex' => '/^[A-Z0-9-]{6,9}$/'],
                ],
            ],

            // REVISAR: es "Sin Placa"; falta definir el formulario exacto.
            [
                'code' => 'permiso-conducir-tlapa',
                'name' => 'Permiso de Conducir Sin Placa (Tlapa de Comonfort, Guerrero)',
                'description' => '',
                'cost' => 56.00,
                'price' => 110.00,
                'suggested_price' => 160.00,
                'service_type' => 'VEHICULOS',
                'processing_time' => '20 Minutos',
                'form_schema' => [
                    ['name' => 'plate', 'label' => 'Número de Placa', 'type' => 'text', 'required' => true, 'regex' => '/^[A-Z0-9-]{6,9}$/'],
                ],
            ],

            // REVISAR: no está claro qué dato exacto se requiere (placa, serie/VIN, factura anterior); falta definir el formulario.
            [
                'code' => 'refacturacion',
                'name' => 'Refacturación',
                'description' => '',
                'cost' => 351.00,
                'price' => 720.00,
                'suggested_price' => 900.00,
                'service_type' => 'VEHICULOS',
                'processing_time' => '30 Minutos',
                'form_schema' => [
                    ['name' => 'plate', 'label' => 'Número de Placa', 'type' => 'text', 'required' => true, 'regex' => '/^[A-Z0-9-]{6,9}$/'],
                ],
            ],

        ];

        $categoryMapping = [
            'ACTAS' => 'Actas',
            'SAT' => 'SAT',
            'IMSS' => 'IMSS',
            'SERVICIOS' => 'Servicios Generales',
            'INFONAVIT' => 'Infonavit',
            'VEHICULOS' => 'Vehículos',
            'AFORE' => 'Afore',
        ];

        foreach ($services as $data) {
            $categoryName = $categoryMapping[$data['service_type']] ?? $data['service_type'];
            $category = \App\Models\Category::firstOrCreate(['name' => $categoryName]);

            Service::updateOrCreate(
                ['code' => $data['code']],
                array_merge($data, [
                    'category_id' => $category->id,
                    'is_active' => true,
                    'active_schedule' => '8:00 AM a 8:00 PM',
                ])
            );
        }
    }
}