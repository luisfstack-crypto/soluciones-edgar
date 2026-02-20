<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Category;
use App\Models\Service;

return new class extends Migration
{
    public function up(): void
    {
        $map = [
            'ACTAS' => ['name' => 'Actas', 'description' => 'Servicios de actas civiles'],
            'SAT' => ['name' => 'SAT', 'description' => 'Trámites de Hacienda y Crédito Público (SAT)'],
            'SINDOS IMSS' => ['name' => 'IMSS', 'description' => 'Servicios relacionados al IMSS'],
            'SERVICIOS' => ['name' => 'Servicios Generales', 'description' => 'Diversos servicios y consultas'],
            'INFONAVIT' => ['name' => 'Infonavit', 'description' => 'Trámites relacionados con Infonavit'],
            'VEHICULOS' => ['name' => 'Vehículos', 'description' => 'Trámites vehiculares'],
        ];

        $categories = [];

        foreach ($map as $legacy_type => $data) {
            $category = Category::firstOrCreate(
                ['name' => $data['name']],
                ['description' => $data['description']]
            );
            $categories[$legacy_type] = $category->id;
        }

        $services = Service::all();
        
        foreach ($services as $service) {
            if ($service->service_type && isset($categories[$service->service_type])) {
                $service->category_id = $categories[$service->service_type];
                $service->save();
            } else {
                if (!isset($categories['SERVICIOS'])) {
                    $category = Category::firstOrCreate(['name' => 'Servicios Generales']);
                    $categories['SERVICIOS'] = $category->id;
                }
                $service->category_id = $categories['SERVICIOS'];
                $service->save();
            }
        }
    }

    public function down(): void
    {
    }
};
