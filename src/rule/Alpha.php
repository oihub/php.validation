<?php

namespace oihub\validation\rule;

class Alpha extends Regex
{
    const NOT_ALPHA = 'Alpha::NOT_ALPHA';
    const ALLOW_SPACES = true;
    const DISALLOW_SPACES = false;

    protected $templates = [
        self::NOT_ALPHA => '{{ name }}必须是纯字母.'
    ];

    public function __construct(bool $allowWhitespace = self::DISALLOW_SPACES)
    {
        parent::__construct($allowWhitespace ?
            '~^[\p{L}\s]*$~iu' : '~^[\p{L}]*$~ui');
    }

    public function validate($value): bool
    {
        return $this->match($this->regex, $value, self::NOT_ALPHA);
    }
}
