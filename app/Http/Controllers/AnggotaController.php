<?php

namespace App\Http\Controllers;

use App\Models\Anggota;
use Illuminate\Http\Request;

class AnggotaController extends Controller
{
    public function index(Request $request)
    {
        $cari = trim((string) $request->query('cari', ''));

        $daftar = Anggota::query()
            ->when($cari !== '', function ($q) use ($cari) {
                $q->where('nama', 'like', "%{$cari}%")
                  ->orWhere('nik', 'like', "%{$cari}%")
                  ->orWhere('alamat', 'like', "%{$cari}%");
            })
            ->latest('id')
            ->paginate(10)
            ->withQueryString();

        return view('anggota.index', compact('daftar', 'cari'));
    }

    public function create()
    {
        return view('anggota.form', ['anggota' => new Anggota()]);
    }

    public function store(Request $request)
    {
        Anggota::create($this->validasi($request));

        return redirect('/anggota')->with('sukses', 'Anggota baru tersimpan.');
    }

    public function edit(Anggota $anggota)
    {
        return view('anggota.form', compact('anggota'));
    }

    public function update(Request $request, Anggota $anggota)
    {
        $anggota->update($this->validasi($request, $anggota->id));

        return redirect('/anggota')->with('sukses', 'Data anggota diperbarui.');
    }

    public function destroy(Anggota $anggota)
    {
        $anggota->delete();

        return redirect('/anggota')->with('sukses', 'Anggota dihapus.');
    }

    private function validasi(Request $request, ?int $id = null): array
    {
        return $request->validate([
            'nama' => ['required', 'string', 'max:80'],
            'nik' => ['required', 'digits:16', 'unique:anggota,nik' . ($id ? ",{$id}" : '')],
            'no_hp' => ['required', 'string', 'max:20'],
            'alamat' => ['required', 'string', 'max:120'],
            'pekerjaan' => ['required', 'string', 'max:60'],
            'penghasilan' => ['required', 'numeric', 'min:0'],
        ], [
            'nik.digits' => 'NIK harus 16 angka.',
            'nik.unique' => 'NIK ini sudah terdaftar.',
        ]);
    }
}
