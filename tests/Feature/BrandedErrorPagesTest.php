<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\URL;
use Tests\TestCase;

class BrandedErrorPagesTest extends TestCase
{
    public function test_unknown_route_renders_the_branded_404_page(): void
    {
        $this->get('/ruta-que-no-existe')
            ->assertNotFound()
            ->assertSee('No encontramos lo que buscas')
            ->assertSee('Tecnología Digital a tu Alcance')
            ->assertSee('images/favicon-32x32.png');
    }

    public function test_tampered_document_signature_renders_the_branded_403_page(): void
    {
        $signedUrl = URL::temporarySignedRoute(
            'orders.download',
            now()->addMinutes(10),
            ['order' => 1],
        );
        $parts = parse_url($signedUrl);
        parse_str($parts['query'], $query);
        $query['signature'][0] = $query['signature'][0] === 'a' ? 'b' : 'a';
        $tamperedUrl = $parts['path'] . '?' . http_build_query($query);

        $this->get($tamperedUrl)
            ->assertForbidden()
            ->assertSee('No tienes permiso para ver esto')
            ->assertSee('Pide un enlace nuevo por WhatsApp.');
    }
}
