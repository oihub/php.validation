<?php

namespace oihub\validation\rule;

use oihub\validation\Rule;

class Datetime extends Rule
{
    const INVALID_DATETIME = 'DateTime::INVALID_DATETIME';

    protected $templates = [
        self::INVALID_DATETIME => '{{ name }}无效的时间格式.',
    ];

    protected $format;

    public function __construct(string $format = null)
    {
        $this->format = $format;
    }

    public function validate($value): bool
    {
        if (!($this->datetime($value, $this->format) instanceof \DateTime)) {
            return $this->error(self::INVALID_DATETIME);
        }
        return true;
    }

    protected function datetime($value, string $format = null)
    {
        if ($format !== null) {
            $dateTime = date_create_from_format($format, $value);
            if ($dateTime instanceof DateTime) {
                return $this->checkDate($dateTime, $format, $value);
            }
            return false;
        }
        return @date_create($value);
    }

    protected function checkDate(DateTime $dateTime, string $format, $value)
    {
        $equal = (string)$dateTime->format($format) === (string)$value;
        if ($dateTime->getLastErrors()['warning_count'] === 0 && $equal) {
            return $dateTime;
        }
        return false;
    }
}
