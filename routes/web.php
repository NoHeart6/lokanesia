<?php

use App\Controllers\HomeController;
use App\Controllers\AuthController;
use App\Controllers\DashboardController;
use App\Controllers\TouristSpotController;
use App\Controllers\WeatherController;
use App\Middleware\AuthMiddleware;

// Public routes
$router->get('/', [HomeController::class, 'index']);
$router->get('/search', [HomeController::class, 'search']);

// Tourist Spots routes
$router->get('/tourist-spots', [TouristSpotController::class, 'index'], ['auth']);
$router->get('/tourist-spots/search', [TouristSpotController::class, 'search'], ['auth']);
$router->get('/tourist-spot/{id}', [TouristSpotController::class, 'show'], ['auth']);
$router->get('/tourist-spots/create', [TouristSpotController::class, 'create'], ['auth']);
$router->post('/tourist-spots', [TouristSpotController::class, 'store'], ['auth']);
$router->get('/tourist-spots/{id}/edit', [TouristSpotController::class, 'edit'], ['auth']);
$router->post('/tourist-spots/{id}', [TouristSpotController::class, 'update'], ['auth']);
$router->delete('/tourist-spots/{id}', [TouristSpotController::class, 'destroy'], ['auth']);
$router->get('/tourist-spots/nearby', [TouristSpotController::class, 'nearby']);
$router->post('/tourist-spots/{id}/reviews', [TouristSpotController::class, 'addReview'], ['auth']);
$router->post('/tourist-spots/{id}/save', [TouristSpotController::class, 'saveSpot'], ['auth']);
$router->post('/tourist-spot/{id}/save', [TouristSpotController::class, 'saveSpot'], ['auth']);
$router->post('3fe4dce1ebca2119d114a7cd38fa4cc1/tourist-spot/{id}/review', [TouristSpotController::class, 'addReview'], ['auth']);
$router->post('/tourist-spot/{id}/itinerary', [TouristSpotController::class, 'addToItinerary'], ['auth']);

// Auth routes
$router->get('/login', [AuthController::class, 'loginPage']);
$router->post('/login', [AuthController::class, 'login']);
$router->get('/register', [AuthController::class, 'showRegisterForm']);
$router->post('/register', [AuthController::class, 'register']);
$router->get('/forgot-password', [AuthController::class, 'forgotPasswordPage']);
$router->get('/reset-password', [AuthController::class, 'resetPasswordPage']);
$router->get('/logout', [AuthController::class, 'logout']);

// Dashboard routes (protected)
$router->get('/dashboard', [DashboardController::class, 'index'], ['auth']);
$router->get('/dashboard/profile', [DashboardController::class, 'profile'], ['auth']);
$router->post('/dashboard/update-profile', [DashboardController::class, 'updateProfile'], ['auth']);
$router->get('/dashboard/settings', [DashboardController::class, 'settings'], ['auth']);
$router->get('/dashboard/itineraries', [DashboardController::class, 'itineraries'], ['auth']);
$router->get('/dashboard/reviews', [DashboardController::class, 'index'], ['auth']);
$router->get('/dashboard/notifications', [DashboardController::class, 'notifications'], ['auth']);

// Weather routes
$router->get('/api/weather', [WeatherController::class, 'getWeather'], ['auth']); // Untuk fallback
$router->post('/api/weather', [WeatherController::class, 'getWeather'], ['auth']); // Untuk menerima koordinat 