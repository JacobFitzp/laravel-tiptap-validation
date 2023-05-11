<?php

namespace JacobFitzp\LaravelTiptapValidation\Tests\Unit\Enums;

use JacobFitzp\LaravelTiptapValidation\Enums\TiptapValidationRuleMode;
use JacobFitzp\LaravelTiptapValidation\Tests\TestCase;

class TiptapValidationRuleModeTest extends TestCase
{
    public function test_it_has_correct_modes(): void
    {
        $this->assertSame([
            TiptapValidationRuleMode::BLACKLIST,
            TiptapValidationRuleMode::WHITELIST,
        ], TiptapValidationRuleMode::cases());

        $this->assertSame('blacklist', TiptapValidationRuleMode::BLACKLIST->value);
        $this->assertSame('whitelist', TiptapValidationRuleMode::WHITELIST->value);
    }
}
