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
        yield [
            [
                'text' => 'testing',
            ],
            7,
        ];

        yield [
            [
                'text' => 'testing',
                'content' => [
                    [
                        'text' => 'hobnob',
                    ],
                ],
            ],
            13,
        ];

        yield [
            [],
            0,
        ];

        yield [
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
    }
}
