<?php

namespace oihub\validation;

use oihub\base\BaseObject;
use oihub\helper\ArrayHelper;

/**
 * Class Chain.
 * 
 * @author sean <maoxfjob@163.com>
 */
class Chain extends BaseObject
{
    /**
     * @var string 键名.
     */
    protected $key;
    /**
     * @var string 别名.
     */
    protected $name;
    /**
     * @var array 规则集合.
     */
    protected $rules = [];

    /**
     * 构造函数.
     * @param string $key 键名.
     * @param string $name 别名.
     * @param bool $required 是否必填.
     * @param bool $allowEmpty 是否允许为空.
     * @return void
     */
    public function __construct(
        string $key,
        string $name,
        bool $required,
        bool $allowEmpty
    ) {
        $this->key = $key;
        $this->name = $name;
        $this->addRule(new rule\Required($required));
        $this->addRule(new rule\NotEmpty($allowEmpty));
    }

    /**
     * 重写__clone.
     * @return void
     */
    public function __clone()
    {
        foreach ($this->rules as $rule) {
            $rules[] = clone $rule;
        }
        $this->rules = $rules ?? [];
    }

    /**
     * 验证.
     * @param Stack $stack 信息栈.
     * @param array $input 输入.
     * @param array $output 输出.
     * @return bool
     */
    public function validate(
        Stack $stack,
        array $input,
        array $output
    ): bool {
        $valid = true;
        foreach ($this->rules as $rule) {
            $rule->setStack($stack);
            $rule->setParams($this->key, $this->name);
            $valid = $rule->isValid($this->key, $input) && $valid;
            if (((!$valid) && $rule->breakOnError()) || $rule->break()) {
                break;
            }
        }
        $value = ArrayHelper::get($input, $this->key);
        if ($valid && $value) {
            ArrayHelper::set($output, $this->key, $value);
        }
        return $valid;
    }

    /**
     * 验证该值为空.
     * @param callable|bool $allowEmpty 是否为空.
     * @return self
     */
    public function allowEmpty($allowEmpty): self
    {
        $this->getRule(1)->setAllowEmpty($allowEmpty);
        return $this;
    }

    /**
     * 验证该值仅由字母数字字符组成.
     * @param bool $allowWhitespace 是否允许空格.
     * @return self
     */
    public function alnum(
        bool $allowWhitespace = rule\Alnum::DISALLOW_SPACES
    ): self {
        return $this->addRule(new rule\Alnum($allowWhitespace));
    }

    /**
     * 验证该值仅由字母字符组成.
     * @param bool $allowWhitespace 是否允许空格.
     * @return self
     */
    public function alpha(
        bool $allowWhitespace = rule\Alpha::DISALLOW_SPACES
    ): self {
        return $this->addRule(new rule\Alpha($allowWhitespace));
    }

    /**
     * 验证该值在此之间.
     * @param int $min 最小值.
     * @param int $max 最大值.
     * @return self
     */
    public function between(int $min, int $max): self
    {
        return $this->addRule(new rule\Between($min, $max));
    }

    /**
     * 验证该值为布尔.
     * @return self
     */
    public function bool(): self
    {
        return $this->addRule(new rule\IsBool());
    }

    /**
     * 通过执行匿名函数进行验证.
     * @param callable $callable 匿名函数.
     * @return self
     */
    public function callback(callable $callable): self
    {
        return $this->addRule(new rule\Callback($callable));
    }

    /**
     * 验证该值为日期，如果格式化了，必须是哪种格式.
     * @param string $format 格式化.
     * @return self
     */
    public function datetime(string $format = null): self
    {
        return $this->addRule(new rule\Datetime($format));
    }

    /**
     * 验证该值是十进制数字.
     * @return self
     */
    public function digits(): self
    {
        return $this->addRule(new rule\Digits());
    }

    /**
     * 验证嵌套数组的值，然后可以使用新的验证器实例验证该值.
     * @param callable $callback 匿名函数.
     * @return self
     */
    public function each(callable $callback): self
    {
        return $this->addRule(new rule\Each($callback));
    }

    /**
     * 验证该值是电子邮件地址.
     * @return self
     */
    public function email(): self
    {
        return $this->addRule(new rule\Email());
    }

    /**
     * 验证该值等于.
     * @param string $value 值.
     * @return self
     */
    public function equals(string $value): self
    {
        return $this->addRule(new rule\Equal($value));
    }

    /**
     * 验证该值为浮点数.
     * @return self
     */
    public function float(): self
    {
        return $this->addRule(new rule\IsFloat());
    }

    /**
     * 验证该值大于.
     * @param int $value 值.
     * @return self
     */
    public function greaterThan(int $value): self
    {
        return $this->addRule(new rule\GreaterThan($value));
    }

    /**
     * 验证身份证号码.
     * @return self
     */
    public function idCard(): self
    {
        return $this->addRule(new rule\IDCode());
    }

    /**
     * 验证该值在数组中.
     * @param array $array 数组.
     * @param bool $strict 是否严格.
     * @return self
     */
    public function inArray(
        array $array,
        bool $strict = rule\InArray::STRICT
    ): self {
        return $this->addRule(new rule\InArray($array, $strict));
    }

    /**
     * 验证该值为整数.
     * @param bool $strict 是否严格.
     * @return self
     */
    public function integer(bool $strict = false): self
    {
        return $this->addRule(new rule\IsInt($strict));
    }

    /**
     * 验证该值为IP.
     * @return self
     */
    public function ip(): self
    {
        return $this->addRule(new rule\IP());
    }

    /**
     * 验证该值为数组.
     * @return self
     */
    public function isArray(): self
    {
        return $this->addRule(new rule\IsArray());
    }

    /**
     * 验证该值为JSON.
     * @return self
     */
    public function json(): self
    {
        return $this->addRule(new rule\Json());
    }

    /**
     * 验证该值长度.
     * @param int $length 长度.
     * @return self
     */
    public function length(int $length): self
    {
        return $this->addRule(new rule\Length($length));
    }

    /**
     * 验证该值长度在此之间.
     * @param int $min 最小值.
     * @param int $max 最大值.
     * @return self
     */
    public function lengthBetween(int $min, int $max = null): self
    {
        return $this->addRule(new rule\LengthBetween($min, $max));
    }

    /**
     * 验证该值小于.
     * @param int $value 值.
     * @return self
     */
    public function lessThan(int $value): self
    {
        return $this->addRule(new rule\LessThan($value));
    }

    /**
     * 将规则对象安装到此链上.
     * @param Rule $rule 规则.
     * @return self
     */
    public function mount(Rule $rule): self
    {
        return $this->addRule($rule);
    }

    /**
     * 验证该值为数字或数字字符串.
     * @return self
     */
    public function numeric(): self
    {
        return $this->addRule(new rule\Numeric());
    }

    /**
     * 验证该值为手机号码.
     * @return self
     */
    public function phone(): self
    {
        return $this->addRule(new rule\Phone());
    }

    /**
     * 验证该值为QQ号码.
     * @return self
     */
    public function qq(): self
    {
        return $this->addRule(new rule\QQ());
    }

    /**
     * 验证该值与正则表达式匹配.
     * @param string $regex 正则表达式匹配.
     * @return self
     */
    public function regex(string $regex): self
    {
        return $this->addRule(new rule\Regex($regex));
    }

    /**
     * 验证该值必填.
     * @param callable|bool $required 是否必填.
     * @return self
     */
    public function required($required): self
    {
        $this->getRule(0)->setRequired($required);
        return $this;
    }

    /**
     * 验证该值为字符串.
     * @return self
     */
    public function string(): self
    {
        return $this->addRule(new rule\IsString());
    }

    /**
     * 验证该值为URL.
     * @param array $schemes 白名单.
     * @return self
     */
    public function url(array $schemes = []): self
    {
        return $this->addRule(new rule\Url($schemes));
    }

    /**
     * 验证该值为UUID.
     * @param int $version 版本.
     * @return self
     */
    public function uuid(int $version = rule\UUID::UUID_VALID): self
    {
        return $this->addRule(new rule\UUID($version));
    }

    /**
     * 验证该值为邮政编码.
     * @return self
     */
    public function zipCode(): self
    {
        return $this->addRule(new rule\ZipCode());
    }

    /**
     * 添加规则.
     * @param Rule $rule 规则.
     * @return self
     */
    protected function addRule(Rule $rule): self
    {
        $this->rules[] = $rule;
        return $this;
    }

    /**
     * 得到规则.
     * @param int $key 键名.
     * @return Rule
     */
    protected function getRule(int $key): Rule
    {
        return $this->rules[$key];
    }
}
