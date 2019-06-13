<?php

namespace oihub\validation\rule;

use oihub\validation\Rule;

class LengthBetween extends Between
{
    const TOO_LONG = 'LengthBetween::TOO_LONG';
    const TOO_SHORT = 'LengthBetween::TOO_SHORT';

    protected $templates = [
        self::TOO_LONG => '{{ name }}长度必须小于{{ max }}.',
        self::TOO_SHORT => '{{ name }}长度必须大于{{ min }}.'
    ];

    protected $max;
    protected $min;

    public function __construct(int $min, int $max)
    {
        $this->min = $min;
        $this->max = $max;
    }

    public function validate($value): bool
    {
        $length = strlen($value);

        return !$this->tooSmall($length, self::TOO_SHORT) &&
            !$this->tooLarge($length, self::TOO_LONG);
    }

    protected function tooLarge($value, string $error): bool
    {
        if ($this->max !== null) {
            return parent::tooLarge($value, $error);
        }
        return false;
    }

    protected function getParams(): array
    {
        return array_merge(parent::getParams(), [
            'min' => $this->min,
            'max' => $this->max
        ]);
    }
}
