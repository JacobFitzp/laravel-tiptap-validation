<?php

namespace JacobFitzp\LaravelTiptapValidation\Facades;

use Illuminate\Support\Facades\Facade;
use JacobFitzp\LaravelTiptapValidation\Rules\TiptapContainsText;
use JacobFitzp\LaravelTiptapValidation\Rules\TiptapContent;

/**
 * TipTap validation facade.
 *
 * @author Jacob Fitzpatrick <jacob@codefy.co.uk>
 */
class TiptapValidation extends Facade
{
    /**
     * Tiptap content validation rule.
     */
    public static function content(): TiptapContent
    {
        return TiptapContent::make();
    }

    /**
     * Tiptap contains text validation rule.
     */
    public static function containsText(): TiptapContainsText
    {
        return TiptapContainsText::make();
    }
}
