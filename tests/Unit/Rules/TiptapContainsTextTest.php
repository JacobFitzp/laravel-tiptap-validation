<?php

namespace JacobFitzp\LaravelTiptapValidation\Tests\Unit\Rules;

use Illuminate\Support\Facades\Validator;
use JacobFitzp\LaravelTiptapValidation\Rules\TiptapContainsText;
use JacobFitzp\LaravelTiptapValidation\Tests\TestCase;

class TiptapContainsTextTest extends TestCase
{
    /**
     * @dataProvider contentContainsTextTestCases
     */
    public function test_it_validates_content_contains_text(mixed $content, bool $passes): void
    {
        $validator = Validator::make([
            'content' => $content,
        ], [
            'content' => TiptapContainsText::create(),
        ]);

        $this->assertSame($passes, $validator->passes());
    }

    public function test_it_validates_minimum_character_length(): void
    {
        $validator = Validator::make([
            'content' => [
                'type' => 'document',
                'content' => [
                    [
                        'text' => 'test',
                    ],
                ],
            ],
        ], [
            'content' => TiptapContainsText::create()
                ->minimum(5),
        ]);

        $this->assertTrue($validator->fails());
        $this->assertSame('Must contain more than 5 characters', $validator->messages()->get('content')[0]);
    }

    public function test_it_validates_maximum_character_length(): void
    {
        $validator = Validator::make([
            'content' => [
                'type' => 'document',
                'content' => [
                    [
                        'text' => 'testing 1234 blah blah',
                    ],
                ],
            ],
        ], [
            'content' => TiptapContainsText::create()
                ->maximum(8),
        ]);

        $this->assertTrue($validator->fails());
        $this->assertSame('Must contain less than 8 characters', $validator->messages()->get('content')[0]);
    }

    public function test_it_validates_character_length_between(): void
    {
        $validator = Validator::make([
            'content' => [
                'type' => 'document',
                'content' => [
                    [
                        'text' => 'testing 1234 blah blah',
                    ],
                ],
            ],
        ], [
            'content' => TiptapContainsText::create()
                ->between(2, 8),
        ]);

        $this->assertTrue($validator->fails());
        $this->assertSame('Must contain between 2 and 8 characters', $validator->messages()->get('content')[0]);
    }

    public static function contentContainsTextTestCases(): \Generator
    {
        yield [false, false];
        yield [null, false];
        yield [[], false];

        yield [
            [
                'type' => 'document',
                'content' => [
                    [
                        'text' => '',
                    ],
                ],
            ],
            false,
        ];

        yield [
            [
                'type' => 'document',
                'content' => [
                    [
                        'text' => 'testing',
                    ],
                ],
            ],
            true,
        ];
    }
}
