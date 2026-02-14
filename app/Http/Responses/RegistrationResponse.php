<?php

namespace App\Http\Responses;

use Filament\Http\Responses\Auth\Contracts\RegistrationResponse as RegistrationResponseContract;
use Illuminate\Http\RedirectResponse;

class RegistrationResponse implements RegistrationResponseContract
{
    public function toResponse($request): RedirectResponse
    {
        return redirect()->to('/app');
    }
}
