<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserFilterRequest;
use App\Services\UserService;

class UserController extends Controller
{
    public function __construct(
        private readonly UserService $userService
    ) {}

    public function index(UserFilterRequest $request)
    {
        $transactions = $this->userService->getFilteredTransactions($request->validated());
        
        return response()->json([
            'status' => 'success',
            'data' => $transactions
        ]);
    }
}
