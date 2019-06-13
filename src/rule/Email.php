<?php

namespace oihub\validation\rule;

use oihub\validation\Rule;

class Email extends Rule
{
    const INVALID_EMAIL = 'Email::INVALID_EMAIL';

    protected $templates = [
        self::INVALID_EMAIL => '{{ name }}格式错误.',
    ];

    public function validate($value): bool
    {
        if (filter_var($value, FILTER_VALIDATE_EMAIL) !== false) {
            return true;
        }
        return $this->error(self::INVALID_EMAIL);
    }
}
