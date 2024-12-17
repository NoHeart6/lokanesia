<?php

namespace App\Models;

use App\Core\Database;
use MongoDB\BSON\ObjectId;

class Itinerary
{
    private Database $db;
    private string $collection = 'itineraries';

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    public function getAll()
    {
        return $this->db->getCollection($this->collection)
            ->find([], ['sort' => ['created_at' => -1]])
            ->toArray();
    }

    public function findById($id)
    {
        return $this->db->getCollection($this->collection)->findOne([
            '_id' => new ObjectId($id)
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

    public function getPopular($limit = 5)
    {
        return $this->db->getCollection($this->collection)
            ->find(
                ['status' => 'published'],
                [
                    'sort' => ['like_count' => -1],
                    'limit' => $limit
                ]
            )->toArray();
    }

    public function getUserItineraries($userId, $limit = 10)
    {
        return $this->db->getCollection($this->collection)
            ->find(
                ['user_id' => new ObjectId($userId)],
                [
                    'sort' => ['created_at' => -1],
                    'limit' => $limit
                ]
            )->toArray();
    }
} 