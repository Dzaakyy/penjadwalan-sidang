<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\ThnAjaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ThnAjaranController extends Controller
{
    public function index()
    {
        $data_thn_ajaran = ThnAjaran::orderByDesc('id_thn_ajaran')->get();
        return view('admin.content.admin.thn_ajaran', compact('data_thn_ajaran'));
    }


    public function create()
    {
        $nextNumber = $this->getCariNomor();

        return view('admin.content.admin.form.thn_ajaran_form', compact('nextNumber'));
    }


    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id_thn_ajaran' => 'required',
            'thn_ajaran' => 'required',
            'status' => 'required',
        ], [
            'thn_ajaran.unique' => 'Tahun Ajaran sudah ada. Harap gunakan Tahun Ajaran lain.'
        ]);

        if ($validator->fails()) return redirect()->back()->withInput()->withErrors($validator);

        $data = [
            'id_thn_ajaran' => $request->id_thn_ajaran,
            'thn_ajaran' => $request->thn_ajaran,
            'status' => $request->status,

        ];

        ThnAjaran::create($data);
        return redirect()->route('thn_ajaran');
    }

    public function edit(string $id)
    {
        $data_thn_ajaran = ThnAjaran::where('id_thn_ajaran', $id)->first();
        return view('admin.content.admin.form.thn_ajaran_edit', compact('data_thn_ajaran'));
    }


    public function update(Request $request, string $id)
    {
        $validator = Validator::make($request->all(), [
            'id_thn_ajaran' => 'required',
            'thn_ajaran' => 'required|unique:thn_ajaran,thn_ajaran,' . $id . ',id_thn_ajaran',
            'status' => 'required',
        ], [
            'thn_ajaran.unique' => 'Tahun Ajaran sudah ada. Harap gunakan Tahun Ajaran lain.'
        ]);

        if ($validator->fails()) return redirect()->back()->withInput()->withErrors($validator);

        $data = [
            'id_thn_ajaran' => $request->id_thn_ajaran,
            'thn_ajaran' => $request->thn_ajaran,
            'status' => $request->status,
        ];
        $thn_ajaran = ThnAjaran::find($id);

        if ($thn_ajaran) {
            $thn_ajaran->update($data);
        }
        return redirect()->route('thn_ajaran');
    }


    public function delete(string $id)
    {
        $data_thn_ajaran = ThnAjaran::where('id_thn_ajaran', $id)->first();
        if ($data_thn_ajaran) {
            $data_thn_ajaran->delete();
        }
        return redirect()->route('thn_ajaran');
    }


    function getCariNomor()
    {
        $id_thn_ajaran = ThnAjaran::pluck('id_thn_ajaran')->toArray();

        for ($i = 1;; $i++) {
            if (!in_array($i, $id_thn_ajaran)) {
                return $i;
                break;
            }
        }
        return $i;
    }
}
