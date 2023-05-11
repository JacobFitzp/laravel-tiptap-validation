<?php

namespace JacobFitzp\LaravelTiptapValidation\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Validator;

/**
 * Validate Tiptap node content.
 *
 * @author Jacob Fitzpatrick <contact@jacobfitzp.me>
 */
class TiptapNode implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
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
