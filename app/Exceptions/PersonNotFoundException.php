<?php

namespace App\Exceptions;

use Exception;

/**
 * EntityNotFoundException
 *
 * Kivétel akkor történik, ha egy személy nem található
 *
 * @author Kovács Zoltán <zoltan1_kovacs@msn.com>
 */
class PersonNotFoundException extends Exception
{
    /**
     * A nem található személy azonosítója.
     *
     * @var int
     */
    protected int $personId;
    /**
     * A kivétel megjelenésekor megjelenő üzenet.
     *
     * @var string
     */
    protected string $message = '';

    // TODO: Nyelvesítés még nincs megoldva
    public function __construct(int $id) {
        $this->personId = $id;
        $this->message = __('exception_person_not_found', ['id' => $id]);
        // Hívja fel a szülő konstruktort az üzenettel
        parent::__construct( $this->message );
    }

    public function report() {
        // Jelentkezés a tevékenységek naplófájlban
        // https://spatie.be/docs/laravel-activitylog/v6/basic-usage/logging-activity
        activity()
            ->withProperties([
                'id' => $this->personId, // A keresett személy id-je
                'message' => $this->getMessage(), // A kivétel üzenete
            ])
            ->log( $this->message );
    }
}
