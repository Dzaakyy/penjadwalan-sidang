<?php

namespace App\Http\Controllers\admin;

use App\Models\Jurusan;
use Illuminate\Http\Request;
use App\Exports\ExportJurusan;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Imports\ImportJurusan;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class JurusanController extends Controller
{
    public function index()
    {
        $data_jurusan = Jurusan::orderByDesc('id_jurusan')->get();
        return view('admin.content.admin.jurusan', compact('data_jurusan'));
    }


    public function create()
    {
        $nextNumber = $this->getCariNomor();

        return view('admin.content.admin.form.jurusan_form', compact('nextNumber'));
    }


    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id_jurusan' => 'required',
            'kode_jurusan' => 'required',
            'nama_jurusan' => 'required|unique:jurusan,nama_jurusan',
        ], [
            'nama_jurusan.unique' => 'Nama Jurusan sudah ada. Harap gunakan nama lain.'
        ]);

        if ($validator->fails()) return redirect()->back()->withInput()->withErrors($validator);

        $data = [
            'id_jurusan' => $request->id_jurusan,
            'kode_jurusan' => $request->kode_jurusan,
            'nama_jurusan' => $request->nama_jurusan,
        ];

        Jurusan::create($data);
        return redirect()->route('jurusan')->with('success', 'Data berhasil ditambahkan.');
    }

    public function edit(string $id)
    {
        $data_jurusan = Jurusan::where('id_jurusan', $id)->first();
        return view('admin.content.admin.form.jurusan_edit', compact('data_jurusan'));
    }





    public function update(Request $request, string $id)
    {
        $validator = Validator::make($request->all(), [
            'id_jurusan' => 'required',
            'kode_jurusan' => 'required',
            'nama_jurusan' => 'required|unique:jurusan,nama_jurusan,' . $id . ',id_jurusan',
        ], [
            'nama_jurusan.unique' => 'Nama Jurusan sudah ada. Harap gunakan nama lain.'
        ]);

        if ($validator->fails()) return redirect()->back()->withInput()->withErrors($validator);

        $data = [
            'id_jurusan' => $request->id_jurusan,
            'kode_jurusan' => $request->kode_jurusan,
            'nama_jurusan' => $request->nama_jurusan,
        ];
        $jurusan = Jurusan::find($id);

        if ($jurusan) {
            $jurusan->update($data);
        }
        return redirect()->route('jurusan')->with('success', 'Data berhasil diganti.');
    }


    // public function delete(string $id)
    // {
    //     $data_jurusan = Jurusan::where('id_jurusan', $id)->first();
    //     if ($data_jurusan) {
    //         $data_jurusan->delete();
    //     }
    //     return redirect()->route('jurusan')->with('success', 'Data berhasil dihapus.');
    // }


    public function delete(string $id)
    {

        $data_jurusan = Jurusan::where('id_jurusan', $id)->first();

        if ($data_jurusan) {

            $relations = [
                'r_prodi',
            ];


            foreach ($relations as $relation) {
                if ($data_jurusan->{$relation}()->count() > 0) {
                    return redirect()->route('jurusan')->with('error', 'Data ini tidak dapat dihapus karena masih ada data yang terkait di ' . class_basename($relation) . '.');
                }
            }

            try {

                $data_jurusan->delete();
                return redirect()->route('jurusan')->with('success', 'Data berhasil dihapus.');
            } catch (\Illuminate\Database\QueryException $e) {
                if ($e->getCode() == '23000') {
                    return redirect()->route('jurusan')->with('error', 'Data ini tidak dapat dihapus karena terkait dengan data lain.');
                }

                throw $e;
            }
        }

        return redirect()->route('jurusan')->with('error', 'Data tidak ditemukan.');
    }




    public function export_excel()
    {
        return Excel::download(new ExportJurusan, "Jurusan.xlsx");
    }


    public function import(Request $request)
    {
        try {
            Log::info('Import request received with file: ', [$request->file('file')->getClientOriginalName()]);
            Excel::import(new ImportJurusan, $request->file('file'));
            return redirect('jurusan')->with('success', 'Data berhasil diimpor.');
        } catch (ValidationException $e) {
            $errorMessages = $e->errors()['duplicate_data'] ?? [];
            $errorString = implode('<br>', $errorMessages);
            Log::warning('Validation error during import: ', $errorMessages);
            return redirect()->back()->withErrors(['duplicate_data' => $errorString]);
        } catch (\Exception $e) {
            Log::error('General Exception: ' . $e->getMessage());
            Log::error('Trace: ' . $e->getTraceAsString());
            return redirect()->back()->with('error', 'Terjadi kesalahan saat import data: ' . $e->getMessage());
        }
    }


    function getCariNomor()
    {
        $id_jurusan = Jurusan::pluck('id_jurusan')->toArray();

        for ($i = 1;; $i++) {
            if (!in_array($i, $id_jurusan)) {
                return $i;
                break;
            }
        }
        return $i;
    }
}
