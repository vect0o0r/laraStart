<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function __construct()
    {
        $this->middleware('auth:api');
    }


    public function profile()
    {
        $user = auth('api')->user();
        return $user;
    }

    public function index()
    {
        return User::latest()->paginate(10);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required',
            'email' => 'required|unique:users',
            'type' => 'required|string',
//            'photo' => 'required|image',
            'password' => 'required|string|min:6',
        ]);
        $data['password'] = Hash::make($request->password);
        return User::create($data);
//        return response()->json(true,$user);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $data = $request->validate([
            'name' => 'required',
            'email' => 'required|unique:users,email,' . $user->id,
            'type' => 'required|string',
//            'photo' => 'required|image',
            'password' => 'sometimes|->nullable|string|min:6',
        ]);
        $request->password ? $data['password'] = Hash::make($request->password) : '';
        $user->update($data);
        return response()->json('user Updated sucessfully', 200);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete($id);
        return response()->json('user deleted sucessfully', 200);
    }
}
