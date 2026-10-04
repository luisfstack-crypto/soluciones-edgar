<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

class Service extends Model
{
    protected $fillable = [
        'category_id',
        'code',
        'name', 
        'description', 
        'price',
        'suggested_price',
        'cost',
        'service_type',
        'schedule_notice',
        'processing_time',
        'image_path',
        'is_active',
        'active_schedule',
        'form_schema',
        'has_schedule',
        'schedule_days',
        'schedule_start',
        'schedule_end',
        'is_maintenance',
        'maintenance_message',
    ];

    protected $casts = [
        'form_schema' => 'array',
        'is_active' => 'boolean',
        'price' => 'decimal:2',
        'suggested_price' => 'decimal:2',
        'has_schedule' => 'boolean',
        'schedule_days' => 'array',
        'is_maintenance' => 'boolean',
    ];

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    private function isWithinScheduleNow(): bool
    {
        if (! $this->has_schedule) {
            return true;
        }

        if (empty($this->schedule_days) || ! $this->schedule_start || ! $this->schedule_end) {
            return false;
        }

        $now = Carbon::now('America/Mexico_City');
        $currentTime = $now->format('H:i:s');

        return in_array($now->dayOfWeekIso, array_map('intval', $this->schedule_days), true)
            && $currentTime >= $this->schedule_start
            && $currentTime < $this->schedule_end;
    }

    public function isAvailable(): bool
    {
        return ! $this->is_maintenance && $this->isWithinScheduleNow();
    }

    public function isAvailableNow(): bool
    {
        return $this->isAvailable();
    }

    public function unavailableReason(): ?string
    {
        if ($this->is_maintenance) {
            return $this->maintenance_message ?: 'Este servicio no está disponible por el momento';
        }

        if (! $this->isWithinScheduleNow()) {
            return $this->getNextAvailableMessage();
        }

        return null;
    }

    public function getNextAvailableMessage(): string
    {
        if (! $this->has_schedule) {
            return 'Disponible ahora';
        }

        if (empty($this->schedule_days) || ! $this->schedule_start) {
            return 'Horario no configurado';
        }

        $timezone = 'America/Mexico_City';
        $now = Carbon::now($timezone);
        try {
            $scheduleStart = Carbon::parse($this->schedule_start, $timezone);
        } catch (\Throwable) {
            return 'Horario no configurado';
        }

        $scheduleDays = array_map('intval', $this->schedule_days);

        for ($daysAhead = 0; $daysAhead <= 7; $daysAhead++) {
            $candidate = $now->copy()->startOfDay()->addDays($daysAhead);

            if (! in_array($candidate->dayOfWeekIso, $scheduleDays, true)) {
                continue;
            }

            $candidate->setTimeFrom($scheduleStart);

            if ($candidate->greaterThan($now)) {
                $dayNames = [
                    1 => 'lunes',
                    2 => 'martes',
                    3 => 'miércoles',
                    4 => 'jueves',
                    5 => 'viernes',
                    6 => 'sábado',
                    7 => 'domingo',
                ];

                return sprintf(
                    'Disponible el %s a las %s',
                    $dayNames[$candidate->dayOfWeekIso],
                    $candidate->format('H:i'),
                );
            }
        }

        return 'Horario no configurado';
    }

    public function category(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Category::class);
    }
}
