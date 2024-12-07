<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Sesi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SesiController extends Controller
{
    public function index()
    {
        $data_sesi = Sesi::orderByDesc('id_sesi')->get();
        return view('admin.content.admin.sesi', compact('data_sesi'));
    }


    public function create()
    {
        $nextNumber = $this->getCariNomor();

        return view('admin.content.admin.form.sesi_form', compact('nextNumber'));
    }


    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id_sesi' => 'required',
            'sesi' => 'required',
            'jam' => 'required|unique:sesi,jam',

        ], [
            'jam.unique' => 'jam sudah ada. Harap gunakan jam lain.'
        ]);

        if ($validator->fails()) return redirect()->back()->withInput()->withErrors($validator);

        $data = [
            'id_sesi' => $request->id_sesi,
            'sesi' => $request->sesi,
            'jam' => $request->jam,

        ];

        Sesi::create($data);
        return redirect()->route('sesi');
    }

    public function edit(string $id)
    {
        $data_sesi = Sesi::where('id_sesi', $id)->first();
        return view('admin.content.admin.form.sesi_edit', compact('data_sesi'));
    }


    public function update(Request $request, string $id)
    {
        $validator = Validator::make($request->all(), [
            'id_sesi' => 'required',
            'sesi' => 'required',
            'jam' => 'required|unique:sesi,jam,' . $id . ',id_sesi',
        ], [
            'jam.unique' => 'jam sudah ada. Harap gunakan jam lain.'
        ]);

        if ($validator->fails()) return redirect()->back()->withInput()->withErrors($validator);

        $data = [
            'id_sesi' => $request->id_sesi,
            'sesi' => $request->sesi,
            'jam' => $request->jam,
        ];
        $sesi = Sesi::find($id);

        if ($sesi) {
            $sesi->update($data);
        }
        return redirect()->route('sesi');
    }


    public function delete(string $id)
    {
        $data_sesi = Sesi::where('id_sesi', $id)->first();
        if ($data_sesi) {
            $data_sesi->delete();
        }
        return redirect()->route('sesi');
    }


    function getCariNomor()
    {
        $id_sesi = Sesi::pluck('id_sesi')->toArray();

        for ($i = 1;; $i++) {
            if (!in_array($i, $id_sesi)) {
                return $i;
                break;
            }
        }
        return $i;
    }
}
