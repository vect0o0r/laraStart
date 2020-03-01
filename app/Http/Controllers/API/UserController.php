<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Intervention\Image\Facades\Image;

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
    public function updateProfile(Request $request){
        $user = auth('api')->user();
        $data = $request->validate([
            'name' => 'required',
            'email' => 'required|unique:users,email,' . $user->id,
//            'photo' => 'sometimes|nullable|image',
            'password' => 'sometimes|nullable|string|min:6',
            'bio' => 'sometimes|nullable|string',
        ]);
        if ($request->password){
            $data['password'] = Hash::make($request->password);
        }else{
            $data['password'] = $user->password;
        }
        if ($request->photo != $user->photo){
            $ext =  '.'.explode('/',explode(':',substr($request->photo, 0,strpos($request->photo,';')))[1])[1];
            $name = time(). $ext;
            $path = public_path('storage/images/profile/').$name;
            $image = Image::make($request->photo)->resize(300, 200)->save($path);
            $data['photo'] = 'storage/images/profile/'.$name;

            $userPhoto = public_path($user->photo);
            if (file_exists($userPhoto)){
            unlink($userPhoto);
            }
        }
        $user->update($data);
        return response()->json(true,200);

    }

    public function index()
    {
        return User::latest()->paginate(5);
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
