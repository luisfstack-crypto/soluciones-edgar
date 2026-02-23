<!DOCTYPE html>
<html>
<head>
    <title>Restablecer Contraseña - Soluciones Edgar</title>
    <style>
        body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; background-color: #f1f5f9; color: #334155; margin: 0; padding: 0; }
        .container { max-width: 600px; margin: 40px auto; background-color: #ffffff; border-radius: 12px; box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05); overflow: hidden; }
        .header { background: linear-gradient(135deg, #0ea5e9, #0284c7); padding: 40px 20px; text-align: center; }
        .content { padding: 40px; }
        h1 { color: #0f172a; font-size: 26px; font-weight: 800; margin-top: 0; margin-bottom: 24px; text-align: center; }
        p { line-height: 1.7; margin-bottom: 24px; font-size: 16px; color: #475569; }
        .button { display: inline-block; background-color: #0ea5e9 !important; color: #ffffff !important; padding: 14px 28px; border-radius: 8px; text-decoration: none; font-weight: 700; margin-top: 10px; box-shadow: 0 4px 6px -1px rgba(14, 165, 233, 0.4); transition: background-color 0.2s; }
        .footer { background-color: #f8fafc; padding: 30px 20px; text-align: center; font-size: 13px; color: #94a3b8; border-top: 1px solid #e2e8f0; }
        .small { font-size: 13px; color: #94a3b8; margin-top: 30px; word-break: break-all; text-align: center; border-top: 1px solid #e2e8f0; padding-top: 20px; }
        .highlight { color: #0ea5e9; font-weight: 700; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            @if(isset($message))
                <img src="{{ $message->embed(public_path('images/logo.png')) }}" alt="Soluciones Edgar" style="height: 50px; width: auto;">
            @else
                <img src="{{ asset('images/logo.png') }}" alt="Soluciones Edgar" style="height: 50px; width: auto;">
            @endif
        </div>
        <div class="content">
            <h1>Restablecer Contraseña</h1>
            <p>Hola <strong>{{ $notifiable->name }}</strong>,</p>
            <p>Recibiste este correo electrónico porque nos solicitaste restablecer la contraseña de tu cuenta en <span class="highlight">Soluciones Edgar</span>.</p>
            
            <div style="text-align: center;">
                <a href="{{ $url }}" class="button">Restablecer Contraseña</a>
            </div>

            <p style="margin-top: 24px;">Este enlace para restablecer la contraseña caducará en 60 minutos.</p>

            <p class="small">Si el botón anterior no funciona, copia y pega el siguiente enlace en tu navegador web:<br>
            <a href="{{ $url }}" style="color: #0ea5e9; text-decoration: none;">{{ $url }}</a></p>
        </div>
        <div class="footer">
            <p>Si no solicitaste un restablecimiento de contraseña, puedes ignorar este mensaje de forma segura.</p>
            <p>&copy; {{ date('Y') }} Soluciones Edgar. Tecnología Digital a tu Alcance.</p>
        </div>
    </div>
</body>
</html>

