<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;

class User extends Authenticatable implements FilamentUser
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'balance',
        'is_admin',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }


    /**
     * Get the orders for the user.
     */
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function credit(float $amount, string $description, $reference = null)
    {
        return \DB::transaction(function () use ($amount, $description, $reference) {
            $this->increment('balance', $amount);

            return $this->transactions()->create([
                'type' => 'deposit',
                'amount' => $amount,
                'description' => $description,
                'reference_type' => $reference ? get_class($reference) : null,
                'reference_id' => $reference ? $reference->id : null,
                'type' => $reference instanceof \App\Models\DepositRequest ? 'deposit' : 'refund', // Simple inference or add param
            ]);
        });
    }
    
    // I should create a more generic method or explicit creditDeposit/creditRefund
    
    public function addBalance(float $amount, string $type, string $description, $reference = null)
    {
         return \DB::transaction(function () use ($amount, $type, $description, $reference) {
            $this->increment('balance', $amount);

            return $this->transactions()->create([
                'type' => $type,
                'amount' => $amount,
                'description' => $description,
                'reference_type' => $reference ? get_class($reference) : null,
                'reference_id' => $reference ? $reference->id : null,
            ]);
        });
    }

    public function subtractBalance(float $amount, string $description, $reference = null)
    {
         return \DB::transaction(function () use ($amount, $description, $reference) {
            if ($this->balance < $amount) {
                throw new \Exception('Saldo insuficiente');
            }
            $this->decrement('balance', $amount);

            return $this->transactions()->create([
                'type' => 'purchase',
                'amount' => -$amount, // Start recording as negative for display? Or keep absolute and use type? 
                // Ledger usually keeps absolute and type determines sign. But `balance` calc might need query. 
                // The `balance` column is the source of truth, transactions are history.
                // storing absolute amount is fine if type is clear.
                // However, seeing -$200 is clearer in a table. Let's store negative for purchases.
                // But the requested 'Interface' just says "Pago de servicio X".
                // I will store the amount passed.
                'description' => $description,
                'reference_type' => $reference ? get_class($reference) : null,
                'reference_id' => $reference ? $reference->id : null,
            ]);
        });
    }

    public function canAccessPanel(Panel $panel): bool
    {
        if ($panel->getId() === 'admin') {
            return $this->is_admin;
        }

        return true;
    }
}
