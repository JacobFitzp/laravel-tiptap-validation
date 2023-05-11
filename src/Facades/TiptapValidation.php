<?php

namespace JacobFitzp\LaravelTiptapValidation\Facades;

use Illuminate\Support\Facades\Facade;
use JacobFitzp\LaravelTiptapValidation\Rules\TiptapContainsText;
use JacobFitzp\LaravelTiptapValidation\Rules\TiptapContent;

class TiptapValidation extends Facade
{
    public static function content(): TiptapContent
    {
        return new TiptapContent();
    }

    public static function containsText(): TiptapContainsText
    {
        return new TiptapContainsText();
    }
}
