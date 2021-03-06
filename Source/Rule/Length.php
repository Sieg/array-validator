<?php

namespace Sieg\ArrayValidator\Rule;

use Sieg\ArrayValidator\Exception\RuleFailed;

class Length extends AbstractRule
{
    public const MESSAGE = 'VALIDATOR_RULE_LENGTH_FAILED';

    /** @var int */
    private $length;

    public function __construct(int $length)
    {
        $this->length = $length;
    }

    /**
     * @param mixed[] $data
     */
    public function process(string $key, array $data): bool
    {
        if (!isset($data[$key]) || strlen($data[$key]) !== $this->length) {
            throw new RuleFailed(self::MESSAGE);
        }

        return true;
    }
}
