<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UserDataController extends Controller
{
    /**
     * Check logged in user
     *
     * @param Request $request
     * @return void
     */
    public function checkLoggedInUser(Request $request)
    {
        $user = auth()->user();

        return response()->json([
            'error' => false,
            'user' => $user
        ]);
    }
}
