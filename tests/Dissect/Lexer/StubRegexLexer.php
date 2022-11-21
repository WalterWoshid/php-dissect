<?php

namespace Dissect\Lexer;

use RuntimeException;

class StubRegexLexer extends RegexLexer
{
    protected array $operators = ['+', '-'];

    protected function getCatchablePatterns(): array
    {
        return ['[1-9][0-9]*'];
    }

    protected function getNonCatchablePatterns(): array
    {
        return ['\s+'];
    }

    protected function getType(string &$value): string
    {
        if (is_numeric($value)) {
            $value = (int)$value;

            return 'INT';
        } elseif (in_array($value, $this->operators)) {
            return $value;
        } else {
            throw new RuntimeException(sprintf('Invalid token "%s"', $value));
        }
    }
}
