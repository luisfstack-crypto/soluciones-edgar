<!DOCTYPE html>
<html>
<head>
    <title>Pedido Completado - Soluciones Edgar</title>
    <style>
        body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; background-color: #0f172a; color: #cbd5e1; margin: 0; padding: 0; }
        .container { max-width: 600px; margin: 40px auto; background-color: #1e293b; border-radius: 12px; border: 1px solid #334155; overflow: hidden; }
        .header { background-color: #000000; padding: 30px; text-align: center; border-bottom: 2px solid #4f46e5; }
        .content { padding: 40px; }
        h1 { color: #f8fafc; font-size: 24px; font-weight: 700; margin-top: 0; margin-bottom: 20px; }
        p { line-height: 1.6; margin-bottom: 20px; font-size: 16px; color: #94a3b8; }
        .admin-note { background-color: #1e1b4b; border-left: 4px solid #4f46e5; padding: 15px; margin: 20px 0; border-radius: 4px; }
        .footer { background-color: #0f172a; padding: 20px; text-align: center; font-size: 12px; color: #64748b; border-top: 1px solid #334155; }
        .highlight { color: #818cf8; font-weight: 600; }
        .button { display: inline-block; background-color: #4f46e5; color: #ffffff; padding: 12px 24px; border-radius: 6px; text-decoration: none; font-weight: 700; margin-top: 20px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1 style="color: #ffffff; margin: 0; text-transform: uppercase; letter-spacing: 2px;">Soluciones Edgar</h1>
        </div>
        <div class="content">
            <h1>¡Tu resultado está listo!</h1>
            <p>Hola <strong>{{ $order->user->name }}</strong>,</p>
            <p>Tu solicitud de <span class="highlight">{{ $order->service->name }}</span> ha sido procesada con éxito.</p>
            
            <p>Adjunto a este correo encontrarás el archivo <span class="highlight">PDF</span> con el resultado oficial de tu trámite.</p>

            @if($order->admin_notes)
                <div class="admin-note">
                    <strong style="color: #818cf8;">Nota del Administrador:</strong>
                    <p style="margin: 5px 0 0; color: #e2e8f0;">{{ $order->admin_notes }}</p>
                </div>
            @endif
            
            <p>Puedes consultar los detalles y descargar copias adicionales desde tu portal personal:</p>
            <div style="text-align: center;">
                <a href="{{ url('/app') }}" class="button">IR A MI PANEL (SKY)</a>
            </div>
        </div>
        <div class="footer">
            <p>Este es un mensaje automático generado por el sistema administrativo (Indigo).</p>
            <p>&copy; {{ date('Y') }} Soluciones Edgar. Tecnología Digital a tu Alcance.</p>
        </div>
    </div>
</body>
</html>