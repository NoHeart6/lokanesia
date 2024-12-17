<?php

namespace App\Middleware;

use App\Core\Request;
use App\Core\Response;
use App\Database\Database;

class AuthMiddleware {
    public function __invoke(Request $request, Response $response) {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        // Cek apakah ini request API
        $isApiRequest = strpos($request->getPath(), '/api/') === 0;
        
        // Cek session user
        if (!isset($_SESSION['user_id'])) {
            // Cek remember token
            if (isset($_COOKIE['remember_token'])) {
                $token = $_COOKIE['remember_token'];
                
                // Cari user dengan token tersebut
                $db = Database::getInstance();
                $users = $db->getCollection('users');
                $user = $users->findOne(['remember_token' => $token]);
                
                if ($user) {
                    // Set session
                    $_SESSION['user_id'] = (string)$user->_id;
                    $_SESSION['user_email'] = $user->email;
                    $_SESSION['user_name'] = $user->name;
                    return true;
                }
            }
            
            // Handle unauthorized access
            if ($isApiRequest || $request->isAjax()) {
                $response->setStatus(401);
                $response->json([
                    'status' => 'error',
                    'message' => 'Silakan login terlebih dahulu'
                ]);
                return false;
            } else {
                header('Location: /login');
                exit;
            }
        }
        
        return true;
    }
} 