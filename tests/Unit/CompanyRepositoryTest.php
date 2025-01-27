<?php

namespace Tests\Feature;

use PHPUnit\Framework\TestCase;
use App\Repositories\CompanyRepository;

class CompanyRepositoryTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function testFindCompanyById(): void
    {
        $repository = $this
            ->createMock(CompanyRepository::class);

        $repository->method('getCompany')
            ->willReturn(
                [
                    'id' => 1,
                    'name' => 'Test Company'
                ]
            );

        // Replace 1 with the actual ID of the company you want to retrieve from the
        $result = $repository->findById(1);

        $this->assertEquals('Mocked Company', $result['name']);
    }
}
