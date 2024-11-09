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
     * A nem található entitás azonosítója.
     *
     * @var int
     */
     protected int $entityId;
    /**
     * A kivétel megjelenésekor megjelenő üzenet.
     *
     * @var string
     */
    protected string $message = '';

    /**
     * EntityNotFoundException konstruktor
     *
     * @param int $id A nem található entitás azonosítója
     */
    public function __construct($id)
    {
        $this->entityId = $id;
        $this->message = __('exception_entity_not_found', ['id' => $id]);
        // Hívja fel a szülő konstruktort az üzenettel
        parent::__construct( $this->message );
    }

    public function report() {
        // Jelentkezés a tevékenységek naplófájlban
        // https://spatie.be/docs/laravel-activitylog/v6/basic-usage/logging-activity
        activity()
            ->withProperties([
                'id' => $this->entityId, // A keresett entitás id-je
                'message' => $this->getMessage(), // A kivétel üzenete
            ])
            ->log( $this->message );
    }
}
