<!DOCTYPE html>
<html>
<head>
    <title>Pedido Completado - Soluciones Edgar</title>
    <style>
        body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; background-color: #f1f5f9; color: #334155; margin: 0; padding: 0; }
        .container { max-width: 600px; margin: 40px auto; background-color: #ffffff; border-radius: 12px; box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05); overflow: hidden; }
        .header { background: linear-gradient(135deg, #4f46e5, #4338ca); padding: 40px 20px; text-align: center; }
        .content { padding: 40px; }
        h1 { color: #1e1b4b; font-size: 26px; font-weight: 800; margin-top: 0; margin-bottom: 24px; text-align: center; }
        p { line-height: 1.7; margin-bottom: 24px; font-size: 16px; color: #475569; }
        .admin-note { background-color: #eef2ff; border-left: 5px solid #4f46e5; padding: 20px; margin: 30px 0; border-radius: 6px; }
        .footer { background-color: #f8fafc; padding: 30px 20px; text-align: center; font-size: 13px; color: #94a3b8; border-top: 1px solid #e2e8f0; }
        .highlight { color: #4f46e5; font-weight: 700; }
        .button { display: inline-block; background-color: #4f46e5; color: #ffffff !important; padding: 14px 28px; border-radius: 8px; text-decoration: none; font-weight: 700; margin-top: 10px; box-shadow: 0 4px 6px -1px rgba(79, 70, 229, 0.4); transition: background-color 0.2s; }
        .button:hover { background-color: #4338ca; }
        .logo-text { color: #ffffff; font-size: 28px; font-weight: 900; letter-spacing: -0.5px; text-transform: uppercase; margin: 0; text-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        .status-badge { display: inline-block; padding: 6px 12px; background-color: #dcfce7; color: #166534; border-radius: 9999px; font-size: 14px; font-weight: 600; margin-bottom: 20px; }
    </style>
</head>
<body>
        <div class="header">
             <img src="{{ $message->embed(public_path('images/logo.png')) }}" alt="Soluciones Edgar" style="height: 50px; width: auto;">
        </div>
        <div class="content">
            <div style="text-align: center;">
                <span class="status-badge">✓ Trámite Finalizado</span>
            </div>
            <h1>¡Tu resultado está listo!</h1>
            <p>Hola <strong>{{ $order->user->name }}</strong>,</p>
            <p>Nos complace informarte que tu solicitud de <span class="highlight">{{ $order->service->name }}</span> ha sido procesada exitosamente por nuestro equipo.</p>
            
            <p><strong>¿Qué sigue?</strong><br>
            Adjunto a este correo encontrarás un archivo <span class="highlight">PDF</span> con el resultado oficial de tu trámite. Por favor descárgalo y consérvalo.</p>

            @if($order->admin_notes)
                <div class="admin-note">
                    <strong style="color: #4338ca; display: block; margin-bottom: 8px;">Nota del Administrador:</strong>
                    <span style="color: #374151;">{{ $order->admin_notes }}</span>
                </div>
            @endif
            
            <p>También puedes consultar el historial y descargar copias adicionales desde tu panel:</p>
            <div style="text-align: center;">
                <a href="{{ url('/app') }}" class="button">Ir a Mis Trámites</a>
            </div>
        </div>
        <div class="footer">
            <p>Gracias por confiar en nuestros servicios digitales.</p>
            <p>&copy; {{ date('Y') }} Soluciones Edgar. Tecnología Digital a tu Alcance.</p>
        </div>
    </div>
</body>
</html>