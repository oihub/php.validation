<?php

namespace oihub\validation\rule;

use oihub\validation\Rule;

class Length extends Rule
{
    const TOO_SHORT = 'Length::TOO_SHORT';
    const TOO_LONG = 'Length::TOO_LONG';

    protected $templates = [
        self::TOO_SHORT => '{{ name }}长度太短，必须等于{{ length }}.',
        self::TOO_LONG => '{{ name }}长度太长，必须等于{{ length }}.',
    ];

    protected $length;

    public function __construct(int $length)
    {
        $this->length = $length;
    }

    public function validate($value): bool
    {
        $actualLength = strlen($value);
        if ($actualLength > $this->length) {
            return $this->error(self::TOO_LONG);
        }
        if ($actualLength < $this->length) {
            return $this->error(self::TOO_SHORT);
        }
        return true;
    }

    protected function getParams(): array
    {
        return array_merge(parent::getParams(), [
            'length' => $this->length
        ]);
    }
}
