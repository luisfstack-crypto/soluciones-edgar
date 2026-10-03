<?php

namespace Tests\Unit;

use App\Models\Service;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class ServiceAvailabilityTest extends TestCase
{
    protected function tearDown(): void
    {
        Carbon::setTestNow();

        parent::tearDown();
    }

    public function test_services_without_schedules_are_always_available(): void
    {
        $service = new Service(['has_schedule' => false]);

        $this->assertTrue($service->isAvailableNow());
        $this->assertSame('Disponible ahora', $service->getNextAvailableMessage());
    }

    public function test_service_is_available_inside_its_mexico_city_schedule(): void
    {
        Carbon::setTestNow(Carbon::parse('2026-10-05 10:00:00', 'America/Mexico_City'));
        $service = $this->scheduledService();

        $this->assertTrue($service->isAvailableNow());
    }

    public function test_service_is_unavailable_before_opening_and_reports_next_slot(): void
    {
        Carbon::setTestNow(Carbon::parse('2026-10-05 08:30:00', 'America/Mexico_City'));
        $service = $this->scheduledService();

        $this->assertFalse($service->isAvailableNow());
        $this->assertSame('Disponible el lunes a las 09:00', $service->getNextAvailableMessage());
    }

    public function test_service_is_unavailable_at_closing_and_reports_next_week(): void
    {
        Carbon::setTestNow(Carbon::parse('2026-10-05 17:00:00', 'America/Mexico_City'));
        $service = $this->scheduledService();

        $this->assertFalse($service->isAvailableNow());
        $this->assertSame('Disponible el lunes a las 09:00', $service->getNextAvailableMessage());
    }

    private function scheduledService(): Service
    {
        return new Service([
            'has_schedule' => true,
            'schedule_days' => [1],
            'schedule_start' => '09:00:00',
            'schedule_end' => '17:00:00',
        ]);
    }
}
