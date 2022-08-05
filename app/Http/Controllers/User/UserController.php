<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index()
    {
        $current_user = Auth::user();
        if($current_user['is_admin']){
            $users = User::all();
            return response()->json($users, ResponseAlias::HTTP_OK);
        } else {
            return response()->json(['message' => 'You are not authorized to access this resource'], ResponseAlias::HTTP_UNAUTHORIZED);
        }
    }

    /**
     * login.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            $token = $user->createToken('MyApp')->plainTextToken;
            return response()->json(['token' => $token], ResponseAlias::HTTP_OK);
        } else {
            return response()->json(['error' => 'Unauthorised'], ResponseAlias::HTTP_UNAUTHORIZED);
        }
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return JsonResponse
     */
    public function signUp(Request $request)
    {
        try {
            $mail = $request->input('email');

            if(!$mail == 'admin@admin.ir') {
                $is_admin = false;
            } else {
                $is_admin = true;
            }

            $user = User::create([
                'name' => $request->input('name'),
                'email' => $mail,
                'is_admin' => $is_admin,
                'credit' => 0,
                'password' => Hash::make($request->input('password')),
                'phone' => $request->input('phone'),
                'city' => $request->input('city'),
                'address' => $request->input('address'),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);

            return response()->json(['user' => $user, 'token' => $user->createToken('MyApp')->plainTextToken], ResponseAlias::HTTP_OK);

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], ResponseAlias::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function profile()
    {
        $user = Auth::user();
        return response()->json(['user' => $user], ResponseAlias::HTTP_OK);
    }
    /**
     * Display the specified resource.
     *
     * @return JsonResponse
     */
    public function show($id)
    {
        $current_user = Auth::user();
        if($current_user['is_admin']) {
            $user = User::find($id);
            return response()->json($user, ResponseAlias::HTTP_OK);
        } else {
            return response()->json(['error' => 'Unauthorised'], ResponseAlias::HTTP_UNAUTHORIZED);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     */
    public function destroy($id)
    {
        try {
            $current_user = Auth::user();
            if($current_user['is_admin']) {
                $user = User::find($id);
                $user->delete();
                return response()->json(['message' => 'User deleted successfully'], ResponseAlias::HTTP_OK);
            } else {
                return response()->json(['error' => 'Unauthorised'], ResponseAlias::HTTP_UNAUTHORIZED);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], ResponseAlias::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
