<!DOCTYPE html>
<html>
<head>
    <title>Pedido Completado</title>
</head>
<body style="font-family: Arial, sans-serif; color: #333;">
    <div style="max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #ddd; border-radius: 8px;">
        <h2 style="color: #d97706;">¡Tu pedido está listo!</h2>
        <p>Hola <strong>{{ $order->user->name }}</strong>,</p>
        <p>Nos complace informarte que tu pedido del servicio <strong>{{ $order->service->name }}</strong> ha sido completado exitosamente.</p>
        
        <p>Hemos adjuntado el archivo con el resultado de tu trámite a este correo.</p>
        
        @if($order->admin_notes)
            <div style="background-color: #f3f4f6; padding: 10px; border-left: 4px solid #d97706; margin: 15px 0;">
                <strong>Nota del Administrador:</strong>
                <p>{{ $order->admin_notes }}</p>
            </div>
        @endif
        
        <p>Gracias por confiar en <strong>Soluciones Edgar</strong>.</p>
        
        <hr style="border: 0; border-top: 1px solid #eee; margin: 20px 0;">
        <small style="color: #999;">Si tienes alguna duda, responde a este correo o contáctanos por WhatsApp.</small>
    </div>
</body>
</html>
