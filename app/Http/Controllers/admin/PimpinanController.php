<?php

namespace App\Http\Controllers\Admin;

use App\Models\Dosen;
use App\Models\Prodi;
use App\Models\Jurusan;
use App\Models\Pimpinan;
use Illuminate\Http\Request;
use App\Models\JabatanPimpinan;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class PimpinanController extends Controller
{
    public function index()
    {
        $data_pimpinan = Pimpinan::with('r_dosen','r_jabatan_pimpinan')
        ->orderByDesc('id_pimpinan')
        ->get();
    return view('admin.content.admin.pimpinan', compact('data_pimpinan'));
    }


    public function create()
    {
        $data_dosen = Dosen::all();
        $data_jabatan_pimpinan = JabatanPimpinan::all();
        $nextNumber = $this->getCariNomor();

        return view('admin.content.admin.form.pimpinan_form', compact('data_dosen', 'data_jabatan_pimpinan', 'nextNumber'));
    }


    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id_pimpinan' => 'required',
            'dosen_id' => 'required',
            'jabatan_pimpinan_id' => 'required',
            'periode' => 'required',
            'status_pimpinan' => 'required',
        ],
        // [
        //     'jurusan.unique' => 'jurusan sudah ada. Harap gunakan nama lain.'
        // ]
    );

        if ($validator->fails()) return redirect()->back()->withInput()->withErrors($validator);

        $data = [
            'id_pimpinan' => $request->id_pimpinan,
            'dosen_id' => $request->dosen_id,
            'jabatan_pimpinan_id' => $request->jabatan_pimpinan_id,
            'periode' => $request->periode,
            'status_pimpinan' => $request->status_pimpinan,
        ];

        Pimpinan::create($data);
        return redirect()->route('pimpinan');
    }

    public function edit(string $id)
    {
        $data_pimpinan = Pimpinan::where('id_pimpinan', $id)->first();
        $data_dosen = Dosen::all();
        $data_jabatan_pimpinan = JabatanPimpinan::all();
        return view('admin.content.admin.form.pimpinan_edit', compact('data_pimpinan' ,'data_dosen', 'data_jabatan_pimpinan'));
    }


    public function update(Request $request, string $id)
    {
        $validator = Validator::make($request->all(), [
            'id_pimpinan' => 'required',
            'dosen_id' => 'required',
            'jabatan_pimpinan_id' => 'required',
            'periode' => 'required',
            'status_pimpinan' => 'required',
        ],
        // [
        //     'jurusan.unique' => 'jurusan sudah ada. Harap gunakan nama lain.'
        // ]
    );

        if ($validator->fails()) return redirect()->back()->withInput()->withErrors($validator);

        $data = [
            'id_pimpinan' => $request->id_pimpinan,
            'dosen_id' => $request->dosen_id,
            'jabatan_pimpinan_id' => $request->jabatan_pimpinan_id,
            'periode' => $request->periode,
            'status_pimpinan' => $request->status_pimpinan,
        ];
        $pimpinan = Pimpinan::find($id);

        if ($pimpinan) {
            $pimpinan->update($data);
        }
        return redirect()->route('pimpinan');
    }


    public function delete(string $id)
    {
        $data_pimpinan = Pimpinan::where('id_pimpinan', $id)->first();
        if ($data_pimpinan) {
            $data_pimpinan->delete();
        }
        return redirect()->route('pimpinan');
    }


    function getCariNomor()
    {
        $id_pimpinan = Pimpinan::pluck('id_pimpinan')->toArray();

        for ($i = 1;; $i++) {
            if (!in_array($i, $id_pimpinan)) {
                return $i;
                break;
            }
        }
        return $i;
    }
}
