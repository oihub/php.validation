# php.validation

php.validation (copy particle/validator)

## Quick usage example

```
$data = [
    'first_name' => 'John',
    'last_name' => 'Doe',
];
$validator = new Validator;
$validator->required('first_name')->lengthBetween(2, 30)->alpha();
$validator->optional('last_name')->lengthBetween(2, 40)->alpha();

$validator->overwriteDefaultMessages([
    LengthBetween::TOO_LONG => 'It\'s too long, that value'
]);
$validator->overwriteMessages([
    'last_name' => [
        LengthBetween::TOO_LONG => 'Your name is too long.'
    ]
]);

$result = $validator->validate($data);
$result->isValid(); // bool(true)
```

## Working with the each validator

```
$data = [
    'invoices' => [
        [
            'id' => 1,
            'date' => '2015-10-28',
            'amount' => 2500,
            'lines' => [
                [
                    'amount' => 500,
                    'description' => 'First line',
                ],
                [
                    'amount' => 2000,
                    'description' => 'Second line',
                ],
            ],
        ],
        [
            'id' => 2,
            'date' => '2015-11-28',
            'amount' => 2000,
            'lines' => [
                [
                    'amount' => 2000,
                    'description' => 'Second line of second invoice',
                ],
            ],
        ],
    ],
];

$validator = new Validator;

$validator->required('invoices')->each(function (Validator $validator) {
    $validator->required('id')->integer();
    $validator->required('amount')->integer();
    $validator->required('date')->datetime('Y-m-d');

    $validator->required('lines')->each(function (Validator $validator) {
        $validator->required('amount')->integer();
        $validator->required('description')->lengthBetween(0, 100);
    });
});

$result = $validator->validate($data);
$result->isValid(); // bool(true)
$result->getValues() === $values; // bool(true)
```

## Using contexts

```
$validator = new Validator;
$validator->context('insert', function (Validator $context) {
    $context->required('first_name')->lengthBetween(2, 30);
});

$validator->context('update', function (Validator $context) {
    $context->optional('first_name')->lengthBetween(2, 30);
});

$validator->validate([], 'update')->isValid(); // bool(true)
$validator->validate([], 'insert')->isValid(); // bool(false), because first_name is required.
```

## Copying from another context

```
$validator = new Validator;
$validator->context('insert', function (Validator $context) {
    $context->required('first_name')->lengthBetween(2, 30);
});

$validator->context('update', function (Validator $context) {
    // copy the rules (and messages) of the "insert" context.
    $context->copyContext('insert');
	
    // make the "first_name" field optional.
    $context->optional('first_name');
});

$result = $validator->validate([], 'update');
$result->isValid(); // bool(true)
```

## Extended example of copying

```
$validator = new Validator;
$validator->context('insert', function (Validator $context) {
    $context->required('first_name')->lengthBetween(2, 30);
    $context->required('last_name')->lengthBetween(2, 30);
});

$validator->context('update', function (Validator $context) {
    // copy the rules (and messages) of the "insert" context.
    $context->copyContext('insert', function ($rules) {
        foreach ($rules as $key => $chain) {
            $context->optional($key);
        }
    });
});
```

## Extending Validator

```
class MyValidator extends Validator
{
    protected function buildChain($key, $name, $required, $allowEmpty)
    {
        return new MyChain($key, $name, $required, $allowEmpty);
    }
}

class MyChain extends Chain
{
    public function grumpy($who = 'Grumpy Smurf')
    {
        return $this->addRule(new GrumpyRule($who));
    }
}

class GrumpyRule extends Rule
{
    const WRONG = 'GrumpyRule::WRONG';

    protected $messageTemplates = [
        self::WRONG => '{{ who }} hates the value of "{{ name }}"',
    ];

    protected $who;

    public function __construct($who)
    {
        $this->who = $who;
    }

    public function validate($value)
    {
        if ($value !== null || $value === null) { // always true, so always grumpy!
            return $this->error(self::WRONG);
        }
        return true;
    }
	
    protected function getMessageParameters()
    {
        return array_merge(parent::getMessageParameters(), [
            'who' => $this->who,
        ]);
    }
}

$validator = new MyValidator;
$validator->required('foo')->grumpy('Silly sally');
$result = $validator->validate(['foo' => true]);
```

## Using Chain::mount(Rule $rule)

```
$validator = new Validator;
$validator->required('foo')->mount(new GrumpyRule('Silly Sally'));
$result = $validator->validate(['foo' => true]);
```	
