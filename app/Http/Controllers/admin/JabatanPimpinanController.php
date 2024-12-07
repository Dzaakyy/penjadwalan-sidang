<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\JabatanPimpinan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class JabatanPimpinanController extends Controller
{
    public function index()
    {
        $data_jabatan_pimpinan = JabatanPimpinan::orderByDesc('id_jabatan_pimpinan')->get();
        return view('admin.content.admin.jabatan_pimpinan', compact('data_jabatan_pimpinan'));
    }


    public function create()
    {
        $nextNumber = $this->getCariNomor();

        return view('admin.content.admin.form.jabatan_pimpinan_form', compact('nextNumber'));
    }


    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id_jabatan_pimpinan' => 'required',
            'jabatan_pimpinan' => 'required|unique:jabatan_pimpinan,jabatan_pimpinan',
            'kode_jabatan_pimpinan' => 'required',
            'status_jabatan_pimpinan' => 'required',

        ], [
            'jabatan_pimpinan.unique' => 'Jabatan Pimpinan sudah ada. Harap gunakan Jabatan Pimpinan lain.'
        ]);

        if ($validator->fails()) return redirect()->back()->withInput()->withErrors($validator);

        $data = [
            'id_jabatan_pimpinan' => $request->id_jabatan_pimpinan,
            'jabatan_pimpinan' => $request->jabatan_pimpinan,
            'kode_jabatan_pimpinan' => $request->kode_jabatan_pimpinan,
            'status_jabatan_pimpinan' => $request->status_jabatan_pimpinan,

        ];

        JabatanPimpinan::create($data);
        return redirect()->route('jabatan_pimpinan');
    }

    public function edit(string $id)
    {
        $data_jabatan_pimpinan = JabatanPimpinan::where('id_jabatan_pimpinan', $id)->first();
        return view('admin.content.admin.form.jabatan_pimpinan_edit', compact('data_jabatan_pimpinan'));
    }


    public function update(Request $request, string $id)
    {
        $validator = Validator::make($request->all(), [
            'id_jabatan_pimpinan' => 'required',
            'jabatan_pimpinan' => 'required|unique:jabatan_pimpinan,jabatan_pimpinan,' . $id . ',id_jabatan_pimpinan',
            'kode_jabatan_pimpinan' => 'required',
            'status_jabatan_pimpinan' => 'required',
        ], [
            'jabatan_pimpinan.unique' => 'Jabatan Pimpinan sudah ada. Harap gunakan Jabatan Pimpinan lain.'
        ]);

        if ($validator->fails()) return redirect()->back()->withInput()->withErrors($validator);

        $data = [
            'id_jabatan_pimpinan' => $request->id_jabatan_pimpinan,
            'jabatan_pimpinan' => $request->jabatan_pimpinan,
            'kode_jabatan_pimpinan' => $request->kode_jabatan_pimpinan,
            'status_jabatan_pimpinan' => $request->status_jabatan_pimpinan,
        ];
        $jabatan_pimpinan = JabatanPimpinan::find($id);

        if ($jabatan_pimpinan) {
            $jabatan_pimpinan->update($data);
        }
        return redirect()->route('jabatan_pimpinan');
    }


    public function delete(string $id)
    {
        $data_jabatan_pimpinan = JabatanPimpinan::where('id_jabatan_pimpinan', $id)->first();
        if ($data_jabatan_pimpinan) {
            $data_jabatan_pimpinan->delete();
        }
        return redirect()->route('jabatan_pimpinan');
    }


    function getCariNomor()
    {
        $id_jabatan_pimpinan = JabatanPimpinan::pluck('id_jabatan_pimpinan')->toArray();

        for ($i = 1;; $i++) {
            if (!in_array($i, $id_jabatan_pimpinan)) {
                return $i;
                break;
            }
        }
        return $i;
    }
}
