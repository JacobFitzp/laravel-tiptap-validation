<?php

namespace JacobFitzp\LaravelTiptapValidation\Tests\Unit\Helpers;

use JacobFitzp\LaravelTiptapValidation\Helpers\TiptapTextHelper;
use JacobFitzp\LaravelTiptapValidation\Tests\TestCase;

class TiptapTextHelperTest extends TestCase
{
    /**
     * @dataProvider characterCountTestCases
     */
    public function test_it_counts_characters(array $nodes, int $length): void
    {
        $this->assertSame($length, TiptapTextHelper::countCharacters([$nodes]));
    }

    public static function characterCountTestCases(): \Generator
    {
        yield 'testing' => [
            [
                'text' => 'testing',
            ],
            7,
        ];

        yield 'blank' => [
            [],
            0,
        ];

        yield 'testing + hobnob' => [
            [
                'text' => 'testing',
                'marks' => [
                    [
                        'text' => 'hobnob',
                    ],
                ],
            ],
            13,
        ];

        yield 'ignores whitespace' => [
            [
                'text' => 'testing   ',
                'marks' => [
                    [
                        'text' => '    ho bno b',
                    ],
                ],
            ],
            13,
        ];
    }
}
