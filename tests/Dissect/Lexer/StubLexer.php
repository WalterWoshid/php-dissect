<?php

namespace Dissect\Lexer;

class StubLexer extends AbstractLexer
{
    protected function extractToken(string $string): ?Token
    {
        if (strlen(utf8_decode($string)) === 0) {
            return null;
        }

        $char = $string[0];

        if ($char === 'd') { // unrecognizable token
            return null;
        }

        $token = new CommonToken($char, $char, $this->getCurrentLine());

        return $token;
    }

    protected function shouldSkipToken(Token $token): bool
    {
        return $token->getType() === 'e';
    }
}
