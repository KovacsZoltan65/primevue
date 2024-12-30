<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\SettingsMetadata;
use Illuminate\Support\Facades\Schema;

class SettingsMetadataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $metadata = [
            [
                'key' => 'default_language',
                'label' => 'Default Language',
                'description' => 'The default language for the application.',
                'type' => 'string',
                'level' => 'application',
                'is_required' => true,
                'default_value' => 'en',
                'validation_rules' => json_encode(['required', 'string', 'max:5']),
            ],
            [
                'key' => 'theme',
                'label' => 'Theme',
                'description' => 'The theme of the application interface.',
                'type' => 'string',
                'level' => 'application',
                'is_required' => false,
                'default_value' => 'light',
                'validation_rules' => json_encode(['string', 'in:light,dark']),
            ],
            [
                'key' => 'timezone',
                'label' => 'Timezone',
                'description' => 'The default timezone for the company.',
                'type' => 'string',
                'level' => 'company',
                'is_required' => true,
                'default_value' => 'UTC',
                'validation_rules' => json_encode(['required', 'string']),
            ],
            [
                'key' => 'currency',
                'label' => 'Currency',
                'description' => 'The default currency for the company.',
                'type' => 'string',
                'level' => 'company',
                'is_required' => false,
                'default_value' => 'USD',
                'validation_rules' => json_encode(['string', 'max:3']),
            ],
        ];

        foreach ($metadata as $data) {
            SettingsMetadata::create($data);
        }
    }
}
