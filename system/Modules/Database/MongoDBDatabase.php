<?php
/**
 * MongoDBDatabase - MongoDB driver
 */

namespace Seed\Modules\Database;

use MongoDB\Client;
use MongoDB\Driver\Exception\Exception as MongoException;

class MongoDBDatabase {
    protected $client;
    protected $database;
    protected $config = [];
    
    public function __construct($config = []) {
        $this->config = $config;
    }
    
    // Connect to MongoDB
    public function connect() {
        try {
            $uri = $this->buildUri();
            $this->client = new Client($uri);
            
            // Select database
            $dbName = $this->config['database'] ?? 'seed';
            $this->database = $this->client->selectDatabase($dbName);
            
            // Ping to verify connection
            $this->database->command(['ping' => 1]);
            
            return $this->database;
        } catch (MongoException $e) {
            log_error('MongoDB connection failed', ['error' => $e->getMessage()]);
            throw new \Exception('MongoDB connection failed: ' . $e->getMessage());
        }
    }
    
    // Build MongoDB connection URI
    protected function buildUri() {
        $host = $this->config['host'] ?? 'localhost';
        $port = $this->config['port'] ?? '27017';
        $username = $this->config['username'] ?? '';
        $password = $this->config['password'] ?? '';
        
        if ($username && $password) {
            return "mongodb://{$username}:{$password}@{$host}:{$port}";
        }
        
        return "mongodb://{$host}:{$port}";
    }
    
    // Get database instance (lazy connect)
    protected function getDatabase() {
        if (!$this->database) {
            $this->connect();
        }
        return $this->database;
    }
    
    // Query documents (find)
    public function query($collection, $filter = [], $options = []) {
        try {
            event('query.executing', ['collection' => $collection, 'filter' => $filter]);
            
            $db = $this->getDatabase();
            $cursor = $db->selectCollection($collection)->find($filter, $options);
            $results = iterator_to_array($cursor);
            
            event('query.executed', ['collection' => $collection, 'count' => count($results)]);
            
            return $results;
        } catch (MongoException $e) {
            log_error('MongoDB query failed', [
                'collection' => $collection,
                'error' => $e->getMessage()
            ]);
            throw new \Exception('MongoDB query failed: ' . $e->getMessage());
        }
    }
    
    // Query single document (findOne)
    public function queryOne($collection, $filter = [], $options = []) {
        try {
            event('query.executing', ['collection' => $collection, 'filter' => $filter]);
            
            $db = $this->getDatabase();
            $result = $db->selectCollection($collection)->findOne($filter, $options);
            
            event('query.executed', ['collection' => $collection, 'found' => !is_null($result)]);
            
            return $result;
        } catch (MongoException $e) {
            log_error('MongoDB queryOne failed', [
                'collection' => $collection,
                'error' => $e->getMessage()
            ]);
            throw new \Exception('MongoDB queryOne failed: ' . $e->getMessage());
        }
    }
    
    // Insert document
    public function insert($collection, $data) {
        try {
            event('query.executing', ['collection' => $collection, 'operation' => 'insert']);
            
            $db = $this->getDatabase();
            $result = $db->selectCollection($collection)->insertOne($data);
            $insertedId = (string) $result->getInsertedId();
            
            event('query.executed', ['collection' => $collection, 'inserted_id' => $insertedId]);
            
            return $insertedId;
        } catch (MongoException $e) {
            log_error('MongoDB insert failed', [
                'collection' => $collection,
                'error' => $e->getMessage()
            ]);
            throw new \Exception('MongoDB insert failed: ' . $e->getMessage());
        }
    }
    
    // Bulk insert documents
    public function bulkInsert($collection, $documents) {
        try {
            if (empty($documents)) {
                return [];
            }
            
            event('query.executing', ['collection' => $collection, 'operation' => 'bulkInsert', 'count' => count($documents)]);
            
            $db = $this->getDatabase();
            $result = $db->selectCollection($collection)->insertMany($documents);
            $insertedIds = array_map('strval', $result->getInsertedIds());
            
            event('query.executed', ['collection' => $collection, 'inserted_count' => count($insertedIds)]);
            
            return $insertedIds;
        } catch (MongoException $e) {
            log_error('MongoDB bulkInsert failed', [
                'collection' => $collection,
                'error' => $e->getMessage()
            ]);
            throw new \Exception('MongoDB bulkInsert failed: ' . $e->getMessage());
        }
    }
    
    // Update documents
    public function update($collection, $filter, $update, $options = []) {
        try {
            event('query.executing', ['collection' => $collection, 'operation' => 'update']);
            
            $db = $this->getDatabase();
            
            // Ensure update uses operators
            if (!isset($update['$set']) && !isset($update['$unset']) && !isset($update['$inc'])) {
                $update = ['$set' => $update];
            }
            
            $result = $db->selectCollection($collection)->updateMany($filter, $update, $options);
            $modifiedCount = $result->getModifiedCount();
            
            event('query.executed', ['collection' => $collection, 'modified_count' => $modifiedCount]);
            
            return $modifiedCount;
        } catch (MongoException $e) {
            log_error('MongoDB update failed', [
                'collection' => $collection,
                'error' => $e->getMessage()
            ]);
            throw new \Exception('MongoDB update failed: ' . $e->getMessage());
        }
    }
    
    // Update single document
    public function updateOne($collection, $filter, $update, $options = []) {
        try {
            event('query.executing', ['collection' => $collection, 'operation' => 'updateOne']);
            
            $db = $this->getDatabase();
            
            // Ensure update uses operators
            if (!isset($update['$set']) && !isset($update['$unset']) && !isset($update['$inc'])) {
                $update = ['$set' => $update];
            }
            
            $result = $db->selectCollection($collection)->updateOne($filter, $update, $options);
            $modifiedCount = $result->getModifiedCount();
            
            event('query.executed', ['collection' => $collection, 'modified_count' => $modifiedCount]);
            
            return $modifiedCount;
        } catch (MongoException $e) {
            log_error('MongoDB updateOne failed', [
                'collection' => $collection,
                'error' => $e->getMessage()
            ]);
            throw new \Exception('MongoDB updateOne failed: ' . $e->getMessage());
        }
    }
    
    // Delete documents
    public function delete($collection, $filter) {
        try {
            event('query.executing', ['collection' => $collection, 'operation' => 'delete']);
            
            $db = $this->getDatabase();
            $result = $db->selectCollection($collection)->deleteMany($filter);
            $deletedCount = $result->getDeletedCount();
            
            event('query.executed', ['collection' => $collection, 'deleted_count' => $deletedCount]);
            
            return $deletedCount;
        } catch (MongoException $e) {
            log_error('MongoDB delete failed', [
                'collection' => $collection,
                'error' => $e->getMessage()
            ]);
            throw new \Exception('MongoDB delete failed: ' . $e->getMessage());
        }
    }
    
    // Delete single document
    public function deleteOne($collection, $filter) {
        try {
            event('query.executing', ['collection' => $collection, 'operation' => 'deleteOne']);
            
            $db = $this->getDatabase();
            $result = $db->selectCollection($collection)->deleteOne($filter);
            $deletedCount = $result->getDeletedCount();
            
            event('query.executed', ['collection' => $collection, 'deleted_count' => $deletedCount]);
            
            return $deletedCount;
        } catch (MongoException $e) {
            log_error('MongoDB deleteOne failed', [
                'collection' => $collection,
                'error' => $e->getMessage()
            ]);
            throw new \Exception('MongoDB deleteOne failed: ' . $e->getMessage());
        }
    }
    
    // Count documents
    public function count($collection, $filter = []) {
        try {
            $db = $this->getDatabase();
            return $db->selectCollection($collection)->countDocuments($filter);
        } catch (MongoException $e) {
            log_error('MongoDB count failed', [
                'collection' => $collection,
                'error' => $e->getMessage()
            ]);
            throw new \Exception('MongoDB count failed: ' . $e->getMessage());
        }
    }
    
    // Aggregation pipeline
    public function aggregate($collection, $pipeline, $options = []) {
        try {
            event('query.executing', ['collection' => $collection, 'operation' => 'aggregate']);
            
            $db = $this->getDatabase();
            $cursor = $db->selectCollection($collection)->aggregate($pipeline, $options);
            $results = iterator_to_array($cursor);
            
            event('query.executed', ['collection' => $collection, 'count' => count($results)]);
            
            return $results;
        } catch (MongoException $e) {
            log_error('MongoDB aggregate failed', [
                'collection' => $collection,
                'error' => $e->getMessage()
            ]);
            throw new \Exception('MongoDB aggregate failed: ' . $e->getMessage());
        }
    }
    
    // Create index
    public function createIndex($collection, $keys, $options = []) {
        try {
            $db = $this->getDatabase();
            return $db->selectCollection($collection)->createIndex($keys, $options);
        } catch (MongoException $e) {
            log_error('MongoDB createIndex failed', [
                'collection' => $collection,
                'error' => $e->getMessage()
            ]);
            throw new \Exception('MongoDB createIndex failed: ' . $e->getMessage());
        }
    }
    
    // Drop collection
    public function dropCollection($collection) {
        try {
            $db = $this->getDatabase();
            return $db->dropCollection($collection);
        } catch (MongoException $e) {
            log_error('MongoDB dropCollection failed', [
                'collection' => $collection,
                'error' => $e->getMessage()
            ]);
            throw new \Exception('MongoDB dropCollection failed: ' . $e->getMessage());
        }
    }
    
    // Start transaction (MongoDB 4.0+)
    public function beginTransaction() {
        try {
            $session = $this->client->startSession();
            $session->startTransaction();
            return $session;
        } catch (MongoException $e) {
            log_error('MongoDB transaction start failed', ['error' => $e->getMessage()]);
            throw new \Exception('MongoDB transaction start failed: ' . $e->getMessage());
        }
    }
    
    // Commit transaction
    public function commit($session) {
        try {
            $session->commitTransaction();
        } catch (MongoException $e) {
            log_error('MongoDB transaction commit failed', ['error' => $e->getMessage()]);
            throw new \Exception('MongoDB transaction commit failed: ' . $e->getMessage());
        }
    }
    
    // Rollback transaction
    public function rollback($session) {
        try {
            $session->abortTransaction();
        } catch (MongoException $e) {
            log_error('MongoDB transaction rollback failed', ['error' => $e->getMessage()]);
            throw new \Exception('MongoDB transaction rollback failed: ' . $e->getMessage());
        }
    }
    
    // Get raw database instance for advanced operations
    public function raw() {
        return $this->getDatabase();
    }
    
    // Get raw client instance
    public function client() {
        if (!$this->client) {
            $this->connect();
        }
        return $this->client;
    }
}

