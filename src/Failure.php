<?php

namespace oihub\validation;

use oihub\base\BaseObject;

/**
 * Class Failure.
 * 
 * @author sean <maoxfjob@163.com>
 */
class Failure extends BaseObject
{
    /**
     * @var string 键名.
     */
    protected $key;
    /**
     * @var string 原因.
     */
    protected $reason;
    /**
     * @var string 模板.
     */
    protected $template;
    /**
     * @var array 数据.
     */
    protected $params = [];

    /**
     * 构造函数.
     * @param string $key 键名.
     * @param string $reason 原因.
     * @param string $template 模板.
     * @param array $params 数据.
     * @return void
     */
    public function __construct(
        string $key,
        string $reason,
        string $template,
        array $params
    ) {
        $this->key = $key;
        $this->reason = $reason;
        $this->template = $template;
        $this->params = $params;
    }

    /**
     * 格式化.
     * @return string
     */
    public function format(): string
    {
        $replace = function ($matches) {
            if (array_key_exists($matches[1], $this->params)) {
                return $this->params[$matches[1]];
            }
            return $matches[0];
        };
        return preg_replace_callback(
            '~{{\s*([^}\s]+)\s*}}~',
            $replace,
            $this->template
        );
    }

    /**
     * 重写键名.
     * @param string $key 键名.
     * @return void
     */
    public function overwriteKey(string $key): void
    {
        $this->key = $key;
    }

    /**
     * 重写消息模板.
     * @param string $template 模板.
     * @return void
     */
    public function overwriteMessageTemplate(string $template): void
    {
        $this->template = $template;
    }

    /**
     * 得到键名.
     * @return string
     */
    public function getKey(): string
    {
        return $this->key;
    }

    /**
     * 得到原因.
     * @return string
     */
    public function getReason(): string
    {
        return $this->reason;
    }
}
