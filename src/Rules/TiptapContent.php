<?php

namespace JacobFitzp\LaravelTiptapValidation\Rules;

use Closure;
use Exception;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use JacobFitzp\LaravelTiptapValidation\Concerns\Creatable;
use JacobFitzp\LaravelTiptapValidation\Enums\TiptapValidationRuleMode;
use RuntimeException;

/**
 * Tiptap content validation rule
 *
 * @author Jacob Fitzpatrick <contact@jacobfitzp.me>
 */
class TiptapContent implements ValidationRule
{
    use Creatable;

    /**
     * Validation failed.
     */
    protected bool $fails = false;

    /**
     * List of allowed / disallowed node types.
     */
    protected array $nodes = [];

    /**
     * List of allowed / disallowed mark types.
     *
     * @var string[]
     */
    protected array $marks = [];

    /**
     * Mode used for validation, blacklist, or whitelist.
     */
    protected TiptapValidationRuleMode $mode = TiptapValidationRuleMode::BLACKLIST;

    /**
     * Nodes to blacklist / whitelist
     *
     * @param  mixed  ...$nodes
     */
    public function nodes(...$nodes): TiptapContent
    {
        $this->nodes = $nodes;

        return $this;
    }

    /**
     * Marks to blacklist / whitelist
     *
     * @param  mixed  ...$marks
     */
    public function marks(...$marks): TiptapContent
    {
        $this->marks = $marks;

        return $this;
    }

    /**
     * Enable whitelisting mode
     */
    public function whitelist(): TiptapContent
    {
        $this->mode = TiptapValidationRuleMode::WHITELIST;

        return $this;
    }

    /**
     * Enable blacklisting mode
     */
    public function blacklist(): TiptapContent
    {
        $this->mode = TiptapValidationRuleMode::BLACKLIST;

        return $this;
    }

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
                $fail(trans('tiptap-validation::messages.tiptapContent'));

                return;
            }
        }

        // Validate root tiptap content.
        $validator = Validator::make($value, [
            'type' => 'required|string',
            'content' => 'array',
        ]);

        if ($validator->fails()) {
            $fail(trans('tiptap-validation::messages.tiptapContent'));

            return;
        }

        // Begin content validation
        if (! empty($value['content'])) {
            $this->validateNodes($value['content']);
        }

        // Content validation failed
        if ($this->fails) {
            $fail(trans('tiptap-validation::messages.tiptapContent'));
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
            '*.marks.*.type' => ['required', 'string',  $this->typeValidationRule($this->marks)],
            '*' => new TiptapNode(),
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
}
