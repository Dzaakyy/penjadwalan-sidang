<?php

namespace App\Http\Controllers\admin;

use App\Models\Prodi;
use App\Models\Jurusan;
use App\Exports\ExportProdi;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Validator;

class ProdiController extends Controller
{
    public function index()
    {
        $data_jurusan = Jurusan::all();
        $data_prodi = Prodi::orderByDesc('id_prodi')->get();
        return view('admin.content.admin.prodi', compact('data_jurusan','data_prodi'));
    }


    public function create()
    {
        $data_jurusan = Jurusan::all();
        $nextNumber = $this->getCariNomor();

        return view('admin.content.admin.form.prodi_form', compact('data_jurusan', 'nextNumber'));
    }


    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id_prodi' => 'required',
            'kode_prodi' => 'required',
            'prodi' => 'required|unique:prodi,prodi',
            'jenjang' => 'required|in:D2,D3,D4',
            'jurusan_id' => 'required',
        ], [
            'prodi.unique' => 'Prodi sudah ada. Harap gunakan nama lain.'
        ]);

        if ($validator->fails()) return redirect()->back()->withInput()->withErrors($validator);

        $data = [
            'id_prodi' => $request->id_prodi,
            'kode_prodi' => $request->kode_prodi,
            'prodi' => $request->prodi,
            'jenjang' => $request->jenjang,
            'jurusan_id' => $request->jurusan_id,
        ];

        Prodi::create($data);
        return redirect()->route('prodi');
    }

    public function edit(string $id)
    {
        $data_jurusan = Jurusan::all();
        $data_prodi = Prodi::where('id_prodi', $id)->first();
        return view('admin.content.admin.form.prodi_edit', compact('data_jurusan', 'data_prodi'));
    }


    public function update(Request $request, string $id)
    {
        $validator = Validator::make($request->all(), [
            'id_prodi' => 'required',
            'kode_prodi' => 'required',
            'prodi' => 'required|unique:prodi,prodi,' . $id . ',id_prodi',
            'jenjang' => 'required',
            'jurusan_id' => 'required',
        ], [
            'prodi.unique' => 'Prodi sudah ada. Harap gunakan nama lain.'
        ]);

        if ($validator->fails()) return redirect()->back()->withInput()->withErrors($validator);

        $data = [
            'id_prodi' => $request->id_prodi,
            'kode_prodi' => $request->kode_prodi,
            'prodi' => $request->prodi,
            'jenjang' => $request->jenjang,
            'jurusan_id' => $request->jurusan_id,
        ];
        $prodi = Prodi::find($id);

        if ($prodi) {
            $prodi->update($data);
        }
        return redirect()->route('prodi');
    }

    public function export_excel()
    {
        return Excel::download(new ExportProdi, "Prodi.xlsx");
    }


    public function delete(string $id)
    {
        $data_prodi = Prodi::where('id_prodi', $id)->first();
        if ($data_prodi) {
            $data_prodi->delete();
        }
        return redirect()->route('prodi');
    }


    function getCariNomor()
    {
        $id_prodi = Prodi::pluck('id_prodi')->toArray();

        for ($i = 1;; $i++) {
            if (!in_array($i, $id_prodi)) {
                return $i;
                break;
            }
        }
        return $i;
    }
}
