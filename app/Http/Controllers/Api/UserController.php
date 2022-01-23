<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function index(){
        $userLogger = Auth::user();

        $users = User::where('id', '!=', $userLogger->id)->get();

        return response()->json([
            'users' => $users
        ], Response::HTTP_OK);
    }

    public function show(User $user) {
        return response()->json([
            'user' => $user
        ], Response::HTTP_OK);
    }

    public function me() {
        return response()->json([
            'me' => Auth::user()
        ], Response::HTTP_OK);
    }
}
