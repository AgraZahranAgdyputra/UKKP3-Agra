<?php

namespace App\Http\Controllers;

use App\Models\Pengaduan;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PengaduanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user=auth()->user();

        $query=Pengaduan::with(['user', 'category'])->latest();

        if($user->hasRole('customer')){
            $query->where('user_id', $user->id);
        }

        return view('pengaduans.index', [
            'pengaduans'=>$query->get(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('pengaduans.form', [
            'pengaduan'=>new Pengaduan(),
            'categories'=>Category::orderBy('name')->get(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated=$request->validate([
            'category_id'=>['required', 'exists:categories,id'],
            'upload_file' => ['nullable', 'array', 'max:10'],
            'upload_file.*' => ['file', 'mimes:pdf,jpg,jpeg,png', 'max:5120'],
            'title'=>['required', 'string', 'max:255'],
            'description'=>['required', 'string', 'max:255'],
            'location'=>['nullable', 'string', 'max:255'],
        ]);

        $files = $validated['upload_file'] ?? [];
        unset($validated['upload_file']);

        $validated['upload_file'] = '';

        $pengaduan = $request->user()->pengaduans()->create($validated);

        foreach ($files as $file) {
            $file->store("pengaduan/{$pengaduan->id}", 'public');
        }

        return redirect()->route('pengaduans.index')->with('success', 'oke boskyuh');
    }

    /**
     * Display the specified resource.
     */
    public function show(Pengaduan $pengaduan)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Pengaduan $pengaduan)
    {
        $this->ensureCanEdit($pengaduan);

        return view('pengaduans.form', [
            'pengaduan'=>$pengaduan,
            'categories'=>Category::orderBy('name')->get(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Pengaduan $pengaduan)
    {
        $this->ensureCanEdit($pengaduan);

        $validated=$request->validate([
            'category_id'=>['required', 'exists:categories,id'],
            'upload_file' => ['nullable', 'array', 'max:10'],
            'upload_file.*' => ['file', 'mimes:pdf,jpg,jpeg,png', 'max:5120'],
            'title'=>['required', 'string', 'max:255'],
            'description'=>['required', 'string', 'max:255'],
            'location'=>['nullable', 'string', 'max:255'],
        ]);

        $files = $validated['upload_file'] ?? [];
        unset($validated['upload_file']);

        $pengaduan->update($validated);

        foreach ($files as $file) {
            $file->store("pengaduan/{$pengaduan->id}", 'public');
        }

        return redirect()->route('pengaduans.index')->with('success', 'dah');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Pengaduan $pengaduan)
    {
        $pengaduan->delete();

        return redirect()->route('pengaduans.index')->with('success', 'khpus kok');
    }

    public function ensureCanEdit(Pengaduan $pengaduan): void
    {
        $user=auth()->user();

        abort_unless(
            $user->hasRole('admin')
            || ($user->hasRole('customer') && $pengaduan->user_id === $user->id),
            403
        );
    }

    public function files(Pengaduan $pengaduan)
    {
        $this->ensureCanView($pengaduan);

        $files = Storage::disk('public')->files("pengaduan/{$pengaduan->id}");

        if ($pengaduan->upload_file) {
            array_unshift($files, $pengaduan->upload_file);
        }

        return view('pengaduans.files', compact('pengaduan', 'files'));
    }

    private function ensureCanView(Pengaduan $pengaduan): void
    {
        $user = auth()->user();

        abort_unless(
            $user->hasRole('admin')
            || $user->hasRole('staff')
            || ($user->hasRole('customer') && $pengaduan->user_id === $user->id),
            403
        );
    }
}
