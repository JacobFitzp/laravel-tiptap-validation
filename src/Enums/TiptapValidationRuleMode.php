<?php

namespace JacobFitzp\LaravelTiptapValidation\Enums;

enum TiptapValidationRuleMode: string
{
    case BLACKLIST = 'blacklist';
    case WHITELIST = 'whitelist';
}
