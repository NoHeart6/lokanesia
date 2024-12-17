<?php

namespace App\Core;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use MongoDB\BSON\ObjectId;
use Exception;

class Auth {
    private static ?Auth $instance = null;
    private Database $db;
    private string $jwtSecret;
    private int $jwtExpiration;

    private function __construct() {
        $this->db = Database::getInstance();
        $this->jwtSecret = $_ENV['JWT_SECRET'] ?? 'your-secret-key';
        $this->jwtExpiration = (int) ($_ENV['JWT_EXPIRATION'] ?? 86400); // 24 jam
    }

    public static function getInstance(): Auth {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function login(string $email, string $password): ?array {
        try {
            error_log("Login attempt for email: " . $email);
            
            $users = $this->db->getCollection('users');
            $user = $users->findOne(['email' => $email]);

            if (!$user) {
                error_log("User not found with email: " . $email);
                return null;
            }

            error_log("Found user: " . print_r($this->sanitizeUser($user), true));

            if (!password_verify($password, $user->password)) {
                error_log("Invalid password for user: " . $email);
                return null;
            }

            $token = $this->generateToken($user->_id);
            error_log("Generated token for user: " . $email);

            return [
                'user' => $this->sanitizeUser($user),
                'token' => $token
            ];
        } catch (Exception $e) {
            error_log("Login error: " . $e->getMessage());
            throw $e;
        }
    }

    public function register(array $data): ?array {
        try {
            error_log("Starting registration process for email: " . ($data['email'] ?? 'not provided'));
            
            $users = $this->db->getCollection('users');

            // Check if email already exists
            if ($users->findOne(['email' => $data['email']])) {
                error_log("Registration failed: Email already exists - " . $data['email']);
                throw new Exception("Email already exists");
            }

            // Validate required fields
            $requiredFields = ['name', 'email', 'password'];
            foreach ($requiredFields as $field) {
                if (empty($data[$field])) {
                    error_log("Registration failed: Missing required field - " . $field);
                    throw new Exception("Missing required field: " . $field);
                }
            }

            // Validate email format
            if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
                error_log("Registration failed: Invalid email format - " . $data['email']);
                throw new Exception("Invalid email format");
            }

            // Validate password length
            if (strlen($data['password']) < 6) {
                error_log("Registration failed: Password too short");
                throw new Exception("Password must be at least 6 characters");
            }

            // Hash password
            $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
            
            // Add additional fields
            $now = new \MongoDB\BSON\UTCDateTime();
            $data['created_at'] = $now;
            $data['updated_at'] = $now;
            $data['email_verified'] = false;

            error_log("Attempting to insert new user into database");
            $result = $users->insertOne($data);
            
            if (!$result->getInsertedId()) {
                error_log("Registration failed: Could not insert user into database");
                throw new Exception("Failed to create user");
            }

            $user = $users->findOne(['_id' => $result->getInsertedId()]);
            if (!$user) {
                error_log("Registration failed: Could not retrieve created user");
                throw new Exception("Failed to retrieve created user");
            }

            $token = $this->generateToken($user->_id);
            error_log("Registration successful for email: " . $data['email']);

            return [
                'user' => $this->sanitizeUser($user),
                'token' => $token
            ];
        } catch (Exception $e) {
            error_log("Registration error: " . $e->getMessage());
            throw $e;
        }
    }

    public function validateToken(string $token): ?object {
        try {
            $decoded = JWT::decode($token, new Key($this->jwtSecret, 'HS256'));
            $users = $this->db->getCollection('users');
            return $users->findOne(['_id' => new ObjectId($decoded->sub)]);
        } catch (Exception $e) {
            error_log("Token validation error: " . $e->getMessage());
            return null;
        }
    }

    public function generateToken($userId): string {
        $payload = [
            'iss' => $_ENV['APP_URL'] ?? 'http://localhost',
            'sub' => (string) $userId,
            'iat' => time(),
            'exp' => time() + $this->jwtExpiration
        ];

        return JWT::encode($payload, $this->jwtSecret, 'HS256');
    }

    public function sanitizeUser($user): array {
        return [
            'id' => (string) $user->_id,
            'name' => $user->name,
            'email' => $user->email,
            'created_at' => $user->created_at->toDateTime()->format('c'),
            'email_verified' => $user->email_verified ?? false
        ];
    }

    public function updateUser(string $userId, array $data): ?array {
        $users = $this->db->getCollection('users');
        
        // Remove sensitive fields
        unset($data['password']);
        unset($data['_id']);
        
        $data['updated_at'] = new \MongoDB\BSON\UTCDateTime();

        $result = $users->updateOne(
            ['_id' => new ObjectId($userId)],
            ['$set' => $data]
        );

        if ($result->getModifiedCount() === 0) {
            return null;
        }

        $user = $users->findOne(['_id' => new ObjectId($userId)]);
        return $this->sanitizeUser($user);
    }

    public function changePassword(string $userId, string $currentPassword, string $newPassword): bool {
        $users = $this->db->getCollection('users');
        $user = $users->findOne(['_id' => new ObjectId($userId)]);

        if (!$user || !password_verify($currentPassword, $user->password)) {
            return false;
        }

        $result = $users->updateOne(
            ['_id' => new ObjectId($userId)],
            [
                '$set' => [
                    'password' => password_hash($newPassword, PASSWORD_DEFAULT),
                    'updated_at' => new \MongoDB\BSON\UTCDateTime()
                ]
            ]
        );

        return $result->getModifiedCount() > 0;
    }

    public function resetPassword(string $email): bool {
        $users = $this->db->getCollection('users');
        $user = $users->findOne(['email' => $email]);

        if (!$user) {
            return false;
        }

        // Generate reset token
        $resetToken = bin2hex(random_bytes(32));
        $resetExpires = new \MongoDB\BSON\UTCDateTime((time() + 3600) * 1000);

        $result = $users->updateOne(
            ['_id' => $user->_id],
            [
                '$set' => [
                    'reset_token' => $resetToken,
                    'reset_expires' => $resetExpires,
                    'updated_at' => new \MongoDB\BSON\UTCDateTime()
                ]
            ]
        );

        if ($result->getModifiedCount() === 0) {
            return false;
        }

        // TODO: Send reset password email
        return true;
    }

    public function confirmResetPassword(string $token, string $newPassword): bool {
        $users = $this->db->getCollection('users');
        $user = $users->findOne([
            'reset_token' => $token,
            'reset_expires' => ['$gt' => new \MongoDB\BSON\UTCDateTime()]
        ]);

        if (!$user) {
            return false;
        }

        $result = $users->updateOne(
            ['_id' => $user->_id],
            [
                '$set' => [
                    'password' => password_hash($newPassword, PASSWORD_DEFAULT),
                    'updated_at' => new \MongoDB\BSON\UTCDateTime()
                ],
                '$unset' => [
                    'reset_token' => '',
                    'reset_expires' => ''
                ]
            ]
        );

        return $result->getModifiedCount() > 0;
    }
} 