<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use function Pest\Laravel\json;

class PollApiController extends Controller
{
    /**
     * @OA\Get(
     *   tags={"Polls"},
     *    path="/api/polls",
     *    summary="Get polls created by authenticated user",
     *    @OA\Response(
     *       response=200,
     *       description="Successful operation",
     *       @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/Poll"))
     *    ),
     *    security={{"sanctum":{}}}
     * )
     * @param Request $request
     */
    public function index(Request $request)
    {
        return $request->user()->polls;
    }
    /**
     * Create a poll.
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @OA\Post(
     *   tags={"Polls"},
     *    path="/api/polls",
     *    summary="Create a new poll",
     *    @OA\RequestBody(
     *       required=true,
     *       @OA\JsonContent(
     *          required={"question","options"},
     *          @OA\Property(property="question", type="string", maxLength=255),
     *          @OA\Property(property="options", type="array",
     *              @OA\Items(type="string", maxLength=100)
     *          )
     *       )
     *    ),
     *    @OA\Response(
     *       response=201,
     *       description="Poll created successfully",
     *       @OA\JsonContent(ref="#/components/schemas/Poll")
     *    ),
     *    security={{"sanctum":{}}}
     * )
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'question' => 'required|string|max:255',
            'options' => 'required|array|min:2',
            'options.*' => 'required|string|max:100',
        ]);

        $poll = $request->user()->polls()->create([
            'question' => $data['question'],
            'options' => json_encode($data['options']),
        ]);

        return response()->json($poll, 201);
    }

    /**
     * Summary of show
     * @param \App\Models\Poll $poll
     * @return array{poll: \App\Models\Poll, totalVotes: int}
     * @OA\Get(
     *   tags={"Polls"},
     *     path="/api/polls/{poll}",
     *     summary="Get details of a specific poll",
     *     @OA\Parameter(
     *         name="poll",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *       response=200,
     *       description="Successful operation",
     *       @OA\JsonContent(
     *         @OA\Property(property="poll", ref="#/components/schemas/Poll"),
     *         @OA\Property(property="totalVotes", type="integer", example=10)
     *       )
     *    ),
     * security={{"sanctum":{}}}
     * )
     */
    public function show(\App\Models\Poll $poll)
    {
        return ['poll'=> $poll,'totalVotes'=>$poll->totalVotes];
    }
}
