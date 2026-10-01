<?php

namespace App\Http\Controllers;

use App\Models\Alat;
use Illuminate\Http\Request;

class AlatController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $alats = Alat::latest()->get();

        if (request()->expectsJson()) {
            return response()->json($alats);
        }

        return view('alat.index', compact('alats'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('alat.index');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_alat' => ['required', 'string', 'max:100'],
            'tahun' => ['required', 'integer', 'min:1900', 'max:2100'],
            'merek' => ['required', 'string', 'max:60'],
            'lokasi' => ['required', 'string', 'max:10'],
        ]);

        $alat = Alat::create($validated);

        return response()->json($alat, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Alat $alat)
    {
        return response()->json($alat);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Alat $alat)
    {
        return view('alat.index', compact('alat'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Alat $alat)
    {
        $validated = $request->validate([
            'nama_alat' => ['required', 'string', 'max:100'],
            'tahun' => ['required', 'integer', 'min:1900', 'max:2100'],
            'merek' => ['required', 'string', 'max:60'],
            'lokasi' => ['required', 'string', 'max:10'],
        ]);

        $alat->update($validated);

        return response()->json($alat);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Alat $alat)
    {
        $alat->delete();

        return response()->json(['message' => 'Alat berhasil dihapus']);
    }
}
