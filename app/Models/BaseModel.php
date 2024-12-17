<?php

namespace App\Models;

use App\Core\Database;

abstract class BaseModel
{
    protected $db;
    protected $collection;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    /**
     * Get MongoDB collection
     */
    protected function getCollection()
    {
        return $this->db->getCollection($this->collection);
    }

    /**
     * Find one document by ID
     */
    public function findById($id)
    {
        return $this->getCollection()->findOne(['_id' => new \MongoDB\BSON\ObjectId($id)]);
    }

    /**
     * Find all documents
     */
    public function findAll()
    {
        return $this->getCollection()->find()->toArray();
    }

    /**
     * Insert one document
     */
    public function insert(array $data)
    {
        $result = $this->getCollection()->insertOne($data);
        return $result->getInsertedId();
    }

    /**
     * Update one document
     */
    public function update($id, array $data)
    {
        $result = $this->getCollection()->updateOne(
            ['_id' => new \MongoDB\BSON\ObjectId($id)],
            ['$set' => $data]
        );
        return $result->getModifiedCount();
    }

    /**
     * Delete one document
     */
    public function delete($id)
    {
        $result = $this->getCollection()->deleteOne(['_id' => new \MongoDB\BSON\ObjectId($id)]);
        return $result->getDeletedCount();
    }
} 