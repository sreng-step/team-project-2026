<?php

namespace App\Http\Controllers\Api;

use App\Events\PollVoteUpdated;
use App\Http\Controllers\Controller;
use App\Listeners\SendPollVote;
use Illuminate\Http\Request;

class VoteApiController extends Controller
{
    /**
     * Summary of store
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @OA\Post(
     *   tags={"Votes"},
     *   path="/api/votes",
     *  summary="Cast a vote in a poll",
     *  @OA\RequestBody(
     *    required=true,
     *   @OA\JsonContent(
     *     required={"poll_id","option"},
     *    @OA\Property(property="poll_id", type="integer", example=1),
     *    @OA\Property(property="option", type="string", example="Blue")
     *  )
     * ),
     *  @OA\Response(
     *    response=201,
     *    description="Vote cast successfully",
     *    @OA\JsonContent(ref="#/components/schemas/Vote")
     *  ),
     *  security={{"sanctum":{}}}
     * )
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'poll_id' => 'required|integer|exists:polls,id',
            'option' => 'required|string',
        ]);
        $poll = \App\Models\Poll::findOrFail($data['poll_id']);
        if (!in_array($data['option'], $poll->options)) {
            return response()->json(['message' => 'Invalid option selected.'], 400);
        }
        $vote = $poll->votes()->create([
            'user_id' => 1,
            'selected_option' => $data['option'],
        ]);
        PollVoteUpdated::dispatch($poll->fresh(['votes']));
        return response()->json($vote, 201);
    }
}
