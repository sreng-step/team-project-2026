<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Poll model represents a poll created by a user.
 * @OA\Schema(
 *    schema="Poll",
 *    type="object",
 *    @OA\Property(property="id", type="integer", example=1),
 *    @OA\Property(property="question", type="string", example="What is your favorite color?"),
 *    @OA\Property(property="options", type="array",
 *        @OA\Items(type="string"), example={"Wednesday", "Blue", "Green", "Yellow"}
 *    ),
 *    @OA\Property(property="created_by", type="integer", example=1),
 *    @OA\Property(property="created_at", type="string", format="date-time", example="2024-01-01T00:00:00Z"),
 *    @OA\Property(property="updated_at", type="string", format="date-time", example="2024-01-01T00:00:00Z")
 * )
 */
class Poll extends Model
{
    /** @use HasFactory<\Database\Factories\PollFactory> */
    use HasFactory;
    protected $fillable = [
        'question',
        'options',
        'created_by',
    ];
    public function votes()
    {
        return $this->hasMany(Vote::class);
    }
    public int $totalVotes {
        get { return $this->votes()->count();}
    }
    public function casts()
    {
        return [
            'options' => 'array',
        ];
    }
}
