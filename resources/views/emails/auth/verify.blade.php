<!DOCTYPE html>
<html>
<head>
    <title>Verifica tu Correo - Soluciones Edgar</title>
    <style>
        body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; background-color: #f1f5f9; color: #334155; margin: 0; padding: 0; }
        .container { max-width: 600px; margin: 40px auto; background-color: #ffffff; border-radius: 12px; box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05); overflow: hidden; }
        .header { background: linear-gradient(135deg, #0ea5e9, #0284c7); padding: 40px 20px; text-align: center; }
        .content { padding: 40px; }
        h1 { color: #0f172a; font-size: 26px; font-weight: 800; margin-top: 0; margin-bottom: 24px; text-align: center; }
        p { line-height: 1.7; margin-bottom: 24px; font-size: 16px; color: #475569; }
        .button { display: inline-block; background-color: #0ea5e9; color: #ffffff !important; padding: 14px 28px; border-radius: 8px; text-decoration: none; font-weight: 700; margin-top: 10px; box-shadow: 0 4px 6px -1px rgba(14, 165, 233, 0.4); transition: background-color 0.2s; }
        .button:hover { background-color: #0284c7; }
        .footer { background-color: #f8fafc; padding: 30px 20px; text-align: center; font-size: 13px; color: #94a3b8; border-top: 1px solid #e2e8f0; }
        .small { font-size: 13px; color: #94a3b8; margin-top: 30px; word-break: break-all; text-align: center; border-top: 1px solid #e2e8f0; padding-top: 20px; }
        .logo-text { color: #ffffff; font-size: 28px; font-weight: 900; letter-spacing: -0.5px; text-transform: uppercase; margin: 0; text-shadow: 0 2px 4px rgba(0,0,0,0.1); }
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
            <h1>Confirmación de Cuenta</h1>
            <p>Hola <strong>{{ $user->name }}</strong>,</p>
            <p>Gracias por registrarte en Soluciones Edgar. Estamos emocionados de tenerte con nosotros.</p>
            <p>Para garantizar la seguridad de tu cuenta y acceder a todos nuestros servicios digitales, por favor verifica tu dirección de correo electrónico haciendo clic en el siguiente enlace:</p>
            
            <div style="text-align: center;">
                <a href="{{ $url }}" class="button">Verificar Mi Correo</a>
            </div>

            <p class="small">Si el botón anterior no funciona, copia y pega el siguiente enlace en tu navegador web:<br>
            <a href="{{ $url }}" style="color: #0ea5e9; text-decoration: none;">{{ $url }}</a></p>
        </div>
        <div class="footer">
            <p>Si no creaste esta cuenta, puedes ignorar este mensaje de forma segura.</p>
            <p>&copy; {{ date('Y') }} Soluciones Edgar. Todos los derechos reservados.</p>
        </div>
    </div>
</body>
</html>
