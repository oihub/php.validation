<?php

namespace oihub\validation;

use oihub\base\BaseObject;

/**
 * Class Result.
 * 
 * @author sean <maoxfjob@163.com>
 */
class Result extends BaseObject
{
    /**
     * @var bool 验证结果.
     */
    protected $isValid;
    /**
     * @var array 失败集合.
     */
    protected $failures;
    /**
     * @var array 数据集合.
     */
    protected $values;
    /**
     * @var array 信息集合.
     */
    protected $messages;

    /**
     * 构造函数.
     * @param bool $isValid 验证结果.
     * @param array $failures 失败集合.
     * @param array $values 数据集合.
     * @return void
     */
    public function __construct(
        bool $isValid,
        array $failures,
        array $values
    ) {
        $this->isValid = $isValid;
        $this->failures = $failures;
        $this->values = $values;
    }

    /**
     * 得到验证结果.
     * @return bool
     */
    public function isValid(): bool
    {
        return $this->isValid;
    }

    /**
     * 得到失败集合.
     * @return array
     */
    public function getFailures(): array
    {
        return $this->failures;
    }

    /**
     * 得到数据集合.
     * @return array
     */
    public function getValues(): array
    {
        return $this->values;
    }

    /**
     * 得到信息集合.
     * @return array
     */
    public function getMessages(): array
    {
        if ($this->messages === null) {
            $this->messages = [];
            foreach ($this->failures as $failure) {
                $this->messages[$failure->getKey()][$failure->getReason()] =
                    $failure->format();
            }
        }
        return $this->messages;
    }

    /**
     * 错误信息.
     * @return string
     */
    public function getError(): string
    {
        $messages = $this->getMessages();
        return array_values(array_shift($messages))[0];
    }
}
