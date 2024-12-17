<?php

namespace App\Models;

use App\Core\Database;
use MongoDB\BSON\ObjectId;

class User
{
    private Database $db;
    private string $collection = 'users';

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    public function findById($id)
    {
        return $this->db->getCollection($this->collection)->findOne([
            '_id' => new ObjectId($id)
        ]);
    }

    public function findByEmail($email)
    {
        return $this->db->getCollection($this->collection)->findOne([
            'email' => $email
        ]);
    }

    public function create($data)
    {
        return $this->db->getCollection($this->collection)->insertOne($data);
    }

    public function update($id, $data)
    {
        return $this->db->getCollection($this->collection)->updateOne(
            ['_id' => new ObjectId($id)],
            ['$set' => $data]
        );
    }

    public function delete($id)
    {
        return $this->db->getCollection($this->collection)->deleteOne([
            '_id' => new ObjectId($id)
        ]);
    }
} 