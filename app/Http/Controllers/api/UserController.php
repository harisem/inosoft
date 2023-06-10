<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Services\UserService;
use App\Traits\ResponseAPI;
use Exception;
use Illuminate\Http\Request;

class UserController extends Controller
{
    use ResponseAPI;

    protected $UserService;

    public function __construct(UserService $UserService)
    {
        $this->UserService = $UserService;
    }

    public function register(Request $request)
    {
        try {
            $data = $this->UserService->storeUser($request);
            return $this->success('Account has been created.', $data, 201);
        } catch (Exception $e) {
            return $this->error($e->getMessage());
        }
    }

    public function login(Request $request)
    {
        try {
            $data = $this->UserService->logMeIn($request);
            if (!$data) return $this->error('Invalid credentials.', 401);
            return $this->respondWithToken('Login successfully.', $data);
        } catch (Exception $e) {
            return $this->error($e->getMessage());
        }
    }

    public function refresh()
    {
        try {
            $data = $this->UserService->refreshToken();
            if (!$data) return $this->error('Invalid credentials.', 401);
            return $this->respondWithToken('Login successfully.', $data);
        } catch (Exception $e) {
            return $this->error($e->getMessage());
        }
    }

    public function logout()
    {
        try {
            $data = $this->UserService->logMeOut();
            if ($data == false) return $this->error('Something went wrong', 500);
            return $this->success('You\'ve been logged out.', null);
        } catch (Exception $e) {
            return $this->error($e->getMessage());
        }
    }

    public function me()
    {
        try {
            $data = $this->UserService->getMyAccount();
            if (!$data) return $this->error('Forbidden access.', 403);
            return $data;
        } catch (Exception $e) {
            return $this->error($e->getMessage());
        }
    }
}
