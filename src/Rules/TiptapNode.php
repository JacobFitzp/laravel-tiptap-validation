<?php

namespace JacobFitzp\LaravelTiptapValidation\Rules;

use Closure;
use Illuminate\Support\Facades\Validator;
use JacobFitzp\LaravelTiptapValidation\Concerns\Creatable;
use JacobFitzp\LaravelTiptapValidation\Concerns\DecodesTiptapContent;
use JacobFitzp\LaravelTiptapValidation\Contracts\TiptapRule;

/**
 * Tiptap node validation rule.
 *
 * Validates that a tiptap node is in the correct format.
 *
 * @author Jacob Fitzpatrick <contact@jacobfitzp.me>
 */
class TiptapNode implements TiptapRule
{
    use Creatable;
    use DecodesTiptapContent;

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $value = $this->decode($value);

        $validator = Validator::make($value, [
            'type' => 'required|string',
            'text' => 'string',
            'marks' => 'array',
            'marks.*.type' => 'string',
            'marks.*.text' => 'string',
            'content' => 'array',
        ]);

        if ($validator->fails()) {
            $fail(trans('messages.tiptapNode'));
        }
    }
}
