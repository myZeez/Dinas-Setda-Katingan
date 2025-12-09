<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\UnitKerja;
use App\Models\Pegawai;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class StrukturOrganisasiController extends Controller
{
    /**
     * Display struktur organisasi page
     */
    public function index()
    {
        // Get all bagian with pegawai
        $bagians = UnitKerja::with(['pegawais' => function ($query) {
                $query->orderBy('is_pimpinan', 'desc')->orderBy('urutan');
            }])
            ->where('level', 'bagian')
            ->orderBy('urutan')
            ->get();

        $stats = [
            'total_unit' => UnitKerja::count(),
            'total_pegawai' => Pegawai::count(),
            'bagian' => UnitKerja::where('level', 'bagian')->count(),
            'subbagian' => UnitKerja::where('level', 'subbagian')->count(),
        ];

        return view('admin.struktur.index', compact('bagians', 'stats'));
    }

    /**
     * Get pegawai by unit kerja (AJAX)
     */
    public function getPegawai($unitKerjaId)
    {
        $unitKerja = UnitKerja::with(['pegawais', 'children.pegawais'])->findOrFail($unitKerjaId);

        return response()->json([
            'unit_kerja' => $unitKerja,
            'pegawais' => $unitKerja->pegawais,
        ]);
    }

    /**
     * Show unit kerja management
     */
    public function unitKerja()
    {
        $unitKerjas = UnitKerja::with('parent')->orderBy('level')->orderBy('urutan')->get();
        $parents = UnitKerja::whereIn('level', ['sekda', 'asisten', 'bagian'])->orderBy('urutan')->get();

        return view('admin.struktur.unit-kerja', compact('unitKerjas', 'parents'));
    }

    /**
     * Store new unit kerja
     */
    public function storeUnitKerja(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'singkatan' => 'nullable|string|max:50',
            'level' => 'required|in:sekda,asisten,bagian,subbagian',
            'parent_id' => 'nullable|exists:unit_kerjas,id',
            'urutan' => 'nullable|integer',
        ]);

        $validated['urutan'] = $validated['urutan'] ?? 0;
        UnitKerja::create($validated);

        return redirect()->route('admin.struktur.unit-kerja')
            ->with('success', 'Unit kerja berhasil ditambahkan.');
    }

    /**
     * Update unit kerja
     */
    public function updateUnitKerja(Request $request, $id)
    {
        $unitKerja = UnitKerja::findOrFail($id);

        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'singkatan' => 'nullable|string|max:50',
            'level' => 'required|in:sekda,asisten,bagian,subbagian',
            'parent_id' => 'nullable|exists:unit_kerjas,id',
            'urutan' => 'nullable|integer',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');
        $unitKerja->update($validated);

        return redirect()->route('admin.struktur.unit-kerja')
            ->with('success', 'Unit kerja berhasil diperbarui.');
    }

    /**
     * Delete unit kerja
     */
    public function destroyUnitKerja($id)
    {
        $unitKerja = UnitKerja::findOrFail($id);

        // Check if has children or pegawai
        if ($unitKerja->children()->count() > 0) {
            return redirect()->route('admin.struktur.unit-kerja')
                ->with('error', 'Unit kerja tidak dapat dihapus karena memiliki sub unit.');
        }

        if ($unitKerja->pegawais()->count() > 0) {
            return redirect()->route('admin.struktur.unit-kerja')
                ->with('error', 'Unit kerja tidak dapat dihapus karena memiliki pegawai.');
        }

        $unitKerja->delete();

        return redirect()->route('admin.struktur.unit-kerja')
            ->with('success', 'Unit kerja berhasil dihapus.');
    }

    /**
     * Show pegawai management
     */
    public function pegawai(Request $request)
    {
        $query = Pegawai::with('unitKerja')
            ->orderBy('unit_kerja_id')
            ->orderBy('is_pimpinan', 'desc')
            ->orderBy('urutan');

        if ($request->unit_kerja_id) {
            $query->where('unit_kerja_id', $request->unit_kerja_id);
        }

        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('nama', 'like', '%' . $request->search . '%')
                  ->orWhere('nip', 'like', '%' . $request->search . '%')
                  ->orWhere('jabatan', 'like', '%' . $request->search . '%');
            });
        }

        $pegawais = $query->paginate(15);
        $unitKerjas = UnitKerja::orderBy('urutan')->get();

        return view('admin.struktur.pegawai', compact('pegawais', 'unitKerjas'));
    }

    /**
     * Store new pegawai
     */
    public function storePegawai(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'nip' => 'nullable|string|max:50',
            'jabatan' => 'required|string|max:255',
            'golongan' => 'nullable|string|max:50',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'unit_kerja_id' => 'required|exists:unit_kerjas,id',
            'is_pimpinan' => 'nullable|boolean',
            'urutan' => 'nullable|integer',
        ]);

        // Handle checkbox is_pimpinan
        $validated['is_pimpinan'] = $request->has('is_pimpinan') && $request->is_pimpinan ? true : false;

        // Jika pimpinan, set urutan 0 agar selalu di atas
        if ($validated['is_pimpinan']) {
            $validated['urutan'] = 0;
        } else {
            $validated['urutan'] = $validated['urutan'] ?? 99;
        }

        if ($request->hasFile('foto')) {
            $file = $request->file('foto');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $validated['foto'] = $file->storeAs('pegawai', $fileName, 'public');
        }

        Pegawai::create($validated);

        return redirect()->route('admin.struktur.pegawai')
            ->with('success', 'Pegawai berhasil ditambahkan.');
    }

    /**
     * Update pegawai
     */
    public function updatePegawai(Request $request, $id)
    {
        $pegawai = Pegawai::findOrFail($id);

        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'nip' => 'nullable|string|max:50',
            'jabatan' => 'required|string|max:255',
            'golongan' => 'nullable|string|max:50',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'unit_kerja_id' => 'required|exists:unit_kerjas,id',
            'is_pimpinan' => 'nullable|boolean',
            'urutan' => 'nullable|integer',
            'is_active' => 'nullable|boolean',
        ]);

        // Handle checkbox is_pimpinan
        $validated['is_pimpinan'] = $request->has('is_pimpinan') && $request->is_pimpinan ? true : false;
        $validated['is_active'] = $request->has('is_active') && $request->is_active ? true : false;

        // Jika pimpinan, set urutan 0 agar selalu di atas
        if ($validated['is_pimpinan']) {
            $validated['urutan'] = 0;
        } elseif (!isset($validated['urutan'])) {
            $validated['urutan'] = $pegawai->urutan ?? 99;
        }

        if ($request->hasFile('foto')) {
            // Delete old foto
            if ($pegawai->foto) {
                Storage::disk('public')->delete($pegawai->foto);
            }
            $file = $request->file('foto');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $validated['foto'] = $file->storeAs('pegawai', $fileName, 'public');
        }

        $pegawai->update($validated);

        return redirect()->route('admin.struktur.pegawai')
            ->with('success', 'Pegawai berhasil diperbarui.');
    }

    /**
     * Delete pegawai
     */
    public function destroyPegawai($id)
    {
        $pegawai = Pegawai::findOrFail($id);

        // Delete foto
        if ($pegawai->foto) {
            Storage::disk('public')->delete($pegawai->foto);
        }

        $pegawai->delete();

        return redirect()->route('admin.struktur.pegawai')
            ->with('success', 'Pegawai berhasil dihapus.');
    }
}
