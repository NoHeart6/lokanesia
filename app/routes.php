<?php

// Home Route
$router->get('/', [\App\Controllers\HomeController::class, 'index']);

// Auth Routes
$router->get('/login', [\App\Controllers\AuthController::class, 'loginPage']);
$router->post('/login', [\App\Controllers\AuthController::class, 'login']);
$router->get('/register', [\App\Controllers\AuthController::class, 'showRegisterForm']);
$router->post('/register', [\App\Controllers\AuthController::class, 'register']);
$router->get('/logout', [\App\Controllers\AuthController::class, 'logout']);

// Dashboard Routes
$router->get('/dashboard', [\App\Controllers\DashboardController::class, 'index']);
$router->get('/dashboard/profile', [\App\Controllers\DashboardController::class, 'profile']);
$router->post('/dashboard/update-profile', [\App\Controllers\DashboardController::class, 'updateProfile']);
$router->post('/dashboard/upload-avatar', [\App\Controllers\DashboardController::class, 'uploadAvatar']);

// Itineraries Routes
$router->get('/dashboard/itineraries', [\App\Controllers\DashboardController::class, 'itineraries']);
$router->post('/api/itineraries/create', [\App\Controllers\DashboardController::class, 'createItinerary']);
$router->get('/api/itineraries/{id}', [\App\Controllers\DashboardController::class, 'getItinerary']);
$router->post('/api/itineraries/{id}/update', [\App\Controllers\DashboardController::class, 'updateItinerary']);
$router->post('/api/itineraries/{id}/delete', [\App\Controllers\DashboardController::class, 'deleteItinerary']);

// Tourist Spots Routes
$router->get('/tourist-spots', [\App\Controllers\TouristSpotController::class, 'index']);
$router->get('/tourist-spot/{id}', [\App\Controllers\TouristSpotController::class, 'show']);
$router->get('/api/tourist-spots/search', [\App\Controllers\TouristSpotController::class, 'search']);
$router->post('/api/tourist-spots/{id}/save', [\App\Controllers\TouristSpotController::class, 'saveSpot']);
$router->post('/api/tourist-spots/{id}/review', [\App\Controllers\TouristSpotController::class, 'addReview']);
$router->post('/api/tourist-spots/{id}/itinerary', [\App\Controllers\TouristSpotController::class, 'addToItinerary']); 