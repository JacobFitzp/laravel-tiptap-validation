<?php

namespace JacobFitzp\LaravelTiptapValidation\Tests\Unit\Rules;

use Generator;
use Illuminate\Support\Facades\Validator;
use JacobFitzp\LaravelTiptapValidation\Rules\TiptapContent;
use JacobFitzp\LaravelTiptapValidation\Tests\TestCase;

class TiptapContentTest extends TestCase
{
    /**
     * @dataProvider validationTestCases
     */
    public function test_it_validates_content(mixed $content, bool $passes): void
    {
        $validator = Validator::make([
            'content' => $content,
        ], [
            'content' => TiptapContent::create(),
        ]);

        $this->assertSame($passes, $validator->passes());

        if (! $passes) {
            $this->assertSame(
                'Invalid Tiptap content',
                $validator->messages()->get('content')[0]
            );
        }
    }

    /**
     * @dataProvider blacklistValidationTestCases
     */
    public function test_it_can_blacklist_nodes_and_marks(mixed $content, array $nodes, array $marks, bool $passes): void
    {
        $validator = Validator::make([
            'content' => $content,
        ], [
            'content' => TiptapContent::create()
                ->blacklist()
                ->nodes(...$nodes)
                ->marks(...$marks),
        ]);

        $this->assertSame($passes, $validator->passes());
    }

    /**
     * @dataProvider whitelistValidationTestCases
     */
    public function test_it_can_whitelist_nodes_and_marks(mixed $content, array $nodes, array $marks, bool $passes): void
    {
        $validator = Validator::make([
            'content' => $content,
        ], [
            'content' => TiptapContent::create()
                ->whitelist()
                ->nodes(...$nodes)
                ->marks(...$marks),
        ]);

        $this->assertSame($passes, $validator->passes());
    }

    public static function validationTestCases(): Generator
    {
        // Empty values
        yield 'Empty array' => [[], true];
        yield 'Empty string' => ['', true];
        yield 'Null' => [null, true];

        // Invalid types
        yield 'String' => ['This is some invalid Tiptap content', false];
        yield 'Integer' => [56, false];
        yield 'Float' => [2.529, false];
        yield 'Boolean' => [true, false];

        // JSON
        yield 'Valid JSON' => [
            '{"type": "document", "content": [{"type": "text", "text": "Testing"}]}',
            true,
        ];

        yield 'Invalid JSON' => [
            '{"foo": "bar"}',
            false,
        ];

        yield [
            [
                'type' => 'document',
                'content' => [],
            ],
            true,
        ];

        yield [
            [
                'type' => 'document',
                'content' => [
                    [
                        'type' => 'paragraph',
                        'text' => 'blah blah',
                    ],
                ],
            ],
            true,
        ];

        yield [
            ['foo' => 'bar'],
            false,
        ];
    }

    public static function blacklistValidationTestCases(): Generator
    {
        yield [
            [
                'type' => 'document',
                'content' => [
                    [
                        'type' => 'ordered_list',
                        'content' => [
                            [
                                'type' => 'list_item',
                                'content' => [
                                    [
                                        'type' => 'paragraph',
                                        'content' => [],
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
            ['list_item'],
            [],
            false,
        ];

        yield [
            [
                'type' => 'document',
                'content' => [
                    [
                        'type' => 'paragraph',
                        'text' => 'testing',
                    ],
                ],
            ],
            ['text'],
            [],
            true,
        ];

        yield [
            [
                'type' => 'document',
                'content' => [
                    [
                        'type' => 'paragraph',
                        'marks' => [
                            [
                                'type' => 'bold',
                                'text' => 'testing',
                            ],
                        ],
                    ],
                ],
            ],
            ['text'],
            ['bold'],
            false,
        ];

        yield [
            [
                'type' => 'document',
                'content' => [
                    [
                        'type' => 'paragraph',
                        'marks' => [
                            [
                                'type' => 'italic',
                                'text' => 'testing',
                            ],
                        ],
                    ],
                ],
            ],
            ['text'],
            ['bold'],
            true,
        ];
    }

    public static function whitelistValidationTestCases(): Generator
    {
        yield [
            [
                'type' => 'document',
                'content' => [
                    [
                        'type' => 'paragraph',
                        'text' => 'testing',
                    ],
                ],
            ],
            [],
            [],
            false,
        ];

        yield [
            [
                'type' => 'document',
                'content' => [
                    [
                        'type' => 'paragraph',
                        'text' => 'testing',
                    ],
                ],
            ],
            ['paragraph'],
            [],
            true,
        ];

        yield [
            [
                'type' => 'document',
                'content' => [
                    [
                        'type' => 'paragraph',
                        'marks' => [
                            [
                                'type' => 'italic',
                                'text' => 'testing',
                            ],
                        ],
                    ],
                ],
            ],
            ['paragraph'],
            ['bold'],
            false,
        ];

        yield [
            [
                'type' => 'document',
                'content' => [
                    [
                        'type' => 'paragraph',
                        'marks' => [
                            [
                                'type' => 'bold',
                                'text' => 'testing',
                            ],
                        ],
                    ],
                ],
            ],
            ['paragraph'],
            ['bold'],
            true,
        ];
    }
}
