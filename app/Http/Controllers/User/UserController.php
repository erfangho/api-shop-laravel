<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index()
    {
        $users = User::all();
        return response()->json($users);
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

            if(!Str::contains($mail, '@erfan.ir')) {
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

            return response()->json($user);

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @return JsonResponse
     */
    public function show($id)
    {
        $user = User::query()->findOrFail($id);

        return response()->json($user);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param User $user
     * @return Response
     */
    public function edit(User $user)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param User $user
     * @return Response
     */
    public function destroy(Request $request)
    {
        try {
            $user = User::query()->findOrFail($request->input('id'));
            $user->delete();

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
