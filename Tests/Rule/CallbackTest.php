<?php

namespace Sieg\ArrayValidator\Tests\Rule;

use PHPUnit\Framework\TestCase;
use Sieg\ArrayValidator\Rule\Callback;

class CallbackTest extends TestCase
{
    public function testProcessCallbackMethodSuccess()
    {
        $config = [
            'callback' => function($key, $data) {
                return true;
            }
        ];
        $rule = new Callback($config);
        $this->assertTrue($rule->process('field1', []));
    }

    public function testProcessRemoteClassMethod()
    {
        $config = [
            'callback' => '\Sieg\ArrayValidator\Tests\Rule\CallbackTest::exampleCallback'
        ];
        $rule = new Callback($config);
        $this->assertTrue($rule->process('field1', []));
    }

    /** example callback method for testing remote callback */
    public function exampleCallback()
    {
        return true;
    }

    /**
     * @expectedException \Sieg\ArrayValidator\Exception\RuleFailed
     * @expectedExceptionMessage \Sieg\ArrayValidator\Rule\Callback::MESSAGE
     */
    public function testProcessFailed()
    {
        $config = [
            'callback' => function($key, $data) {
                return false;
            }
        ];
        $rule = new Callback($config);
        $this->assertTrue($rule->process('field1', []));
    }
}
