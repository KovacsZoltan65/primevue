<?php

namespace App\Enums;

/**
 * https://github.com/benbjurstrom/plink
 */

enum Status: int
{
    case ACTIVE = 0;
    case SUPERSEEDED = 1;
    case EXPIRED = 2;
    case USED = 3;
    case INVALID = 4;
    case SESSION = 5;

    public function errorMessage(): string
    {
        return match ($this) {
            self::ACTIVE => 'Active',
            self::SUPERSEEDED => 'Superseded',
            self::EXPIRED => 'Expired',
            self::USED => 'Used',
            self::INVALID => 'Invalid',
            self::SESSION => 'Session',
            default => 'Unknown status',
        };
    }
}
