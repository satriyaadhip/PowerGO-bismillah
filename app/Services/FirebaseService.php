<?php

namespace App\Services;

use Kreait\Firebase\Factory;
use Kreait\Firebase\Database;

class FirebaseService
{
    protected Database $database;

    public function __construct()
    {
        $serviceAccount = base_path(env('FIREBASE_CREDENTIALS'));

        $this->database = (new Factory)
            ->withServiceAccount($serviceAccount)
            ->withDatabaseUri(env('FIREBASE_DATABASE_URL'))
            ->createDatabase();
    }

    public function getDatabase(): Database
    {
        return $this->database;
    }

    public function getData(string $path)
    {
        return $this->database->getReference($path)->getValue();
    }

    public function setData(string $path, array $data)
    {
        return $this->database->getReference($path)->set($data);
    }

    public function pushData(string $path, array $data)
    {
        return $this->database->getReference($path)->push($data);
    }
}
