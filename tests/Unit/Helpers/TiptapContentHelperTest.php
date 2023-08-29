<?php

namespace JacobFitzp\LaravelTiptapValidation\Tests\Unit\Helpers;

use JacobFitzp\LaravelTiptapValidation\Helpers\TiptapContentHelper;
use JacobFitzp\LaravelTiptapValidation\Tests\TestCase;

class TiptapContentHelperTest extends TestCase
{
    /**
     * @dataProvider decodesContentTestCases
     */
    public function test_it_decodes_content(mixed $value, ?array $expected): void
    {
        $this->assertSame($expected, TiptapContentHelper::decode($value));
    }

    public static function decodesContentTestCases(): \Generator
    {
        yield [null, null];
        yield [false, null];
        yield [26, [26]];
        yield ['test', null];
        yield [[], []];
        yield [['type' => 'document'], ['type' => 'document']];
        yield ['{"type": "document"}', ['type' => 'document']];
    }
}
