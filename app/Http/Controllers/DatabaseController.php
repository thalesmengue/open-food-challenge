<?php

namespace App\Http\Controllers;

use App\Services\DatabaseService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DatabaseController extends Controller
{
    public function __construct(
        private readonly DatabaseService $service
    )
    {
    }

    public function getStatus(): JsonResponse|string
    {
        try {
            $dbReadAndWrite = $this->service->getConnection();
            $lastTimeExecuted = $this->service->getCronLastExecution();


            return response()->json([
                'success' => true,
                'messages' => [
                    'databaseConnection' => $dbReadAndWrite,
                    'lastTimeExecuted' => $lastTimeExecuted
                ]
            ]);
        } catch (Exception $e) {
            die("Could not connect to the database.  Please check your configuration. error:" . $e->getMessage());
        }
    }
}
