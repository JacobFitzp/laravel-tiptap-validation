<?php

namespace JacobFitzp\LaravelTiptapValidation\Rules;

use Closure;
use Exception;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use JacobFitzp\LaravelTiptapValidation\Concerns\Configurable;
use JacobFitzp\LaravelTiptapValidation\Enums\TiptapValidationRuleMode;
use RuntimeException;

/**
 * Tiptap content validation rule
 *
 * @author Jacob Fitzpatrick <contact@jacobfitzp.me>
 */
class TiptapValidationRule implements ValidationRule
{
    use Configurable;

    /**
     * Validation failed.
     */
    protected bool $fails = false;

    /**
     * Validate Tiptap content.
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // Skip empty values
        if (empty($value)) {
            return;
        }

        // Attempt to decode json from string
        if (! is_array($value)) {
            try {
                $value = json_decode($value, true, 512, JSON_THROW_ON_ERROR);
                // Decoding was unsuccessful
                if (empty($value) || ! is_array($value)) {
                    throw new RuntimeException();
                }
            } catch (Exception $exception) {
                $fail('Invalid Tiptap content');

                return;
            }
        }

        // Validate root tiptap content.
        $validator = Validator::make($value, [
            'type' => 'required|string',
            'content' => 'array',
        ]);

        if ($validator->fails()) {
            $fail('Invalid Tiptap content');

            return;
        }

        // Begin content validation
        if (! empty($value['content'])) {
            $this->validateNodes($value['content']);
        }

        // Content validation failed
        if ($this->fails) {
            $fail('Invalid Tiptap content');
        }
    }

    /**
     * Recursively validate nodes.
     */
    protected function validateNodes(array $nodes): bool
    {
        // Validate node types
        $validator = Validator::make($nodes, [
            '*.type' => ['required', 'string', $this->typeValidationRule($this->nodes)],
            '*.marks' => 'array',
            '*.marks.*.type' => ['required', 'string',  $this->typeValidationRule($this->marks)],
            '*.marks.*.text' => 'string',
            '*.content' => 'array',
            '*.text' => 'string',
        ]);

        if ($validator->fails()) {
            return $this->fail();
        }

        // Validate nested content
        collect($nodes)
            ->each(function (array $node) {
                if (! empty($node['content'])) {
                    $this->validateNodes($node['content']);
                }
            });

        return true;
    }

    /**
     * Type validation rule
     */
    protected function typeValidationRule(array $types): mixed
    {
        return match ($this->mode) {
            TiptapValidationRuleMode::WHITELIST => Rule::in($types),
            default => Rule::notIn($types),
        };
    }

    /**
     * Fail validation
     *
     * @return false
     */
    protected function fail(): bool
    {
        $this->fails = true;

        return false;
    }

    /**
     * Create rule instance.
     */
    public static function create(): TiptapValidationRule
    {
        return new self();
    }
}
