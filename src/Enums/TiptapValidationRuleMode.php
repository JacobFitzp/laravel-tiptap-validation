<?php

namespace JacobFitzp\LaravelTiptapValidation\Enums;

/**
 * Validation rule mode.
 *
 * @author Jacob Fitzpatrick <jacob@codefy.co.uk>
 */
enum TiptapValidationRuleMode: string
{
    case BLACKLIST = 'blacklist';
    case WHITELIST = 'whitelist';
}
