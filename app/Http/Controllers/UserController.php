<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Http\Resources\UserResource;

class UserController extends Controller
{

    /**
     * Create the controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->authorizeResource(User::class, 'user');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, User $user)
    {

        $users = $user->query()
            ->with('role')
            ->withCount('aiChats')
            ->latest()
            ->paginate($request->input('perPage', 15));

        return Inertia::render('User', [
            'items' => UserResource::collection($users),
            'roles' => Role::all(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, User $user)
    {

        $request->validate([
            'name' =>'required|max:255',
            'email' =>'required|email|unique:users,email',
            'chat_limit' => "required|min:0|max:30",
            'password' => 'required|min:8|regex:/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{6,}$/',
        ], [
            'password.regex' => 'Password must contain at least one upper case, lower case, number, and special character'
        ] ,[
            'name' => 'Name',
            'email' => 'Email',
            'chat_limit' => 'Chat Limit',
            'password' => 'Password',
        ]);

        $user->create([
            'name' => $request->name,
            'email' => $request->email,
            'chat_limit' => $request->input('chat_limit', 0),
            'role_id' => $request->role_id,
            'password' => bcrypt($request->password),
        ]);

        return redirect()->route('user.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' =>'required|max:255',
            'email' => "required|email|unique:users,email,{$request->id}",
            'chat_limit' => "required|min:0|max:30",
            'password' => 'nullable|min:8|regex:/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{6,}$/',
        ], [
            'password.regex' => 'Password must contain at least one upper case, lower case, number, and special character'
        ] ,[
            'name' => 'Name',
            'email' => 'Email',
            'chat_limit' => 'Chat Limit',
            'password' => 'Password',
        ]);

        $user->fill([
            'name' => $request->name,
            'email' => $request->email,
            'chat_limit' => $request->chat_limit,
            'role_id' => $request->role_id,
        ]);

        if ($request->input('password')) {
            $user->password = bcrypt($request->input('password'));
        }

        $user->save();

        return redirect()->route('user.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('user.index');
    }
}
