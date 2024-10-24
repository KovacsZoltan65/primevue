<?php

namespace App\Jobs;

use Illuminate\Bus\Batchable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessDbBackup implements ShouldQueue
{
    use Queueable,
        Batchable,
        Dispatchable,
        InteractsWithQueue,
        SerializesModels;

    private $backupDatas = [
        [ 'db_name' => '', 'db_user' => '', 'db_password' => '', ],
    ];
    
    /**
     * Create a new job instance.
     */
    public function __construct($backupDatas)
    {
        $this->backupDatas = $backupDatas;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            foreach( $this->backupDatas as $backupData )
            {
                //
            }
            
            \Illuminate\Support\Facades\Artisan::call('db:backup', [
                'db_name' => $backupData['db_name'],
                '--db_user' => $backupData['db_user'],
                '--db_password' => $backupData['db_password'],
                '--filename' => $backupData['filename'],
            ]);
            
        } catch( \Exception $e ) {
            \Log::error('ProcessDbBackup@handle error: ' . print_r($e->getMessage(), true));
        } finally {
            //
        }
    }
}
