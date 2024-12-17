<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Request;
use App\Core\Response;
use MongoDB\BSON\ObjectId;
use MongoDB\BSON\UTCDateTime;

class ReviewController extends Controller {
    public function index(Request $request, Response $response): void {
        $spotId = $request->getParam('spotId');
        $page = (int) $request->getQuery('page', 1);
        $perPage = (int) $request->getQuery('per_page', 10);
        $sortBy = $request->getQuery('sort_by', 'created_at');
        $sortOrder = $request->getQuery('sort_order', 'desc');

        try {
            // Check if tourist spot exists
            $spot = $this->getCollection('tourist_spots')->findOne([
                '_id' => new ObjectId($spotId)
            ]);

            if (!$spot) {
                $response->notFound('Tourist spot not found');
                return;
            }

            $filter = ['tourist_spot_id' => new ObjectId($spotId)];
            $sort = [$sortBy => $sortOrder === 'desc' ? -1 : 1];

            $total = $this->getCollection('reviews')->countDocuments($filter);
            $reviews = $this->getCollection('reviews')
                ->find(
                    $filter,
                    [
                        'skip' => ($page - 1) * $perPage,
                        'limit' => $perPage,
                        'sort' => $sort
                    ]
                )->toArray();

            // Get user details for each review
            foreach ($reviews as &$review) {
                $user = $this->getCollection('users')->findOne(
                    ['_id' => $review->user_id],
                    ['projection' => ['name' => 1]]
                );
                $review->user_name = $user->name;
            }

            $response->json([
                'data' => $reviews,
                'meta' => [
                    'current_page' => $page,
                    'per_page' => $perPage,
                    'total' => $total,
                    'total_pages' => ceil($total / $perPage)
                ]
            ]);

        } catch (\Exception $e) {
            $response->notFound('Tourist spot not found');
        }
    }

    public function store(Request $request, Response $response): void {
        $this->validateJson();
        $spotId = $request->getParam('spotId');

        $rules = [
            'rating' => 'required|numeric',
            'comment' => 'required|min:10'
        ];

        $data = $request->getBody();
        $errors = $this->validate($data, $rules);

        if ($data['rating'] < 1 || $data['rating'] > 5) {
            $errors['rating'] = 'Rating must be between 1 and 5';
        }

        if (!empty($errors)) {
            $response->setStatus(422);
            $response->json(['errors' => $errors]);
            return;
        }

        try {
            // Check if tourist spot exists
            $spot = $this->getCollection('tourist_spots')->findOne([
                '_id' => new ObjectId($spotId)
            ]);

            if (!$spot) {
                $response->notFound('Tourist spot not found');
                return;
            }

            // Check if user has already reviewed this spot
            $existingReview = $this->getCollection('reviews')->findOne([
                'tourist_spot_id' => new ObjectId($spotId),
                'user_id' => new ObjectId($request->user->_id)
            ]);

            if ($existingReview) {
                $response->setStatus(422);
                $response->json(['error' => 'You have already reviewed this tourist spot']);
                return;
            }

            // Create review
            $reviewData = [
                'tourist_spot_id' => new ObjectId($spotId),
                'user_id' => new ObjectId($request->user->_id),
                'rating' => (float) $data['rating'],
                'comment' => $data['comment'],
                'created_at' => new UTCDateTime(),
                'updated_at' => new UTCDateTime()
            ];

            $result = $this->getCollection('reviews')->insertOne($reviewData);

            if (!$result->getInsertedId()) {
                $response->serverError('Failed to create review');
                return;
            }

            // Update tourist spot rating and review count
            $allReviews = $this->getCollection('reviews')->find([
                'tourist_spot_id' => new ObjectId($spotId)
            ])->toArray();

            $totalRating = 0;
            foreach ($allReviews as $review) {
                $totalRating += $review->rating;
            }

            $averageRating = $totalRating / count($allReviews);

            $this->getCollection('tourist_spots')->updateOne(
                ['_id' => new ObjectId($spotId)],
                [
                    '$set' => [
                        'rating' => $averageRating,
                        'review_count' => count($allReviews)
                    ]
                ]
            );

            $review = $this->getCollection('reviews')->findOne([
                '_id' => $result->getInsertedId()
            ]);

            $response->setStatus(201);
            $response->json(['data' => $review]);

        } catch (\Exception $e) {
            $response->notFound('Tourist spot not found');
        }
    }

    public function update(Request $request, Response $response): void {
        $this->validateJson();
        $id = $request->getParam('id');

        $rules = [
            'rating' => 'required|numeric',
            'comment' => 'required|min:10'
        ];

        $data = $request->getBody();
        $errors = $this->validate($data, $rules);

        if ($data['rating'] < 1 || $data['rating'] > 5) {
            $errors['rating'] = 'Rating must be between 1 and 5';
        }

        if (!empty($errors)) {
            $response->setStatus(422);
            $response->json(['errors' => $errors]);
            return;
        }

        try {
            $review = $this->getCollection('reviews')->findOne([
                '_id' => new ObjectId($id)
            ]);

            if (!$review) {
                $response->notFound('Review not found');
                return;
            }

            // Check if user owns the review
            if ((string) $review->user_id !== (string) $request->user->_id) {
                $response->forbidden('You are not authorized to update this review');
                return;
            }

            $updateData = [
                'rating' => (float) $data['rating'],
                'comment' => $data['comment'],
                'updated_at' => new UTCDateTime()
            ];

            $result = $this->getCollection('reviews')->updateOne(
                ['_id' => new ObjectId($id)],
                ['$set' => $updateData]
            );

            if ($result->getModifiedCount() === 0) {
                $response->serverError('Failed to update review');
                return;
            }

            // Update tourist spot rating
            $allReviews = $this->getCollection('reviews')->find([
                'tourist_spot_id' => $review->tourist_spot_id
            ])->toArray();

            $totalRating = 0;
            foreach ($allReviews as $review) {
                $totalRating += $review->rating;
            }

            $averageRating = $totalRating / count($allReviews);

            $this->getCollection('tourist_spots')->updateOne(
                ['_id' => $review->tourist_spot_id],
                ['$set' => ['rating' => $averageRating]]
            );

            $updatedReview = $this->getCollection('reviews')->findOne([
                '_id' => new ObjectId($id)
            ]);

            $response->json(['data' => $updatedReview]);

        } catch (\Exception $e) {
            $response->notFound('Review not found');
        }
    }

    public function destroy(Request $request, Response $response): void {
        $id = $request->getParam('id');

        try {
            $review = $this->getCollection('reviews')->findOne([
                '_id' => new ObjectId($id)
            ]);

            if (!$review) {
                $response->notFound('Review not found');
                return;
            }

            // Check if user owns the review
            if ((string) $review->user_id !== (string) $request->user->_id) {
                $response->forbidden('You are not authorized to delete this review');
                return;
            }

            $result = $this->getCollection('reviews')->deleteOne([
                '_id' => new ObjectId($id)
            ]);

            if ($result->getDeletedCount() === 0) {
                $response->serverError('Failed to delete review');
                return;
            }

            // Update tourist spot rating and review count
            $allReviews = $this->getCollection('reviews')->find([
                'tourist_spot_id' => $review->tourist_spot_id
            ])->toArray();

            $reviewCount = count($allReviews);
            $averageRating = 0;

            if ($reviewCount > 0) {
                $totalRating = 0;
                foreach ($allReviews as $review) {
                    $totalRating += $review->rating;
                }
                $averageRating = $totalRating / $reviewCount;
            }

            $this->getCollection('tourist_spots')->updateOne(
                ['_id' => $review->tourist_spot_id],
                [
                    '$set' => [
                        'rating' => $averageRating,
                        'review_count' => $reviewCount
                    ]
                ]
            );

            $response->json(['message' => 'Review deleted successfully']);

        } catch (\Exception $e) {
            $response->notFound('Review not found');
        }
    }
} 