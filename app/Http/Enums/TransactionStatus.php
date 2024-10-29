<?php

namespace App\Enums;

enum TransactionStatus: string
{
    case AUTHORISED = 'authorised';
    case DECLINE = 'decline';
    case REFUNDED = 'refunded';

    public static function fromProviderXCode(int $code): self
    {
        return match($code) {
            1 => self::AUTHORISED,
            2 => self::DECLINE,
            3 => self::REFUNDED,
            default => throw new \InvalidArgumentException('Invalid status code')
        };
    }

    public static function fromProviderYCode(int $code): self
    {
        return match($code) {
            100 => self::AUTHORISED,
            200 => self::DECLINE,
            300 => self::REFUNDED,
            default => throw new \InvalidArgumentException('Invalid status code')
        };
    }
}