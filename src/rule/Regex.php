<?php

namespace oihub\validation\rule;

use oihub\validation\Rule;

class Regex extends Rule
{
    const NO_MATCH = 'Regex::NO_MATCH';

    protected $templates = [
        self::NO_MATCH => '{{ name }}格式错误.'
    ];

    protected $regex;

    public function __construct(string $regex)
    {
        $this->regex = $regex;
    }

    public function validate($value): bool
    {
        return $this->match($this->regex, $value, self::NO_MATCH);
    }

    protected function match(string $regex, $value, string $reason)
    {
        $result = preg_match($regex, $value);
        if ($result === 0) {
            return $this->error($reason);
        }
        return true;
    }
}
