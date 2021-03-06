<?php

namespace Sieg\ArrayValidator\Rule;

use Sieg\ArrayValidator\Exception\RuleFailed;

class MinLength extends AbstractRule
{
    public const MESSAGE = 'VALIDATOR_RULE_LENGTH_TOO_LOW';

    /** @var int */
    private $min;

    public function __construct(int $min)
    {
        $this->min = $min;
    }

    /**
     * @param mixed[] $data
     */
    public function process(string $key, array $data): bool
    {
        if (!isset($data[$key]) || strlen($data[$key]) < $this->min) {
            throw new RuleFailed(self::MESSAGE);
        }

        return true;
    }
}
