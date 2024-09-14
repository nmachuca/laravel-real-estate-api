<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\LoginRequest;
use App\Models\User;
use App\Services\AuthService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    /**
     * Method registers new user
     * @param RegisterRequest $request
     * @return JsonResponse
     */
    public function register(RegisterRequest $request): JsonResponse
    {
        // validate request
        $validated = $request->validated();
        // register user in database
        $user = AuthService::addUser($validated);
        // sample log process
        Log::info('User {$user->name} registered successfully.', ['id' => $user->id, 'email' => $user->email]);
        // send response
        return $this->sendResponse(
            [
                'id' => $user->id,
                'email' => $user->email
            ],
            "User registered successfully",
            Response::HTTP_CREATED
        );
    }

    /**
     * Method creates token for the given user to be used for auth purposes
     * @param LoginRequest $request
     * @return JsonResponse
     */
    public function login(LoginRequest $request): JsonResponse
    {
        // validate request
        $validated = $request->validated();

        //get credentials
        $credentials = request(['email', 'password']);
        $user = User::where('email', $validated['email'])->first();

        // check credentials
        if (!Auth::attempt($credentials)) {
            return $this->sendError('Credentials not valid', [], Response::HTTP_UNAUTHORIZED);
        }
        // sample log process
        Log::info('User {$user->name} logged in successfully.', ['id' => $user->id, 'email' => $user->email]);

        return $this->sendResponse(
            [
                'type' => 'Bearer',
                'token' => $user->createToken(config('app.name'))->plainTextToken,
            ],
            "User logged in successfully"
        );
    }

}
