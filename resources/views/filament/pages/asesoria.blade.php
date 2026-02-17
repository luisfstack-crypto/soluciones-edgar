<x-filament::page>

<style>
    :root {
        --card-bg: #ffffff;
        --text-primary: #111827;
        --text-secondary: #6b7280;
        --accent: #25D366;
        --border-color: #e5e7eb;
    }

    .dark {
        --card-bg: #111827;
        --text-primary: #f9fafb;
        --text-secondary: #9ca3af;
        --border-color: #1f2937;
    }

    .asesoria-wrapper {
        min-height: 60vh;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .asesoria-card {
        width: 100%;
        max-width: 520px;
        background: var(--card-bg);
        border: 1px solid var(--border-color);
        border-radius: 16px;
        padding: 3rem 2.5rem;
        text-align: center;
        box-shadow: 0 10px 30px rgba(0,0,0,0.08);
        transition: all .3s ease;
    }

    .asesoria-card h2 {
        font-size: 1.75rem;
        font-weight: 700;
        color: var(--text-primary);
        margin-bottom: 1rem;
    }

    .asesoria-card p {
        color: var(--text-secondary);
        font-size: 0.95rem;
        line-height: 1.6;
        margin-bottom: 2rem;
    }

    .asesoria-btn {
        display: inline-flex;
        align-items: center;
        gap: .6rem;
        padding: .9rem 1.6rem;
        background: var(--accent);
        color: #ffffff;
        font-weight: 600;
        border-radius: 10px;
        text-decoration: none;
        transition: all .2s ease;
    }

    .asesoria-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 18px rgba(37,211,102,0.3);
    }

    .asesoria-note {
        margin-top: 1.5rem;
        font-size: 0.8rem;
        color: var(--text-secondary);
    }
</style>

<div class="asesoria-wrapper">
    <div class="asesoria-card">

        <h2>¿Necesitas ayuda?</h2>

        <p>
            Hola <strong>{{ auth()->user()->name }}</strong>,
            puedes contactar a nuestro soporte vía WhatsApp para recibir asesoría.
        </p>

        <a href="https://wa.me/5219990000000"
           target="_blank"
           class="asesoria-btn">
            Iniciar conversación
        </a>

        <div class="asesoria-note">
            Se abrirá WhatsApp Web o la app móvil en una nueva pestaña.
        </div>

    </div>
</div>

</x-filament::page>
