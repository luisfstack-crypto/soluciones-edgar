<?php

namespace App\Filament\Dashboard\Pages;

use Filament\Pages\Auth\Login as BaseLogin;

class Login extends BaseLogin
{
    public function mount(): void
    {
        parent::mount();
        if (auth()->check()) {
            $this->redirectIntended(default: filament()->getHomeUrl());
        }
    }

    protected function getRedirectUrl(): string
    {
        return auth()->user()->is_admin ? '/admin' : '/app';
    }
}
