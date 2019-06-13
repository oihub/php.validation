<?php

namespace oihub\validation\rule;

use oihub\validation\Rule;

class Json extends Rule
{
    const INVALID_JSON = 'Json::INVALID_JSON';

    protected $templates = [
        self::INVALID_JSON => '{{ name }}无效的JSON格式.',
    ];

    public function validate($value): bool
    {
        if (!is_string($value)) {
            return $this->error(self::INVALID_JSON);
        }
        json_decode($value);
        if (json_last_error() !== JSON_ERROR_NONE) {
            return $this->error(self::INVALID_JSON);
        }
        return true;
    }
}
