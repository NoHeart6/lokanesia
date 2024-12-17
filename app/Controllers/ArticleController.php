<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Request;
use App\Core\Response;
use MongoDB\BSON\ObjectId;
use MongoDB\BSON\UTCDateTime;

class ArticleController extends Controller {
    public function index(Request $request, Response $response): void {
        $page = (int) $request->getQuery('page', 1);
        $perPage = (int) $request->getQuery('per_page', 10);
        $category = $request->getQuery('category');
        $sortBy = $request->getQuery('sort_by', 'created_at');
        $sortOrder = $request->getQuery('sort_order', 'desc');

        $filter = [];
        if ($category) {
            $filter['category'] = $category;
        }

        $sort = [$sortBy => $sortOrder === 'desc' ? -1 : 1];

        $total = $this->getCollection('articles')->countDocuments($filter);
        $articles = $this->getCollection('articles')
            ->find(
                $filter,
                [
                    'skip' => ($page - 1) * $perPage,
                    'limit' => $perPage,
                    'sort' => $sort
                ]
            )->toArray();

        // Get author details for each article
        foreach ($articles as &$article) {
            $author = $this->getCollection('users')->findOne(
                ['_id' => $article->author_id],
                ['projection' => ['name' => 1]]
            );
            $article->author_name = $author->name;
        }

        $response->json([
            'data' => $articles,
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
            $article = $this->getCollection('articles')->findOne([
                '_id' => new ObjectId($id)
            ]);

            if (!$article) {
                $response->notFound('Article not found');
                return;
            }

            // Get author details
            $author = $this->getCollection('users')->findOne(
                ['_id' => $article->author_id],
                ['projection' => ['name' => 1]]
            );
            $article->author_name = $author->name;

            // Get related tourist spots
            if (isset($article->tourist_spots) && !empty($article->tourist_spots)) {
                $spots = [];
                foreach ($article->tourist_spots as $spotId) {
                    $spot = $this->getCollection('tourist_spots')->findOne(
                        ['_id' => $spotId],
                        ['projection' => ['name' => 1, 'address' => 1, 'thumbnail' => 1]]
                    );
                    if ($spot) {
                        $spots[] = $spot;
                    }
                }
                $article->tourist_spots = $spots;
            }

            $response->json(['data' => $article]);

        } catch (\Exception $e) {
            $response->notFound('Article not found');
        }
    }

    public function store(Request $request, Response $response): void {
        $this->validateJson();

        $rules = [
            'title' => 'required|min:3',
            'content' => 'required|min:100',
            'category' => 'required',
            'thumbnail' => 'required|url'
        ];

        $data = $request->getBody();
        $errors = $this->validate($data, $rules);

        if (!empty($errors)) {
            $response->setStatus(422);
            $response->json(['errors' => $errors]);
            return;
        }

        // Format tourist spots if provided
        $touristSpots = [];
        if (isset($data['tourist_spots']) && is_array($data['tourist_spots'])) {
            foreach ($data['tourist_spots'] as $spotId) {
                try {
                    $spot = $this->getCollection('tourist_spots')->findOne([
                        '_id' => new ObjectId($spotId)
                    ]);
                    if ($spot) {
                        $touristSpots[] = new ObjectId($spotId);
                    }
                } catch (\Exception $e) {
                    continue;
                }
            }
        }

        $articleData = [
            'title' => $data['title'],
            'slug' => $this->createSlug($data['title']),
            'content' => $data['content'],
            'category' => $data['category'],
            'thumbnail' => $data['thumbnail'],
            'tourist_spots' => $touristSpots,
            'author_id' => new ObjectId($request->user->_id),
            'created_at' => new UTCDateTime(),
            'updated_at' => new UTCDateTime()
        ];

        $result = $this->getCollection('articles')->insertOne($articleData);

        if (!$result->getInsertedId()) {
            $response->serverError('Failed to create article');
            return;
        }

        $article = $this->getCollection('articles')->findOne([
            '_id' => $result->getInsertedId()
        ]);

        $response->setStatus(201);
        $response->json(['data' => $article]);
    }

    public function update(Request $request, Response $response): void {
        $this->validateJson();
        $id = $request->getParam('id');

        $rules = [
            'title' => 'required|min:3',
            'content' => 'required|min:100',
            'category' => 'required',
            'thumbnail' => 'required|url'
        ];

        $data = $request->getBody();
        $errors = $this->validate($data, $rules);

        if (!empty($errors)) {
            $response->setStatus(422);
            $response->json(['errors' => $errors]);
            return;
        }

        try {
            $article = $this->getCollection('articles')->findOne([
                '_id' => new ObjectId($id)
            ]);

            if (!$article) {
                $response->notFound('Article not found');
                return;
            }

            // Check if user is the author
            if ((string) $article->author_id !== (string) $request->user->_id) {
                $response->forbidden('You are not authorized to update this article');
                return;
            }

            // Format tourist spots if provided
            $touristSpots = [];
            if (isset($data['tourist_spots']) && is_array($data['tourist_spots'])) {
                foreach ($data['tourist_spots'] as $spotId) {
                    try {
                        $spot = $this->getCollection('tourist_spots')->findOne([
                            '_id' => new ObjectId($spotId)
                        ]);
                        if ($spot) {
                            $touristSpots[] = new ObjectId($spotId);
                        }
                    } catch (\Exception $e) {
                        continue;
                    }
                }
            }

            $updateData = [
                'title' => $data['title'],
                'slug' => $this->createSlug($data['title']),
                'content' => $data['content'],
                'category' => $data['category'],
                'thumbnail' => $data['thumbnail'],
                'tourist_spots' => $touristSpots,
                'updated_at' => new UTCDateTime()
            ];

            $result = $this->getCollection('articles')->updateOne(
                ['_id' => new ObjectId($id)],
                ['$set' => $updateData]
            );

            if ($result->getModifiedCount() === 0) {
                $response->serverError('Failed to update article');
                return;
            }

            $updatedArticle = $this->getCollection('articles')->findOne([
                '_id' => new ObjectId($id)
            ]);

            $response->json(['data' => $updatedArticle]);

        } catch (\Exception $e) {
            $response->notFound('Article not found');
        }
    }

    public function destroy(Request $request, Response $response): void {
        $id = $request->getParam('id');

        try {
            $article = $this->getCollection('articles')->findOne([
                '_id' => new ObjectId($id)
            ]);

            if (!$article) {
                $response->notFound('Article not found');
                return;
            }

            // Check if user is the author
            if ((string) $article->author_id !== (string) $request->user->_id) {
                $response->forbidden('You are not authorized to delete this article');
                return;
            }

            $result = $this->getCollection('articles')->deleteOne([
                '_id' => new ObjectId($id)
            ]);

            if ($result->getDeletedCount() === 0) {
                $response->serverError('Failed to delete article');
                return;
            }

            $response->json(['message' => 'Article deleted successfully']);

        } catch (\Exception $e) {
            $response->notFound('Article not found');
        }
    }

    private function createSlug(string $title): string {
        // Transliterate non-ASCII characters
        $slug = iconv('UTF-8', 'ASCII//TRANSLIT', $title);
        // Convert to lowercase
        $slug = strtolower($slug);
        // Replace non-alphanumeric characters with hyphens
        $slug = preg_replace('/[^a-z0-9-]/', '-', $slug);
        // Remove multiple consecutive hyphens
        $slug = preg_replace('/-+/', '-', $slug);
        // Remove leading and trailing hyphens
        $slug = trim($slug, '-');

        return $slug;
    }
} 