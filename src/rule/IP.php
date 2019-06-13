<?php

namespace oihub\validation\rule;

use oihub\validation\Rule;

class IP extends Rule
{
    const INVALID_IP = 'IP::INVALID_IP';

    protected $templates = [
        self::INVALID_IP => '{{ name }}无效的IP格式.',
    ];

    public function validate($value): bool
    {
        if (filter_var($value, FILTER_VALIDATE_IP) !== false) {
            return true;
        }
        return $this->error(self::INVALID_IP);
    }
}
