<?php

namespace JacobFitzp\LaravelTiptapValidation\Tests\Unit\Facades;

use JacobFitzp\LaravelTiptapValidation\Facades\TiptapValidation;
use JacobFitzp\LaravelTiptapValidation\Rules\TiptapContainsText;
use JacobFitzp\LaravelTiptapValidation\Rules\TiptapContent;
use JacobFitzp\LaravelTiptapValidation\Tests\TestCase;

class TiptapValidationTest extends TestCase
{
    public function test_it_gets_content_rule(): void
    {
        $this->assertInstanceOf(
            TiptapContent::class,
            TiptapValidation::content()
        );
    }

    public function test_it_gets_contains_text_rule(): void
    {
        $this->assertInstanceOf(
            TiptapContainsText::class,
            TiptapValidation::containsText()
        );
    }
}
