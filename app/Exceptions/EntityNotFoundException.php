<?php

namespace App\Exceptions;

use Exception;

/**
 * EntityNotFoundException
 *
 * Kivétel akkor történik, ha egy entitás nem található
 *
 * @author Kovács Zoltán <zoltan1_kovacs@msn.com>
 */
class EntityNotFoundException extends Exception
{
    /**
     * EntityNotFoundException konstruktor
     *
     * @param int $id A nem található entitás azonosítója
     */
    public function __construct($id)
    {
        // Hívja fel a szülő konstruktort az üzenettel
        parent::__construct( message: __(key: 'exception_entity_not_found', replace: ['id' => $id]) );
    }
}
