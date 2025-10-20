<?php

namespace App\Services;

use Kreait\Firebase\Factory;
use Kreait\Firebase\Database;

class FirebaseService
{
    protected $database;

    public function __construct()
    {
        $factory = (new Factory)
            ->withServiceAccount(config('services.firebase.credentials.file'))
            ->withDatabaseUri(config('services.firebase.database.url'));

        $this->database = $factory->createDatabase();
    }

    public function getDatabase(): Database
    {
        return $this->database;
    }

    public function getData(string $path)
    {
        return $this->database->getReference($path)->getValue();
    }

    public function setData(string $path, $data)
    {
        return $this->database->getReference($path)->set($data);
    }

    public function pushData(string $path, $data)
    {
        return $this->database->getReference($path)->push($data);
    }
}
