<?php

namespace Dissect\Parser;

/**
 * Represents a rule in a context-free grammar.
 *
 * @author Jakub Lédl <jakubledl@gmail.com>
 */
class Rule
{
    protected int $number;

    protected string $name;

    /**
     * @var string[]
     */
    protected array $components;

    /**
     * @var callable
     */
    protected $callback = null;

    protected ?int $precedence = null;

    /**
     * Constructor.
     *
     * @param int $number The number of the rule in the grammar.
     * @param string $name The name (lhs) of the rule ("A" in "A -> a b c")
     * @param string[] $components The components of this rule.
     */
    public function __construct(int $number, string $name, array $components)
    {
        $this->number = $number;
        $this->name = $name;
        $this->components = $components;
    }

    /**
     * Returns the number of this rule.
     *
     * @return int The number of this rule.
     */
    public function getNumber(): int
    {
        return $this->number;
    }

    /**
     * Returns the name of this rule.
     *
     * @return string The name of this rule.
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Returns the components of this rule.
     *
     * @return string[] The components of this rule.
     */
    public function getComponents(): array
    {
        return $this->components;
    }

    /**
     * Returns a component at index $index or null
     * if index is out of range.
     *
     * @param int $index The index.
     *
     * @return ?string The component at index $index.
     */
    public function getComponent(int $index): ?string
    {
        if (!isset($this->components[$index])) {
            return null;
        }

        return $this->components[$index];
    }

    /**
     * Sets the callback (the semantic value) of the rule.
     *
     * @param callable $callback The callback.
     */
    public function setCallback(callable $callback): void
    {
        $this->callback = $callback;
    }

    public function getCallback(): ?callable
    {
        return $this->callback;
    }

    public function getPrecedence(): ?int
    {
        return $this->precedence;
    }

    public function setPrecedence($i): void
    {
        $this->precedence = $i;
    }
}
