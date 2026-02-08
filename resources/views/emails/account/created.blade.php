<!DOCTYPE html>
<html>
<head>
    <title>Bienvenido a Soluciones Edgar</title>
    <style>
        body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; background-color: #f9fafb; color: #374151; margin: 0; padding: 0; }
        .container { max-width: 600px; margin: 40px auto; background-color: #ffffff; border-radius: 12px; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06); overflow: hidden; }
        .header { background-color: #0ea5e9; padding: 30px; text-align: center; }
        .header img { max-height: 60px; }
        .content { padding: 40px; }
        h1 { color: #111827; font-size: 24px; font-weight: 700; margin-top: 0; margin-bottom: 20px; }
        p { line-height: 1.6; margin-bottom: 20px; font-size: 16px; }
        .button { display: inline-block; background-color: #0ea5e9; color: #ffffff; padding: 12px 24px; border-radius: 6px; text-decoration: none; font-weight: 600; margin-top: 20px; }
        .footer { background-color: #f3f4f6; padding: 20px; text-align: center; font-size: 12px; color: #6b7280; }
        .highlight { color: #0284c7; font-weight: 600; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1 style="color: #ffffff; margin: 0;">Soluciones Edgar</h1>
        </div>
        <div class="content">
            <h1>¡Bienvenido, {{ $user->name }}!</h1>
            <p>Estamos emocionados de que te hayas unido a <span class="highlight">Soluciones Edgar</span>. Tu cuenta ha sido creada exitosamente.</p>
            <p>Ahora puedes acceder a nuestra plataforma para solicitar trámites, gestionar tus servicios y mucho más.</p>
            <p>Si tienes alguna pregunta, nuestro equipo de soporte está listo para ayudarte.</p>
            
            <div style="text-align: center;">
                <a href="{{ url('/app/login') }}" class="button">Ir a mi Cuenta</a>
            </div>
        </div>
        <div class="footer">
            <p>&copy; {{ date('Y') }} Soluciones Edgar. Todos los derechos reservados.</p>
            <p>Este es un correo automático, por favor no respondas directamente.</p>
        </div>
    </div>
</body>
</html>
