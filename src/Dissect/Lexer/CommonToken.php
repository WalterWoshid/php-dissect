<?php

namespace Dissect\Lexer;

/**
 * A simple token representation.
 *
 * @author Jakub Lédl <jakubledl@gmail.com>
 */
class CommonToken implements Token
{
    /**
     * Constructor.
     *
     * @param mixed $type The type of the token.
     * @param string $value The token value.
     * @param int $line The line.
     */
    public function __construct(
        protected mixed $type,
        protected string $value,
        protected int $line
    ) {}

    /**
     * {@inheritDoc}
     */
    public function getType(): mixed
    {
        return $this->type;
    }

    /**
     * {@inheritDoc}
     */
    public function getValue(): string
    {
        return $this->value;
    }

    /**
     * {@inheritDoc}
     */
    public function getLine(): int
    {
        return $this->line;
    }
}
