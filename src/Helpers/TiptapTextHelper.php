<?php

namespace JacobFitzp\LaravelTiptapValidation\Helpers;

class TiptapTextHelper
{
    public static function countCharacters(array $nodes): int
    {
        $characters = 0;
        $textProperties = config('tiptap-validation.textProperties');

        foreach ($nodes as $node) {
            foreach ($textProperties as $textProperty) {
                $characters += strlen($node[$textProperty] ?? '');
            }

            foreach ($node['marks'] ?? [] as $mark) {
                foreach ($textProperties as $textProperty) {
                    $characters += strlen($mark[$textProperty] ?? '');
                }
            }

            // Nested content
            if (! empty($node['content'])) {
                $characters += self::countCharacters($node['content']);
            }
        }

        return $characters;
    }
}
