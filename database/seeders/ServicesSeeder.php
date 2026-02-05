<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
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
            [
                'code' => 'ACT-NAC',
                'name' => 'Acta de Nacimiento',
                'description' => 'SERVICIO ACTIVO Todos los días de 8:00 AM a 8:00 PM (Si ordena fuera de horario, el documento se entregará cuando el servicio esté activo Y NO CUENTAN ESAS HRS DE PROCESO) ORDENAR SOLO CON LA CURP',
                'price' => 70.00,
                'service_type' => 'ACTAS',
                'processing_time' => '1-30 Minutos',
                'image_path' => null,
                'form_schema' => [
                    ['name' => 'curp', 'label' => 'CURP', 'type' => 'text', 'required' => true],
                ],
            ],
            [
                'code' => 'ACT-DEF',
                'name' => 'Acta de Defunción',
                'description' => 'SERVICIO ACTIVO Todos los días de 8:00 AM a 8:00 PM (Si ordena fuera de horario, el documento se entregará cuando el servicio esté activo Y NO CUENTAN ESAS HRS DE PROCESO) ORDENAR SOLO CON LA CURP',
                'price' => 70.00,
                'service_type' => 'ACTAS',
                'processing_time' => '1-30 Minutos',
                'image_path' => null,
                'form_schema' => [
                    ['name' => 'curp', 'label' => 'CURP', 'type' => 'text', 'required' => true],
                ],
            ],
            [
                'code' => 'ACT-DIV',
                'name' => 'Acta de Divorcio',
                'description' => 'SERVICIO ACTIVO Todos los días de 8:00 AM a 8:00 PM (Si ordena fuera de horario, el documento se entregará cuando el servicio esté activo Y NO CUENTAN ESAS HRS DE PROCESO) ORDENAR SOLO CON LA CURP DE ALGUNO DE LOS ESPOSOS',
                'price' => 70.00,
                'service_type' => 'ACTAS',
                'processing_time' => '1-30 Minutos',
                'image_path' => null,
                'form_schema' => [
                    ['name' => 'curp', 'label' => 'CURP de alguno de los esposos', 'type' => 'text', 'required' => true],
                ],
            ],
            [
                'code' => 'ACT-MAT',
                'name' => 'Acta de Matrimonio',
                'description' => 'SERVICIO ACTIVO Todos los días de 8:00 AM a 8:00 PM (Si ordena fuera de horario, el documento se entregará cuando el servicio esté activo Y NO CUENTAN ESAS HRS DE PROCESO) ORDENAR SOLO CON LA CURP DE ALGUNO DE LOS ESPOSOS',
                'price' => 70.00,
                'service_type' => 'ACTAS',
                'processing_time' => '1-30 Minutos',
                'image_path' => null,
                'form_schema' => [
                    ['name' => 'curp', 'label' => 'CURP de alguno de los esposos', 'type' => 'text', 'required' => true],
                ],
            ],
            [
                'code' => 'CSF-01',
                'name' => 'CSF Clon con CURP',
                'description' => 'LEER INFORMACION SERVICIO ACTIVO Todos los días 24/7 ORDENAR SOLO CON LA CURP',
                'price' => 60.00,
                'service_type' => 'SAT',
                'processing_time' => '5 Minutos',
                'image_path' => null,
                'form_schema' => [
                    ['name' => 'curp', 'label' => 'CURP', 'type' => 'text', 'required' => true],
                ],
            ],
            [
                'code' => 'CSF-02',
                'name' => 'CSF con RFC y IDCIF',
                'description' => 'LEER INFORMACION SERVICIO ACTIVO Todos los días 24/7 ORDENAR SOLO CON EL RFC Y IDCIF',
                'price' => 55.00,
                'service_type' => 'SAT',
                'processing_time' => '5 Minutos',
                'image_path' => null,
                'form_schema' => [
                    ['name' => 'rfc', 'label' => 'RFC', 'type' => 'text', 'required' => true],
                    ['name' => 'idcif', 'label' => 'IDCIF', 'type' => 'text', 'required' => true],
                ],
            ],
            [
                'code' => 'IDCIF-01',
                'name' => 'LOCALIZAR IDCIF [ORDENAR CON RFC] SOLO ARROJA IDCIF',
                'description' => 'LEER INFORMACION SERVICIO ACTIVO DE LUNES A VIERNES DE 8:00 AM a 8:00 PM (Si ordena fuera de horario, la orden saldra cuando este dentro de horario y no contaran esas hrs en proceso) ORDENAR SOLO CON EL RFC',
                'price' => 95.00,
                'service_type' => 'SAT',
                'processing_time' => '1-6 Horas',
                'image_path' => null,
                'form_schema' => [
                    ['name' => 'rfc', 'label' => 'RFC', 'type' => 'text', 'required' => true],
                ],
            ],
            [
                'code' => 'NSS-01',
                'name' => 'Constancia Vigencia Derechos NSS PDF por CURP',
                'description' => 'LEER INFORMACION SERVICIO ACTIVO Todos los días 24/7 ORDENAR SOLO CON LA CURP LA ORDEN ARROJA LA VIGENCIA DE DERECHSO DEL IMSS',
                'price' => 40.00,
                'service_type' => 'SINDOS IMSS',
                'processing_time' => '5 Minutos',
                'image_path' => null,
                'form_schema' => [
                    ['name' => 'curp', 'label' => 'CURP', 'type' => 'text', 'required' => true],
                ],
            ],
            [
                'code' => 'NSS-02',
                'name' => 'Localizar NSS con CURP',
                'description' => 'LEER INFORMACION SERVICIO ACTIVO Todos los días 24/7 ORDENAR SOLO CON LA CURP ESTA ORDEN SOLO LE ARROJARA EL NSS DE UNA CURP',
                'price' => 20.00,
                'service_type' => 'SINDOS IMSS',
                'processing_time' => '5 Minutos',
                'image_path' => null,
                'form_schema' => [
                    ['name' => 'curp', 'label' => 'CURP', 'type' => 'text', 'required' => true],
                ],
            ],
            [
                'code' => 'NSS-03',
                'name' => 'Semanas Cotizadas por CURP',
                'description' => 'LEER INFORMACION SERVICIO ACTIVO Todos los días 24/7 ORDENAR SOLO CON LA CURP Y EL NSS EJEMPLO DE COMO ORDENAR: MAGG930214HGRRRR03 : 46496449745',
                'price' => 40.00,
                'service_type' => 'SINDOS IMSS',
                'processing_time' => '10 Minutos',
                'image_path' => null,
                'form_schema' => [
                    ['name' => 'input_string', 'label' => 'CURP : NSS (Ej: MAGG93... : 4649...)', 'type' => 'text', 'required' => true],
                ],
            ],
            [
                'code' => 'AFO-01',
                'name' => 'Localizar AFORE (Saber el Banco o Institución)',
                'description' => 'LEER INFORMACION SERVICIO ACTIVO Todos los días 24/7 ORDENAR SOLO CON LA CURP',
                'price' => 20.00,
                'service_type' => 'SERVICIOS',
                'processing_time' => '5 Minutos',
                'image_path' => null,
                'form_schema' => [
                    ['name' => 'curp', 'label' => 'CURP', 'type' => 'text', 'required' => true],
                ],
            ],
            [
                'code' => 'CURP-01',
                'name' => 'CURP Actualizada',
                'description' => 'LEER INFORMACION SERVICIO ACTIVO Todos los días 24/7 ORDENAR SOLO CON LA CURP',
                'price' => 8.00,
                'service_type' => 'SERVICIOS',
                'processing_time' => '5 Minutos',
                'image_path' => null,
                'form_schema' => [
                    ['name' => 'curp', 'label' => 'CURP', 'type' => 'text', 'required' => true],
                ],
            ],
            [
                'code' => 'CFE-01',
                'name' => 'Recibo CFE PDF',
                'description' => 'LEER INFORMACION SERVICIO ACTIVO Todos los días de 8:00 AM a 8:00 PM (Si ordena fuera de horario, el documento se entregará cuando el servicio esté activo Y NO CUENTAN ESAS HRS DE PROCESO) NORMALMENTE DEMORA DE 1 A 10 MINUTOS PERO LUEGO EL SISTEMA DE CFE ENTRA EN MANTENIMIENTO Y PUEDE DEMORAR ORDENAR SOLO CON EL NUMERO DEL SERVICIO',
                'price' => 10.00,
                'service_type' => 'SERVICIOS',
                'processing_time' => '1-10 Minutos',
                'image_path' => null,
                'form_schema' => [
                    ['name' => 'service_number', 'label' => 'Número de Servicio', 'type' => 'text', 'required' => true],
                ],
            ],
            [
                'code' => 'ANP-01',
                'name' => 'Antecedentes no Penales',
                'description' => 'LEER INFORMACION SERVICIO ACTIVO Todos los días 24/7 ORDENAR CON TODOS LOS DATOS SOLICITADOS: *CURP *NOMBRES *APELLIDO PATERNO *APELLIDO MATERNO *DOMICILIO *CLAVE ELECTOR',
                'price' => 70.00,
                'service_type' => 'SERVICIOS',
                'processing_time' => '5 Minutos',
                'image_path' => null,
                'form_schema' => [
                    ['name' => 'curp', 'label' => 'CURP', 'type' => 'text', 'required' => true],
                    ['name' => 'names', 'label' => 'Nombres', 'type' => 'text', 'required' => true],
                    ['name' => 'paternal_surname', 'label' => 'Apellido Paterno', 'type' => 'text', 'required' => true],
                    ['name' => 'maternal_surname', 'label' => 'Apellido Materno', 'type' => 'text', 'required' => true],
                    ['name' => 'address', 'label' => 'Domicilio', 'type' => 'text', 'required' => true],
                    ['name' => 'elector_key', 'label' => 'Clave Elector', 'type' => 'text', 'required' => true],
                ],
            ],
            [
                'code' => 'EDO-INF',
                'name' => 'ESTADO DE CUENTA MENSUAL INFONAVIT',
                'description' => 'SERVICIO ACTIVO Todos los días de 8:00 AM a 8:00 PM (Si ordena fuera de horario, el documento se entregará cuando el servicio esté activo Y NO CUENTAN ESAS HRS DE PROCESO) ORDENAR CON NUMERO DE CREDITO',
                'price' => 100.00,
                'service_type' => 'INFONAVIT',
                'processing_time' => '1-60 Minutos',
                'image_path' => null,
                'form_schema' => [
                    ['name' => 'credit_number', 'label' => 'Número de Crédito', 'type' => 'text', 'required' => true],
                ],
            ],
            [
                'code' => 'REC-INF',
                'name' => 'RECUPERAR CLAVE CUENTA INFONAVIT',
                'description' => 'SERVICIO ACTIVO Para solicitarlo solo se requiere: NSS (Simplemente complete los datos y proceda a pagar sus pedidos utilizando su saldo de crédito)',
                'price' => 100.00,
                'service_type' => 'INFONAVIT',
                'processing_time' => '1-20 Minutos',
                'image_path' => null,
                'form_schema' => [
                    ['name' => 'nss', 'label' => 'NSS', 'type' => 'text', 'required' => true],
                ],
            ],
            [
                'code' => 'REPHIS-INF',
                'name' => 'REPORTE HISTORICO INFONAVIT',
                'description' => 'SERVICIO ACTIVO Todos los días de 8:00 AM a 8:00 PM (Si ordena fuera de horario, el documento se entregará cuando el servicio esté activo Y NO CUENTAN ESAS HRS DE PROCESO) ORDENAR SOLO CON EL NSS',
                'price' => 100.00,
                'service_type' => 'INFONAVIT',
                'processing_time' => '1-30 Minutos',
                'image_path' => null,
                'form_schema' => [
                    ['name' => 'nss', 'label' => 'NSS', 'type' => 'text', 'required' => true],
                ],
            ],
            [
                'code' => 'RESCRED-INF',
                'name' => 'RESUMEN CREDITO INFONAVIT',
                'description' => 'SERVICIO ACTIVO Todos los días de 8:00 AM a 8:00 PM (Si ordena fuera de horario, el documento se entregará cuando el servicio esté activo Y NO CUENTAN ESAS HRS DE PROCESO) ORDENAR SOLO CON NSS O NUEMRO DE CREDITO',
                'price' => 100.00,
                'service_type' => 'INFONAVIT',
                'processing_time' => '1-30 Minutos',
                'image_path' => null,
                'form_schema' => [
                    ['name' => 'identifier', 'label' => 'NSS o Número de Crédito', 'type' => 'text', 'required' => true],
                ],
            ],
            [
                'code' => 'FP-TCDMX',
                'name' => 'FORMATO PAGO DE TENENCIA CD MX',
                'description' => 'Para solicitarlo se Requiere: NÚMERO DE PLACA AÑO A PAGAR',
                'price' => 30.00,
                'service_type' => 'VEHICULOS',
                'processing_time' => '10 Minutos',
                'image_path' => null,
                'form_schema' => [
                    ['name' => 'plate', 'label' => 'Número de Placa', 'type' => 'text', 'required' => true],
                    ['name' => 'year', 'label' => 'Año a Pagar', 'type' => 'text', 'required' => true],
                ],
            ],
            [
                'code' => 'FP-TEDOMX',
                'name' => 'FORMATO PAGO DE TENENCIA EDOMEX',
                'description' => '\'Para solicitarlo se Requiere: NÚMERO DE PLACA',
                'price' => 30.00,
                'service_type' => 'VEHICULOS',
                'processing_time' => '10 Minutos',
                'image_path' => null,
                'form_schema' => [
                    ['name' => 'plate', 'label' => 'Número de Placa', 'type' => 'text', 'required' => true],
                ],
            ],
            [
                'code' => 'HOJ-REP',
                'name' => 'HOJA REPUVE',
                'description' => 'SERVICIO ACTIVO Todos los días de 8:00 AM a 8:00 PM (Si ordena fuera de horario, el documento se entregará cuando el servicio esté activo Y NO CUENTAN ESAS HRS DE PROCESO) ORDENAR SOLO CON LA PLACA',
                'price' => 30.00,
                'service_type' => 'VEHICULOS',
                'processing_time' => '1-20 Minutos',
                'image_path' => null,
                'form_schema' => [
                    ['name' => 'plate', 'label' => 'Placa', 'type' => 'text', 'required' => true],
                ],
            ],
        ];

        foreach ($services as $service) {
            Service::updateOrCreate(
                ['code' => $service['code']],
                $service
            );
        }
    }
}
