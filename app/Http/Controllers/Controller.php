<?php

namespace App\Http\Controllers;

use Kreait\Firebase\Database\Reference;
use App\Services\FirebaseService;

abstract class Controller
{
    protected Reference $database;

    public function __construct(FirebaseService $firebase)
    {
        $this->database = $firebase->getDatabase()->getReference();
    }
}
