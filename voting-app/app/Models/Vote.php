<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Summary of Vote
 * @OA\Schema(
 *    schema="Vote",
 *    type="object",
 *    @OA\Property(property="id", type="integer", example=1),
 *    @OA\Property(property="poll_id", type="integer", example=1),
 *    @OA\Property(property="user_id", type="integer", example=1),
 *    @OA\Property(property="selected_option", type="string", example="Blue"),
 *    @OA\Property(property="created_at", type="string", format="date-time", example="2024-01-01T00:00:00Z"),
 *    @OA\Property(property="updated_at", type="string", format="date-time", example="2024-01-01T00:00:00Z")
 * )
 */
class Vote extends Model
{
    /** @use HasFactory<\Database\Factories\VoteFactory> */
    use HasFactory;
    protected $fillable = [
        'poll_id',
        'user_id',
        'selected_option',
    ];
    public function poll()
    {
        return $this->belongsTo(Poll::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
