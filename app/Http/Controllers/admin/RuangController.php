<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Ruang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RuangController extends Controller
{
    public function index()
    {
        $data_ruang = Ruang::orderByDesc('id_ruang')->get();
        return view('admin.content.admin.ruang', compact('data_ruang'));
    }


    public function create()
    {
        $nextNumber = $this->getCariNomor();

        return view('admin.content.admin.form.ruang_form', compact('nextNumber'));
    }


    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id_ruang' => 'required',
            'kode_ruang' => 'required|unique:ruang,kode_ruang',

        ], [
            'kode_ruang.unique' => 'Ruangan sudah ada. Harap gunakan Kode lain.'
        ]);

        if ($validator->fails()) return redirect()->back()->withInput()->withErrors($validator);

        $data = [
            'id_ruang' => $request->id_ruang,
            'kode_ruang' => $request->kode_ruang,

        ];

        Ruang::create($data);
        return redirect()->route('ruang');
    }

    public function edit(string $id)
    {
        $data_ruang = Ruang::where('id_ruang', $id)->first();
        return view('admin.content.admin.form.ruang_edit', compact('data_ruang'));
    }


    public function update(Request $request, string $id)
    {
        $validator = Validator::make($request->all(), [
            'id_ruang' => 'required',
            'kode_ruang' => 'required|unique:ruang,kode_ruang,' . $id . ',id_ruang',
        ], [
            'kode_ruang.unique' => 'Ruangan sudah ada. Harap gunakan Kode lain.'
        ]);

        if ($validator->fails()) return redirect()->back()->withInput()->withErrors($validator);

        $data = [
            'id_ruang' => $request->id_ruang,
            'kode_ruang' => $request->kode_ruang,
        ];
        $ruang = Ruang::find($id);

        if ($ruang) {
            $ruang->update($data);
        }
        return redirect()->route('ruang');
    }


    public function delete(string $id)
    {
        $data_ruang = Ruang::where('id_ruang', $id)->first();
        if ($data_ruang) {
            $data_ruang->delete();
        }
        return redirect()->route('ruang');
    }


    function getCariNomor()
    {
        $id_ruang = Ruang::pluck('id_ruang')->toArray();

        for ($i = 1;; $i++) {
            if (!in_array($i, $id_ruang)) {
                return $i;
                break;
            }
        }
        return $i;
    }
}
