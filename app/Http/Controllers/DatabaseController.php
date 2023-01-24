<?php

namespace App\Http\Controllers;

use App\Repositories\DatabaseRepository;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DatabaseController extends Controller
{
    public DatabaseRepository $databaseRepository;
    public function __construct()
    {
        $this->databaseRepository = new DatabaseRepository();
    }
    public function getStatus()
    {
        try {
            $dbReadAndWrite = $this->databaseRepository->getConnection();


            return response()->json([
                'success' => true,
                'messages' => [
                    'database_connection' => $dbReadAndWrite
                ]
            ]);
        } catch (Exception $e) {
            die("Could not connect to the database.  Please check your configuration. error:" . $e->getMessage());
        }
    }
}
