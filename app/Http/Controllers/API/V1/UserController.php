<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class UserController extends Controller
{
    /**
     * login as user
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
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

        return ["message" => $user->createToken($request->device_name)->plainTextToken];
    }

    /**
     * signup new user
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function signup(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email,',
            'password' => 'required|min:8',
        ]);

        $new_user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return new UserResource($new_user);
    }

    /**
     * logout user
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'logged out succefully']);
    }

    /**
     * return the logged in user
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        return UserResource::make($request->user());
    }

    /**
     * Update the logged in users informations
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $request->validate([
            'name'     => 'nullable|string',
            'email'    => 'nullable|email|unique:users,email,' . $request->user()->id,
            'password' => 'nullable|string|min:8',
        ]);

        $update_result = $request->user()->update([
            'name' => $request->name ?? $request->user()->name,
            'email' => $request->email ?? $request->user()->email,
            'password' => $request->password ? Hash::make($request->password) : $request->user()->password,
        ]);

        return $update_result ? UserResource::make($request->user()) : response()->json(['message' => 'an error occured']);;
    }

    /**
     * Delete user
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $this->logout($request);

        $delete_result = $request->user()->delete();
        return $delete_result ? UserResource::make($request->user()) : response()->json(['message' => 'an error occured']);
    }
}
