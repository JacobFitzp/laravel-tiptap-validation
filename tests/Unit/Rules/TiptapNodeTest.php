<?php

namespace JacobFitzp\LaravelTiptapValidation\Tests\Unit\Rules;

use Illuminate\Support\Facades\Validator;
use JacobFitzp\LaravelTiptapValidation\Rules\TiptapNode;
use JacobFitzp\LaravelTiptapValidation\Tests\TestCase;

class TiptapNodeTest extends TestCase
{
    /**
     * @dataProvider validatesNodesTestCases
     */
    public function test_it_validates_tiptap_node(array $node, bool $passes): void
    {
        $validator = Validator::make([
            'node' => $node,
        ], [
            'node' => new TiptapNode(),
        ]);

        $this->assertSame($passes, $validator->passes());
    }

    public static function validatesNodesTestCases(): \Generator
    {
        yield [
            [],
            false,
        ];

        yield [
            [
                'type' => 'test',
                'text' => 'blah',
            ],
            true,
        ];

        yield [
            [
                'type' => null,
                'text' => [],
            ],
            false,
        ];

        yield [
            [
                'type' => 'test',
                'marks' => 'test',
            ],
            false,
        ];
    }
}
