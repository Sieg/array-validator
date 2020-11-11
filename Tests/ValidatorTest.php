<?php

namespace Sieg\ArrayValidator\Tests;

use PHPUnit\Framework\TestCase;
use Sieg\ArrayValidator\Rule;
use Sieg\ArrayValidator\Validator;

class ValidatorTest extends TestCase
{
    public $configurationExample = [
        Rule\Required::class => [
            'fields' => ['field1', 'field2', 'field3']
        ],
        Rule\Expression::class => [
            [
                'fields' => ['field1', 'field3'],
                'pattern' => '/value\d+/'
            ],
            [
                'fields' => ['field2'],
                'pattern' => '/Value/i',
                'message' => 'super message'
            ]
        ]
    ];

    public function testConstructor()
    {
        $validator = new Validator();
        $this->assertInstanceOf(Validator::class, $validator);
    }

    public function testValidate()
    {
        $data = [
            'field1' => 'value1',
            'field2' => 'value2',
            'field3' => 'value3'
        ];

        $validator = new Validator($this->configurationExample);
        $this->assertTrue($validator->isValid($data));
    }

    public function testEmptyGetErrors()
    {
        $validator = new Validator();
        $this->assertEmpty($validator->getErrors());
    }

    public function testErrors()
    {
        $data = [
            'field1' => 'value1',
            'field2' => 'something'
        ];

        $validator = new Validator($this->configurationExample);
        $validator->isValid($data);

        $expected = [
            'field2' => [
                'super message'
            ],
            'field3' => [
                Rule\Required::MESSAGE,
                Rule\Expression::MESSAGE
            ]
        ];

        $this->assertEquals($expected, $validator->getErrors());
    }

    public function testGetRule()
    {
        $validator = new Validator($this->configurationExample);

        $this->assertSame(
            $this->configurationExample[Rule\Required::class],
            $validator->getRule(Rule\Required::class)
        );

        $this->assertSame(
            $this->configurationExample[Rule\Expression::class],
            $validator->getRule(Rule\Expression::class)
        );
    }

    public function testSetRule()
    {
        $ruleConfig = ['key' => "exampleValue"];
        $validator = new Validator($this->configurationExample);
        $validator->setRule(Rule\Required::class, $ruleConfig);
        $this->assertSame($ruleConfig, $validator->getRule(Rule\Required::class));
    }

    public function testAddRuleToOne()
    {
        $ruleConfig = ['key' => "exampleValue"];
        $validator = new Validator($this->configurationExample);
        $validator->addRule(Rule\Required::class, $ruleConfig);
        $expected = [$this->configurationExample[Rule\Required::class]];
        $expected[] = $ruleConfig;

        $this->assertSame($expected, $validator->getRule(Rule\Required::class));
    }

    public function testAddRuleToMultiple()
    {
        $ruleConfig = ['key' => "exampleValue"];
        $validator = new Validator($this->configurationExample);
        $validator->addRule(Rule\Expression::class, $ruleConfig);
        $expected = $this->configurationExample[Rule\Expression::class];
        $expected[] = $ruleConfig;

        $this->assertSame($expected, $validator->getRule(Rule\Expression::class));
    }

    public function testAddRuleToNotExisting()
    {
        $ruleConfig = ['key' => "exampleValue"];
        $validator = new Validator($this->configurationExample);
        $validator->addRule("other", $ruleConfig);

        $this->assertSame([$ruleConfig], $validator->getRule("other"));
    }
}
