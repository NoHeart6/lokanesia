<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Request;
use App\Core\Response;
use MongoDB\BSON\ObjectId;
use MongoDB\BSON\UTCDateTime;

class ItineraryController extends Controller {
    public function index(Request $request, Response $response): void {
        $page = (int) $request->getQuery('page', 1);
        $perPage = (int) $request->getQuery('per_page', 10);
        $sortBy = $request->getQuery('sort_by', 'created_at');
        $sortOrder = $request->getQuery('sort_order', 'desc');

        $filter = ['user_id' => new ObjectId($request->user->_id)];
        $sort = [$sortBy => $sortOrder === 'desc' ? -1 : 1];

        $total = $this->getCollection('itineraries')->countDocuments($filter);
        $itineraries = $this->getCollection('itineraries')
            ->find(
                $filter,
                [
                    'skip' => ($page - 1) * $perPage,
                    'limit' => $perPage,
                    'sort' => $sort
                ]
            )->toArray();

        // Get tourist spot details for each itinerary
        foreach ($itineraries as &$itinerary) {
            $spots = [];
            foreach ($itinerary->spots as $spot) {
                $touristSpot = $this->getCollection('tourist_spots')->findOne(
                    ['_id' => $spot->tourist_spot_id],
                    ['projection' => ['name' => 1, 'address' => 1, 'location' => 1]]
                );
                if ($touristSpot) {
                    $spots[] = [
                        'tourist_spot' => $touristSpot,
                        'visit_date' => $spot->visit_date,
                        'notes' => $spot->notes ?? null
                    ];
                }
            }
            $itinerary->spots = $spots;
        }

        $response->json([
            'data' => $itineraries,
            'meta' => [
                'current_page' => $page,
                'per_page' => $perPage,
                'total' => $total,
                'total_pages' => ceil($total / $perPage)
            ]
        ]);
    }

    public function show(Request $request, Response $response): void {
        $id = $request->getParam('id');

        try {
            $itinerary = $this->getCollection('itineraries')->findOne([
                '_id' => new ObjectId($id),
                'user_id' => new ObjectId($request->user->_id)
            ]);

            if (!$itinerary) {
                $response->notFound('Itinerary not found');
                return;
            }

            // Get tourist spot details
            $spots = [];
            foreach ($itinerary->spots as $spot) {
                $touristSpot = $this->getCollection('tourist_spots')->findOne(
                    ['_id' => $spot->tourist_spot_id],
                    ['projection' => ['name' => 1, 'address' => 1, 'location' => 1, 'description' => 1]]
                );
                if ($touristSpot) {
                    $spots[] = [
                        'tourist_spot' => $touristSpot,
                        'visit_date' => $spot->visit_date,
                        'notes' => $spot->notes ?? null
                    ];
                }
            }
            $itinerary->spots = $spots;

            $response->json(['data' => $itinerary]);

        } catch (\Exception $e) {
            $response->notFound('Itinerary not found');
        }
    }

    public function store(Request $request, Response $response): void {
        $this->validateJson();

        $rules = [
            'title' => 'required|min:3',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            'spots' => 'required|array'
        ];

        $data = $request->getBody();
        $errors = $this->validate($data, $rules);

        if (!empty($errors)) {
            $response->setStatus(422);
            $response->json(['errors' => $errors]);
            return;
        }

        // Validate dates
        $startDate = strtotime($data['start_date']);
        $endDate = strtotime($data['end_date']);

        if ($endDate < $startDate) {
            $response->setStatus(422);
            $response->json(['errors' => ['end_date' => 'End date must be after start date']]);
            return;
        }

        // Validate and format spots
        $spots = [];
        foreach ($data['spots'] as $spot) {
            if (!isset($spot['tourist_spot_id']) || !isset($spot['visit_date'])) {
                $response->setStatus(422);
                $response->json(['errors' => ['spots' => 'Invalid spot data']]);
                return;
            }

            try {
                // Check if tourist spot exists
                $touristSpot = $this->getCollection('tourist_spots')->findOne([
                    '_id' => new ObjectId($spot['tourist_spot_id'])
                ]);

                if (!$touristSpot) {
                    $response->setStatus(422);
                    $response->json(['errors' => ['spots' => 'Tourist spot not found']]);
                    return;
                }

                $visitDate = strtotime($spot['visit_date']);
                if ($visitDate < $startDate || $visitDate > $endDate) {
                    $response->setStatus(422);
                    $response->json(['errors' => ['spots' => 'Visit date must be within itinerary dates']]);
                    return;
                }

                $spots[] = [
                    'tourist_spot_id' => new ObjectId($spot['tourist_spot_id']),
                    'visit_date' => new UTCDateTime($visitDate * 1000),
                    'notes' => $spot['notes'] ?? null
                ];
            } catch (\Exception $e) {
                $response->setStatus(422);
                $response->json(['errors' => ['spots' => 'Invalid tourist spot ID']]);
                return;
            }
        }

        $itineraryData = [
            'user_id' => new ObjectId($request->user->_id),
            'title' => $data['title'],
            'description' => $data['description'] ?? null,
            'start_date' => new UTCDateTime($startDate * 1000),
            'end_date' => new UTCDateTime($endDate * 1000),
            'spots' => $spots,
            'created_at' => new UTCDateTime(),
            'updated_at' => new UTCDateTime()
        ];

        $result = $this->getCollection('itineraries')->insertOne($itineraryData);

        if (!$result->getInsertedId()) {
            $response->serverError('Failed to create itinerary');
            return;
        }

        $itinerary = $this->getCollection('itineraries')->findOne([
            '_id' => $result->getInsertedId()
        ]);

        $response->setStatus(201);
        $response->json(['data' => $itinerary]);
    }

    public function update(Request $request, Response $response): void {
        $this->validateJson();
        $id = $request->getParam('id');

        $rules = [
            'title' => 'required|min:3',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            'spots' => 'required|array'
        ];

        $data = $request->getBody();
        $errors = $this->validate($data, $rules);

        if (!empty($errors)) {
            $response->setStatus(422);
            $response->json(['errors' => $errors]);
            return;
        }

        try {
            $itinerary = $this->getCollection('itineraries')->findOne([
                '_id' => new ObjectId($id),
                'user_id' => new ObjectId($request->user->_id)
            ]);

            if (!$itinerary) {
                $response->notFound('Itinerary not found');
                return;
            }

            // Validate dates
            $startDate = strtotime($data['start_date']);
            $endDate = strtotime($data['end_date']);

            if ($endDate < $startDate) {
                $response->setStatus(422);
                $response->json(['errors' => ['end_date' => 'End date must be after start date']]);
                return;
            }

            // Validate and format spots
            $spots = [];
            foreach ($data['spots'] as $spot) {
                if (!isset($spot['tourist_spot_id']) || !isset($spot['visit_date'])) {
                    $response->setStatus(422);
                    $response->json(['errors' => ['spots' => 'Invalid spot data']]);
                    return;
                }

                try {
                    // Check if tourist spot exists
                    $touristSpot = $this->getCollection('tourist_spots')->findOne([
                        '_id' => new ObjectId($spot['tourist_spot_id'])
                    ]);

                    if (!$touristSpot) {
                        $response->setStatus(422);
                        $response->json(['errors' => ['spots' => 'Tourist spot not found']]);
                        return;
                    }

                    $visitDate = strtotime($spot['visit_date']);
                    if ($visitDate < $startDate || $visitDate > $endDate) {
                        $response->setStatus(422);
                        $response->json(['errors' => ['spots' => 'Visit date must be within itinerary dates']]);
                        return;
                    }

                    $spots[] = [
                        'tourist_spot_id' => new ObjectId($spot['tourist_spot_id']),
                        'visit_date' => new UTCDateTime($visitDate * 1000),
                        'notes' => $spot['notes'] ?? null
                    ];
                } catch (\Exception $e) {
                    $response->setStatus(422);
                    $response->json(['errors' => ['spots' => 'Invalid tourist spot ID']]);
                    return;
                }
            }

            $updateData = [
                'title' => $data['title'],
                'description' => $data['description'] ?? null,
                'start_date' => new UTCDateTime($startDate * 1000),
                'end_date' => new UTCDateTime($endDate * 1000),
                'spots' => $spots,
                'updated_at' => new UTCDateTime()
            ];

            $result = $this->getCollection('itineraries')->updateOne(
                ['_id' => new ObjectId($id)],
                ['$set' => $updateData]
            );

            if ($result->getModifiedCount() === 0) {
                $response->serverError('Failed to update itinerary');
                return;
            }

            $updatedItinerary = $this->getCollection('itineraries')->findOne([
                '_id' => new ObjectId($id)
            ]);

            $response->json(['data' => $updatedItinerary]);

        } catch (\Exception $e) {
            $response->notFound('Itinerary not found');
        }
    }

    public function destroy(Request $request, Response $response): void {
        $id = $request->getParam('id');

        try {
            $itinerary = $this->getCollection('itineraries')->findOne([
                '_id' => new ObjectId($id),
                'user_id' => new ObjectId($request->user->_id)
            ]);

            if (!$itinerary) {
                $response->notFound('Itinerary not found');
                return;
            }

            $result = $this->getCollection('itineraries')->deleteOne([
                '_id' => new ObjectId($id)
            ]);

            if ($result->getDeletedCount() === 0) {
                $response->serverError('Failed to delete itinerary');
                return;
            }

            $response->json(['message' => 'Itinerary deleted successfully']);

        } catch (\Exception $e) {
            $response->notFound('Itinerary not found');
        }
    }
} 