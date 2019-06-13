<?php

namespace oihub\validation\rule;

use oihub\exception\Exception;

class UUID extends Regex
{
    const INVALID_UUID = 'UUID::INVALID_UUID';
    const UUID_VALID = 0b0000100;
    const UUID_NIL = 0b0000001;
    const UUID_V1 = 0b0000010;
    const UUID_V2 = 0b0001000;
    const UUID_V3 = 0b0010000;
    const UUID_V4 = 0b0100000;
    const UUID_V5 = 0b1000000;

    protected $regexes = [
        self::UUID_VALID =>
        '~^[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}$~i',
        self::UUID_NIL =>
        '~^[0]{8}-[0]{4}-[0]{4}-[0]{4}-[0]{12}$~i',
        self::UUID_V1 =>
        '~^[0-9a-f]{8}-[0-9a-f]{4}-1[0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}$~i',
        self::UUID_V2 =>
        '~^[0-9a-f]{8}-[0-9a-f]{4}-2[0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}$~i',
        self::UUID_V3 =>
        '~^[0-9a-f]{8}-[0-9a-f]{4}-3[0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}$~i',
        self::UUID_V4 =>
        '~^[0-9a-f]{8}-[0-9a-f]{4}-4[0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}$~i',
        self::UUID_V5 =>
        '~^[0-9a-f]{8}-[0-9a-f]{4}-5[0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}$~i',
    ];

    protected $versionNames = [
        self::UUID_VALID => 'valid format',
        self::UUID_NIL => 'NIL',
        self::UUID_V1 => 'v1',
        self::UUID_V2 => 'v2',
        self::UUID_V3 => 'v3',
        self::UUID_V4 => 'v4',
        self::UUID_V5 => 'v5',
    ];

    protected $templates = [
        self::INVALID_UUID => '{{ name }}无效的UUID格式 ({{ version }}).'
    ];

    protected $version;

    public function __construct(int $version = self::UUID_VALID)
    {
        if ($version >= (self::UUID_V5 * 2) || $version < 0) {
            Exception::error('Invalid UUID version mask given.');
        }
        $this->version = $version;
    }

    public function validate($value): bool
    {
        foreach ($this->regexes as $version => $regex) {
            if (
                ($version & $this->version) === $version &&
                preg_match($regex, $value) > 0
            ) {
                return true;
            }
        }
        return $this->error(self::INVALID_UUID);
    }

    protected function getParams(): array
    {
        $versions = [];
        foreach (array_keys($this->regexes) as $version) {
            if (($version & $this->version) === $version) {
                $versions[] = $this->versionNames[$version];
            }
        }
        return array_merge(parent::getParams(), [
            'version' => implode(', ', $versions)
        ]);
    }
}
