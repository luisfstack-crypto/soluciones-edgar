<?php

namespace Tests\Unit;

use App\Services\DocMxService;
use PHPUnit\Framework\Attributes\RunInSeparateProcess;
use Tests\TestCase;

class DocMxServiceTest extends TestCase
{
    #[RunInSeparateProcess]
    public function test_localizar_nss_requires_curp_and_builds_only_the_curp_payload(): void
    {
        $docMx = new DocMxService();
        $schema = [
            ['name' => 'curp', 'required' => false],
            ['name' => 'nss', 'required' => false],
        ];
        $input = [
            'CURP' => 'abcd010101hynaaa01',
            'nss' => '12345678901',
        ];

        $this->assertSame(['curp'], $docMx->requiredFields('localizar-nss', $schema));
        $this->assertSame([], $docMx->missingFields('localizar-nss', $schema, $input));
        $this->assertSame(
            ['curp' => 'ABCD010101HYNAAA01'],
            $docMx->buildFormData('localizar-nss', $input)
        );
    }

    #[RunInSeparateProcess]
    public function test_localizar_nss_reports_missing_curp_even_if_schema_marks_it_optional(): void
    {
        $docMx = new DocMxService();

        $this->assertSame(
            ['curp'],
            $docMx->missingFields(
                'localizar-nss',
                [['name' => 'nss', 'required' => false]],
                ['nss' => '12345678901']
            )
        );
    }
}
