<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SupportRequest; // We might need to create this model
use Illuminate\Support\Facades\Log;

class SupportRedirectController extends Controller
{
    public function __invoke()
    {
        // Log the request
        // You requested to "save" or "register" that they requested help.
        // We can create a simple record or just log it. 
        // User asked: "does it register that I requested help? if not, I want you to save it."
        
            if (auth()->check()) {
                \App\Models\SupportRequest::create([
                    'user_id' => auth()->id(),
                    'type' => 'whatsapp'
                ]);
            }

            // Redirect to WhatsApp
            return redirect()->away('https://web.whatsapp.com/send?phone=529991730976');
    }
}
