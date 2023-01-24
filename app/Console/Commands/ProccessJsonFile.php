<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ProccessJsonFile extends Command
{
    protected $signature = 'json-file:proccess {filepath}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        return Command::SUCCESS;
    }
}
