<?php

namespace Dissect\Lexer;

use Dissect\Lexer\Exception\RecognitionException;
use Dissect\Lexer\TokenStream\TokenStream;

/**
 * A lexer takes an input string and processes
 * it into a token stream.
 *
 * @author Jakub Lédl <jakubledl@gmail.com>
 */
interface Lexer
{
    /**
     * Lexes the given string, returning a token stream.
     *
     * @param string $string The string to lex.
     *
     * @throws RecognitionException When unable to extract more tokens from the string.
     *
     * @return TokenStream The resulting token stream.
     */
    public function lex(string $string): TokenStream;
}
