<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessJsonFile implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $file;
    public $chunkSize;
    public $limit;

    public function __construct($file, $chunkSize = 100, $limit = 0)
    {
        $this->file = $file;
        $this->chunkSize = $chunkSize;
        $this->limit = $limit;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(): void
    {
        $counter = 1;
        $items = collect();

        $file = gzopen($this->file, "r");

        while ($line = fgets($file)) {

            if ($items->count() < $this->chunkSize) {
                $items->add(array_filter(json_decode($line, true)));
            } else {
                ProcessJsonItems::dispatch($items);
                $items = collect();
                $items->add(array_filter(json_decode($line, true)));
            }

            if ($this->limit > 0 && $counter >= $this->limit) {
                ProcessJsonItems::dispatch($items);
                break;
            };
            $counter++;
        }
    }
}
