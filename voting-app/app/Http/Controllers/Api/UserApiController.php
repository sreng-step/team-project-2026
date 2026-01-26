<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class UserApiController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/user",
     *     summary="Get authenticated user",
     *     @OA\Response(
     *        response=200,
     *        description="Successful operation",
     *        @OA\JsonContent(ref="#/components/schemas/User")
     *     ),
     *     security={{"sanctum":{}}}
     * )
     */
    public function getAuthenticatedUser(Request $request)
    {
        return $request->user();
    }
    /**
     * Summary of login
     * @param Request $request
     * @return array{token: string}
     * @OA\Post(
     *   tags={"Users"},
     *   path="/api/login",
     *   summary="Authenticate user and issue token",
     *   @OA\RequestBody(
     *     required=true,
     *     @OA\JsonContent(
     *       required={"email","password","device_name"},
     *       @OA\Property(property="email", type="string", format="email", example="user@example.com"),
     *             @OA\Property(property="password", type="string", format="password", example="password123"),
     *       @OA\Property(property="device_name", type="string", example="user-device"),
     *     )
     *   ),
     *   @OA\Response(
     *     response=200,
     *     description="Authentication successful",
     *     @OA\JsonContent(
     *       @OA\Property(property="token", type="string", example="1|aBcDeFgHiJkLmNoPqRsTuVwXyZ1234567890")
     *     )
     *   ),
     *   security={{}}
     * )
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
            'device_name' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        return ["token" => $user->createToken($request->device_name)->plainTextToken];
    }
}
