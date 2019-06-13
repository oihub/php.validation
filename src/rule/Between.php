<?php

namespace oihub\validation\rule;

use oihub\validation\Rule;

class Between extends Rule
{
    const TOO_BIG = 'Between::TOO_BIG';
    const TOO_SMALL = 'Between::TOO_SMALL';

    protected $templates = [
        self::TOO_BIG => '{{ name }}必须小于等于{{ max }}.',
        self::TOO_SMALL => '{{ name }}必须大于等于{{ min }}.',
    ];

    protected $min;
    protected $max;

    public function __construct(int $min, int $max)
    {
        $this->min = $min;
        $this->max = $max;
    }

    public function validate($value): bool
    {
        return !$this->tooSmall($value, self::TOO_SMALL) &&
            !$this->tooLarge($value, self::TOO_BIG);
    }

    protected function tooSmall($value, string $error): bool
    {
        if ($value < $this->min) {
            $this->error($error);
            return true;
        }
        return false;
    }

    protected function tooLarge($value, string $error): bool
    {
        if ($value > $this->max) {
            $this->error($error);
            return true;
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
