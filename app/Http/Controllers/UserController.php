<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::with('roles')->get();

        return view('users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('users.form', [
            'user'=>new User(),
            'roles'=>\Spatie\Permission\Models\Role::where('guard_name', 'web')->get(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data=$request->validate([
            'name'=>['required', 'string', 'max:255'],
            'email'=>['required', 'email', 'max:255', 'unique:users,email'],
            'role'=>['required', 'exists:roles,name'],
            'password'=>['required', 'confirmed', 'min:8'],
        ]);

        $role = $data['role'];
        unset($data['role']);

        $user = User::create($data);
        $user->syncRoles($role);

        return redirect()->route('users.index')->with('success', 'User berhasil dibuat.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        return view('users.form', [
            'user'=>$user,
            'roles'=>\Spatie\Permission\Models\Role::where('guard_name', 'web')->get(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
       $data = $request->validate([
        'name' => ['required', 'string', 'max:255'],
        'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
        'role' => ['required', 'exists:roles,name'],
        'password' => ['nullable', 'confirmed', 'min:8'],
        ]);

        $role = $data['role'];
        unset($data['role']);

        if (empty($data['password'])) {
            unset($data['password']);
        }

        $user->update($data);
        $user->syncRoles($role);

        return redirect()->route('users.index')->with('success', 'User berhasil diubah.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        if ($user->pengaduans()->exists()) {
        return back()->with('error', 'User masih memiliki pengaduan, jadi tidak dapat dihapus.');
        }

        $user->delete();

        return redirect()->route('users.index')->with('success', 'User berhasil dihapus.');
    }
}
