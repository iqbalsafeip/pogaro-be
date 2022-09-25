<?php

namespace App\Http\Controllers;

use App\Models\Barber;
use App\Models\Prodi;
use App\Models\Profile;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\Exceptions\MissingAbilityException;

class UserController extends Controller {
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        return User::with('roles', 'prodi')->get();
    }

    public function mahasiswa() {
        return User::with('roles', 'prodi', 'profile')->whereHas('roles', function($q){
            $q->where('slug', '=', 'mhs');
        })->get();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        $creds = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
            'name' => 'nullable|string',
            'role' => 'nullable',
            'nama_barber' => 'nullable',
            'foto' => 'nullable'
        ]);

        $user = User::where('email', $creds['email'])->first();
        if ($user) {
            return response(['error' => 1, 'message' => 'user already exists'], 409);
        }

        $user = User::create([
            'email' => $creds['email'],
            'password' => Hash::make($creds['password']),
            'name' => $creds['name'],
            'role' => $creds['role']
        ]);

        if($creds['role'] == 'user'){
            $profile = new Profile();
            $profile->nama = $creds['name'];
            $profile->user_id = $user->id;
            $profile->save();
        } else {
            $barber = new Barber();
            $barber->user_id = $user->id;
            $barber->nama = $creds['name'];
            $barber->nama_barber = $creds['nama_barber'];
            $barber->status = 0;
            $imageName = time().'.'.$creds['foto']->getClientOriginalExtension();
            $creds['foto']->move(public_path('images'), $imageName);
            $barber->profile = $imageName;
            $barber->save();
        }

        return $user;
    }

    /**
     * Authenticate an user and dispatch token.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request) {
        $creds = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $creds['email'])->first();
        if (! $user || ! Hash::check($request->password, $user->password)) {
            return response(['error' => 1, 'message' => 'Email/ Password tidak sesuai'], 401);
        }




        $data = $user;
        


        return response(['error' => 0, 'data' => $data], 200);
    }

    public function barber(Request $request){
        $data = Barber::all();

        return response()->json([
            'data' => $data
        ]);
    }

    public function barberid($id){
        $data = Barber::findOrFail($id);
        $data['services'] = $data->services;
        return response()->json([
            'data' => $data
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \App\Models\User  $user
     */
    public function show(User $user) {
        if($user->role == 'user'){
            $res = Profile::where(['user_id'=> $user->id])->first();
        } else {
            $res = Barber::where(['user_id'=> $user->id])->first();
        }

        if(!$res){
            return response()->json([
                'error' => 1,
                'message' => 'not found'
            ]);
        }
        return $res;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return User
     *
     * @throws MissingAbilityException
     */
    public function update(Request $request, User $user) {
        $user->name = $request->name ?? $user->name;
        $user->email = $request->email ?? $user->email;
        $user->password = $request->password ? Hash::make($request->password) : $user->password;
        $user->email_verified_at = $request->email_verified_at ?? $user->email_verified_at;

        //check if the logged in user is updating it's own record

        $loggedInUser = $request->user();
        if ($loggedInUser->id == $user->id) {
            $user->update();
        } elseif ($loggedInUser->tokenCan('admin') || $loggedInUser->tokenCan('super-admin')) {
            $user->update();
        } else {
            throw new MissingAbilityException('Not Authorized');
        }

        return $user;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user) {
        $adminRole = Role::where('slug', 'admin')->first();
        $userRoles = $user->roles;

        if ($userRoles->contains($adminRole)) {
            //the current user is admin, then if there is only one admin - don't delete
            $numberOfAdmins = Role::where('slug', 'admin')->first()->users()->count();
            if (1 == $numberOfAdmins) {
                return response(['error' => 1, 'message' => 'Create another admin before deleting this only admin user'], 409);
            }
        }

        $user->delete();

        return response(['error' => 0, 'message' => 'user deleted']);
    }

    /**
     * Return Auth user
     *
     * @param  Request  $request
     * @return mixed
     */
    public function me(Request $request) {
        $user = $request->user();
        $user['roles'] = $user->roles;
        if($user->prodi){
            $user['prodi'] = $user->prodi;
        }

        if($user->profile){
            $user['name'] = $user->profile->nmmhs;
        }
        return $user;
    }
}
