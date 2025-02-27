<?php

namespace Tests\API\FileImport;

use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class testImportFile extends TestCase
{
    public function testFileImport(): void
    {
        // Generáljunk egy fake CSV fájlt egyedi tartalommal
        $csvContent = "name,email\nJohn Doe,john@example.com\nJane Doe,jane@example.com";
        $file = UploadedFile::fake()->createWithContent('test.csv', $csvContent);

        // Feltételezve, hogy az import végpontunk a /import URL-en keresztül érhető el
        $response = $this->post('/import', [
            'file' => $file,
        ]);

        // Ellenőrizzük, hogy a válasz státusza 200 OK
        $response->assertStatus(200);

        // Itt érdemes ellenőrizni, hogy a file importálás során létrejött változások megtörténtek-e,
        // pl. a megfelelő rekordok beszúrásra kerültek-e az adatbázisba.
        $this->assertDatabaseHas('users', [
            'email' => 'jane@example.com'
        ]);
    }
}