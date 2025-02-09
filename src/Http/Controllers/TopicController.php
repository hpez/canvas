<?php

namespace Canvas\Http\Controllers;

use Exception;
use Canvas\Topic;
use Ramsey\Uuid\Uuid;
use Illuminate\Validation\Rule;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;

class TopicController extends Controller
{
    /**
     * Get all the topics.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        return response()->json(Topic::withCount('posts')
            ->orderByDesc('created_at')
            ->get());
    }

    /**
     * Get a single topic or return a UUID to create one.
     *
     * @param null $id
     * @return JsonResponse
     * @throws Exception
     */
    public function show($id = null): JsonResponse
    {
        if ($id === 'create') {
            return response()->json(Topic::make([
                'id' => Uuid::uuid4(),
            ]));
        } else {
            $topic = Topic::find($id);

            if ($topic) {
                return response()->json($topic);
            } else {
                return response()->json(null, 301);
            }
        }
    }

    /**
     * Create or update a topic.
     *
     * @param string $id
     * @return JsonResponse
     */
    public function store(string $id): JsonResponse
    {
        $data = [
            'id'   => request('id'),
            'name' => request('name'),
            'slug' => request('slug'),
        ];

        $messages = [
            'required' => __('canvas::validation.required'),
            'unique'   => __('canvas::validation.unique'),
        ];

        validator($data, [
            'name' => 'required',
            'slug' => [
                'required',
                'alpha_dash',
                Rule::unique('canvas_topics', 'slug')->ignore($id),
            ],
        ], $messages)->validate();

        $topic = $id !== 'create' ? Topic::find($id) : new Topic(['id' => request('id')]);

        $topic->fill($data);
        $topic->save();

        return response()->json($topic->refresh());
    }

    /**
     * Delete a topic.
     *
     * @param string $id
     * @return void
     */
    public function destroy(string $id)
    {
        $topic = Topic::find($id);

        if ($topic) {
            $topic->delete();
        }
    }
}
