<?php

namespace JacobFitzp\LaravelTiptapValidation\Helpers;

class TiptapTextHelper
{
    protected static array $textProperties = ['text'];

    public static function countCharacters(array $nodes): int
    {
        $characters = 0;

        foreach ($nodes as $node) {
            foreach (self::$textProperties as $textProperty) {
                $characters += strlen($node[$textProperty] ?? '');
            }

            foreach ($node['marks'] ?? [] as $mark) {
                foreach (self::$textProperties as $textProperty) {
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
