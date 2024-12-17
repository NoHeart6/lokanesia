<?php

namespace App\Controllers;

use MongoDB\Client;
use MongoDB\BSON\ObjectId;
use MongoDB\BSON\UTCDateTime;

class AuthController {
    private $db;
    private $users;

    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        try {
            // Load environment variables if not already loaded
            if (!getenv('MONGODB_URI')) {
                $envFile = __DIR__ . '/../../.env';
                if (file_exists($envFile)) {
                    $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
                    foreach ($lines as $line) {
                        if (strpos($line, '=') !== false && strpos($line, '#') !== 0) {
                            list($key, $value) = explode('=', $line, 2);
                            putenv(trim($key) . '=' . trim($value));
                            $_ENV[trim($key)] = trim($value);
                        }
                    }
                }
            }

            $client = new Client(getenv('MONGODB_URI'));
            $this->db = $client->selectDatabase(getenv('MONGODB_DB'));
            $this->users = $this->db->users;
        } catch (\Exception $e) {
            error_log("Database connection error: " . $e->getMessage() . "\n" . $e->getTraceAsString());
            throw new \Exception("Tidak dapat terhubung ke database: " . $e->getMessage());
        }
    }

    private function sanitizeInput($input) {
        return htmlspecialchars(strip_tags(trim($input)), ENT_QUOTES, 'UTF-8');
    }

    private function generateUsername($name, $email) {
        // Ambil bagian pertama dari email (sebelum @)
        $username = strtolower(explode('@', $email)[0]);
        
        // Hapus karakter yang tidak diinginkan
        $username = preg_replace('/[^a-z0-9]/', '', $username);
        
        // Cek apakah username sudah ada
        $count = 1;
        $originalUsername = $username;
        while ($this->users->findOne(['username' => $username])) {
            $username = $originalUsername . $count;
            $count++;
        }
        
        return $username;
    }

    private function jsonResponse($status, $message, $data = null) {
        header('Content-Type: application/json');
        $response = ['status' => $status, 'message' => $message];
        if ($data !== null) {
            $response['data'] = $data;
        }
        echo json_encode($response);
        exit;
    }

    public function loginPage($request, $response) {
        try {
            error_log("AuthController::loginPage - Starting");
            
            if (isset($_SESSION['user_id'])) {
                error_log("AuthController::loginPage - User already logged in, redirecting to dashboard");
                $response->redirect('/dashboard');
                return;
            }
            
            $data = [
                'registered' => isset($_GET['registered']) ? true : false,
                'message' => null,
                'title' => 'Login - ' . ($_ENV['APP_NAME'] ?? 'Lokanesia')
            ];
            
            error_log("AuthController::loginPage - Rendering login view with data: " . print_r($data, true));
            $response->view('auth/login', $data);
        } catch (\Exception $e) {
            error_log("AuthController::loginPage - Error: " . $e->getMessage());
            error_log("Stack trace: " . $e->getTraceAsString());
            error_log("In file: " . $e->getFile() . " on line " . $e->getLine());
            
            $response->serverError($e->getMessage());
        }
    }

    public function login() {
        try {
            $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
            $password = $_POST['password'] ?? '';
            $remember = isset($_POST['remember']);

            // Validasi input
            if (empty($email) || empty($password)) {
                $this->jsonResponse('error', 'Email dan password harus diisi');
            }

            // Cari user berdasarkan email
            $user = $this->users->findOne(['email' => $email]);
            
            if (!$user || !password_verify($password, $user->password)) {
                $this->jsonResponse('error', 'Email atau password salah');
            }

            // Set session
            $_SESSION['user_id'] = (string)$user->_id;
            $_SESSION['user_email'] = $user->email;
            $_SESSION['user_name'] = $user->name;
            
            if ($remember) {
                // Set cookie untuk "Remember Me"
                $token = bin2hex(random_bytes(32));
                setcookie('remember_token', $token, time() + (86400 * 30), '/', '', true, true);
                
                // Simpan token di database
                $this->users->updateOne(
                    ['_id' => $user->_id],
                    ['$set' => ['remember_token' => $token]]
                );
            }

            $this->jsonResponse('success', 'Login berhasil', [
                'redirect' => '/dashboard',
                'user' => [
                    'name' => $user->name,
                    'email' => $user->email
                ]
            ]);
        } catch (\Exception $e) {
            error_log("Login error: " . $e->getMessage() . "\n" . $e->getTraceAsString());
            $this->jsonResponse('error', 'Terjadi kesalahan saat login');
        }
    }

    public function register() {
        try {
            error_log("Starting registration process");
            
            // Sanitasi input
            $name = $this->sanitizeInput($_POST['name'] ?? '');
            $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
            $password = $_POST['password'] ?? '';
            $password_confirmation = $_POST['password_confirmation'] ?? '';

            error_log("Received registration data - Name: $name, Email: $email");

            // Validasi input
            if (empty($name) || empty($email) || empty($password)) {
                error_log("Empty fields detected");
                $this->jsonResponse('error', 'Semua field harus diisi');
            }

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                error_log("Invalid email format: $email");
                $this->jsonResponse('error', 'Format email tidak valid');
            }

            if (strlen($password) < 8) {
                error_log("Password too short");
                $this->jsonResponse('error', 'Password minimal 8 karakter');
            }

            if ($password !== $password_confirmation) {
                error_log("Password confirmation doesn't match");
                $this->jsonResponse('error', 'Konfirmasi password tidak cocok');
            }

            // Cek apakah email sudah terdaftar
            $existingUser = $this->users->findOne(['email' => $email]);
            if ($existingUser) {
                error_log("Email already exists: $email");
                $this->jsonResponse('error', 'Email sudah terdaftar');
            }

            // Generate username unik
            $username = $this->generateUsername($name, $email);

            // Hash password
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            // Data user baru
            $userData = [
                'username' => $username,
                'name' => $name,
                'email' => $email,
                'password' => $hashedPassword,
                'created_at' => new UTCDateTime(),
                'updated_at' => new UTCDateTime(),
                'role' => 'user',
                'status' => 'active',
                'profile' => [
                    'avatar' => null,
                    'bio' => null,
                    'location' => null
                ]
            ];

            error_log("Attempting to insert new user with username: " . $username);

            // Simpan user baru
            $result = $this->users->insertOne($userData);

            if ($result->getInsertedCount()) {
                error_log("User registered successfully with ID: " . $result->getInsertedId());
                
                // Set session untuk login otomatis
                $_SESSION['user_id'] = (string)$result->getInsertedId();
                $_SESSION['user_email'] = $email;
                $_SESSION['user_name'] = $name;
                $_SESSION['username'] = $username;

                $this->jsonResponse('success', 'Registrasi berhasil', [
                    'redirect' => '/dashboard'
                ]);
            } else {
                error_log("Failed to insert user");
                throw new \Exception("Gagal menyimpan data user");
            }
        } catch (\Exception $e) {
            error_log("Registration error: " . $e->getMessage() . "\n" . $e->getTraceAsString());
            $this->jsonResponse('error', 'Terjadi kesalahan saat mendaftar: ' . $e->getMessage());
        }
    }

    public function logout() {
        try {
            // Hapus remember token dari database jika ada
            if (isset($_SESSION['user_id'])) {
                $this->users->updateOne(
                    ['_id' => new ObjectId($_SESSION['user_id'])],
                    ['$unset' => ['remember_token' => '']]
                );
            }
            
            // Hapus semua data session
            session_destroy();
            
            // Hapus cookie remember me
            if (isset($_COOKIE['remember_token'])) {
                setcookie('remember_token', '', time() - 3600, '/');
            }
            
            // Redirect ke home
            header('Location: /');
            exit();
        } catch (\Exception $e) {
            error_log("Logout error: " . $e->getMessage() . "\n" . $e->getTraceAsString());
            header('Location: /');
            exit();
        }
    }

    public function showRegisterForm() {
        if (isset($_SESSION['user_id'])) {
            header('Location: /dashboard');
            exit;
        }
        require_once __DIR__ . '/../../views/auth/register.php';
    }

    public function loginForm() {
        try {
            if (isset($_SESSION['user_id'])) {
                header('Location: /dashboard');
                exit;
            }
            
            $registered = isset($_GET['registered']) ? true : false;
            require_once __DIR__ . '/../../views/auth/login.php';
        } catch (\Exception $e) {
            error_log("Error showing login form: " . $e->getMessage());
            error_log("Stack trace: " . $e->getTraceAsString());
            
            // Redirect to error page
            header('Location: /error');
            exit;
        }
    }
} 