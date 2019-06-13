<?php

namespace oihub\validation\rule;

use oihub\validation\Rule;

class Url extends Rule
{
    const INVALID_URL = 'Url::INVALID_URL';
    const INVALID_SCHEME = 'Url::INVALID_SCHEME';

    protected $templates = [
        self::INVALID_URL => '{{ name }}无效的URL格式.',
        self::INVALID_SCHEME => '{{ name }}必须包含在:{{ schemes }}.',
    ];

    protected $schemes = [];

    public function __construct(array $schemes = [])
    {
        $this->schemes = $schemes;
    }

    public function validate($value): bool
    {
        $url = filter_var($value, FILTER_VALIDATE_URL);
        if ($url !== false) {
            return $this->validateScheme($value);
        }
        return $this->error(self::INVALID_URL);
    }

    protected function validateScheme($value)
    {
        if (
            count($this->schemes) > 0 &&
            !in_array(parse_url($value, PHP_URL_SCHEME), $this->schemes)
        ) {
            return $this->error(self::INVALID_SCHEME);
        }
        return true;
    }

    protected function getParams(): array
    {
        return array_merge(parent::getParams(), [
            'schemes' => implode(', ', $this->schemes)
        ]);
    }
}
