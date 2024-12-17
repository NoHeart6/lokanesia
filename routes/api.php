<?php

use App\Controllers\AuthController;
use App\Controllers\TouristSpotController;
use App\Controllers\ReviewController;
use App\Controllers\ItineraryController;
use App\Controllers\ArticleController;
use App\Middleware\AuthMiddleware;

// Auth routes
$router->post('/api/auth/login', [AuthController::class, 'login']);
$router->post('/api/auth/register', [AuthController::class, 'register']);
$router->post('/api/auth/forgot-password', [AuthController::class, 'forgotPassword']);
$router->post('/api/auth/reset-password', [AuthController::class, 'resetPassword']);

// Protected auth routes
$router->get('/api/auth/me', [AuthController::class, 'me'], ['auth']);
$router->put('/api/auth/profile', [AuthController::class, 'updateProfile'], ['auth']);
$router->put('/api/auth/password', [AuthController::class, 'changePassword'], ['auth']);

// Tourist spots routes
$router->get('/api/tourist-spots', [TouristSpotController::class, 'index']);
$router->get('/api/tourist-spots/{id}', [TouristSpotController::class, 'show']);
$router->get('/api/tourist-spots/nearby', [TouristSpotController::class, 'nearby']);
$router->get('/api/tourist-spots/search', [TouristSpotController::class, 'search']);
$router->post('/api/tourist-spots', [TouristSpotController::class, 'store'], ['auth']);
$router->put('/api/tourist-spots/{id}', [TouristSpotController::class, 'update'], ['auth']);
$router->delete('/api/tourist-spots/{id}', [TouristSpotController::class, 'destroy'], ['auth']);

// Reviews routes
$router->get('/api/tourist-spots/{spotId}/reviews', [ReviewController::class, 'index']);
$router->post('/api/tourist-spots/{spotId}/reviews', [ReviewController::class, 'store'], ['auth']);
$router->put('/api/reviews/{id}', [ReviewController::class, 'update'], ['auth']);
$router->delete('/api/reviews/{id}', [ReviewController::class, 'destroy'], ['auth']);

// Itinerary routes
$router->get('/api/itineraries', [ItineraryController::class, 'index'], ['auth']);
$router->get('/api/itineraries/{id}', [ItineraryController::class, 'show'], ['auth']);
$router->post('/api/itineraries', [ItineraryController::class, 'store'], ['auth']);
$router->put('/api/itineraries/{id}', [ItineraryController::class, 'update'], ['auth']);
$router->delete('/api/itineraries/{id}', [ItineraryController::class, 'destroy'], ['auth']);

// Articles routes
$router->get('/api/articles', [ArticleController::class, 'index']);
$router->get('/api/articles/{id}', [ArticleController::class, 'show']);
$router->post('/api/articles', [ArticleController::class, 'store'], ['auth']);
$router->put('/api/articles/{id}', [ArticleController::class, 'update'], ['auth']);
$router->delete('/api/articles/{id}', [ArticleController::class, 'destroy'], ['auth']);

// Register middlewares
$router->addMiddleware('auth', new AuthMiddleware()); 