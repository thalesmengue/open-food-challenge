<?php

namespace App\Jobs;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class GetProductJsonFile implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle()
    {
        $this->getLastTimeExecuted();

        $files = [
            'products_01.json.gz',
            'products_02.json.gz',
            'products_03.json.gz',
            'products_04.json.gz',
            'products_05.json.gz',
            'products_06.json.gz',
            'products_07.json.gz',
            'products_08.json.gz',
            'products_09.json.gz'
        ];

        $this->deleteOldFiles();

        foreach ($files as $file) {
            $url = 'https://challenges.coode.sh/food/data/json/' . $file;
            $this->download($url, storage_path('app/' . $file));
        }

        ProcessAllJsonFiles::dispatch();
    }

    private function download($url, $destination): void
    {
        file_put_contents($destination, file_get_contents($url));
    }

    private function deleteOldFiles()
    {
        $files = [
            'products_01.json.gz',
            'products_02.json.gz',
            'products_03.json.gz',
            'products_04.json.gz',
            'products_05.json.gz',
            'products_06.json.gz',
            'products_07.json.gz',
            'products_08.json.gz',
            'products_09.json.gz'
        ];

        foreach ($files as $file) {
            Storage::delete($file);
        }
    }

    private function getLastTimeExecuted()
    {
        $time = Carbon::now();

        DB::table('cron_date_info')->insert([
            'last_execution' => $time
        ]);
    }
}
