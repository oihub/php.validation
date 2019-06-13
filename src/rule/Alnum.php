<?php

namespace oihub\validation\rule;

class Alnum extends Regex
{
    const NOT_ALNUM = 'Alnum::NOT_ALNUM';
    const ALLOW_SPACES = true;
    const DISALLOW_SPACES = false;

    protected $templates = [
        self::NOT_ALNUM => '{{ name }}只能包含字母和数字.'
    ];

    public function __construct(bool $allowSpaces = self::DISALLOW_SPACES)
    {
        parent::__construct($allowSpaces ?
            '~^[\p{L}0-9\s]*$~iu' : '~^[\p{L}0-9]*$~iu');
    }

    public function validate($value): bool
    {
        return $this->match($this->regex, $value, self::NOT_ALNUM);
    }
}
