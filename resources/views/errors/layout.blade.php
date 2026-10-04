<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="color-scheme" content="light dark">
    <title>@yield('title', 'Soluciones Edgar') | Soluciones Edgar</title>
    <link rel="icon" href="{{ asset('images/favicon.ico') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('images/favicon-16x16.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('images/favicon-32x32.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('images/apple-touch-icon.png') }}">
    <style>
        :root {
            color-scheme: light;
            font-family: ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
            color: #1B2A41;
            background: #f4f7fb;
        }
        * { box-sizing: border-box; }
        body {
            min-height: 100vh;
            margin: 0;
            display: grid;
            place-items: center;
            padding: 32px 20px;
            background: linear-gradient(145deg, #f4f7fb 0%, #e9f1fb 100%);
        }
        main {
            width: min(100%, 560px);
            text-align: center;
        }
        .brand {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-height: 72px;
            margin-bottom: 30px;
        }
        .brand img { display: block; width: auto; max-width: min(260px, 72vw); height: 64px; object-fit: contain; }
        .error-code {
            margin: 0;
            color: #1B2A41;
            font-size: clamp(76px, 18vw, 132px);
            font-weight: 800;
            line-height: 0.95;
        }
        h1 { margin: 22px 0 10px; font-size: clamp(24px, 6vw, 32px); line-height: 1.2; }
        .message { margin: 0 auto; max-width: 450px; color: #526176; font-size: 16px; line-height: 1.65; }
        .document-help { margin-top: 12px; }
        .actions { margin-top: 28px; }
        .button {
            display: inline-flex;
            min-height: 48px;
            align-items: center;
            justify-content: center;
            padding: 0 22px;
            border-radius: 8px;
            background: #1E90FF;
            color: white;
            font-size: 15px;
            font-weight: 700;
            text-decoration: none;
            transition: background-color 150ms ease, transform 150ms ease;
        }
        .button:hover { background: #0877df; transform: translateY(-1px); }
        .button:focus-visible { outline: 3px solid #1B2A41; outline-offset: 3px; }
        .tagline { margin: 36px 0 0; color: #708096; font-size: 13px; font-weight: 600; }
        @media (prefers-color-scheme: dark) {
            :root { color-scheme: dark; color: #e8eef7; background: #111a27; }
            body { background: linear-gradient(145deg, #111a27 0%, #1B2A41 100%); }
            .error-code { color: #f1f6fd; }
            .message { color: #b8c5d7; }
            .tagline { color: #a9b8cc; }
            .button:focus-visible { outline-color: #f1f6fd; }
        }
        @media (prefers-reduced-motion: reduce) {
            *, *::before, *::after { transition-duration: 0.01ms !important; }
        }
    </style>
</head>
<body>
    <main>
        <a class="brand" href="{{ url('/') }}" aria-label="Soluciones Edgar, inicio">
            <img src="{{ asset('images/logo.png') }}" alt="Soluciones Edgar">
        </a>
        <p class="error-code" aria-label="Error @yield('code')">@yield('code')</p>
        <h1>@yield('heading')</h1>
        <p class="message">@yield('message')</p>
        @yield('extra')
        <div class="actions">
            <a class="button" href="@yield('button-url', url('/'))">@yield('button-label', 'Volver al inicio')</a>
        </div>
        <p class="tagline">Tecnología Digital a tu Alcance</p>
    </main>
</body>
</html>
