<?php

namespace Dissect\Lexer;

/**
 * A common contract for tokens.
 *
 * @author Jakub Lédl <jakubledl@gmail.com>
 */
interface Token
{
    /**
     * Returns the token type.
     *
     * @return mixed The token type.
     */
    public function getType(): mixed;

    /**
     * Returns the token value.
     *
     * @return string The token value.
     */
    public function getValue(): string;

    /**
     * Returns the line on which the token was found.
     *
     * @return int The line.
     */
    public function getLine(): int;
}
