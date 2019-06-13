<?php

namespace oihub\validation\rule;

use oihub\validation\Rule;

class IsBool extends Rule
{
    const NOT_BOOL = 'BOOL::NOT_BOOL';

    protected $templates = [
        self::NOT_BOOL => '{{ name }}必须是布尔值.',
    ];

    public function validate($value): bool
    {
        if (filter_var($value, FILTER_VALIDATE_BOOLEAN) !== false) {
            return true;
        }
        return $this->error(self::NOT_BOOL);
    }
}
