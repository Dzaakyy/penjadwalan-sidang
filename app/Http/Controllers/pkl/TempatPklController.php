<?php

namespace App\Http\Controllers\pkl;

use App\Models\TempatPkl;
use Illuminate\Http\Request;
use App\Models\PerusahaanPkl;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
 
class TempatPklController extends Controller
{
    public function index()
    {
        $data_perusahaan = TempatPkl::orderByDesc('id_perusahaan')->get();
        return view('admin.content.pkl.kaprodi.tempat_pkl', compact('data_perusahaan'));
    }


    public function create()
    {
        $nextNumber = $this->getCariNomor();

        return view('admin.content.pkl.kaprodi.form.tempat_pkl_form', compact('nextNumber'));
    }


    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id_perusahaan' => 'required',
            'nama_perusahaan' => 'required|unique:tempat_pkl,nama_perusahaan',
            'deskripsi' => 'required',
            'status' => 'required',
        ], [
            'nama_perusahaan.unique' => 'Nama Perusahaan sudah ada. Harap gunakan nama lain.'
        ]);

        if ($validator->fails()) return redirect()->back()->withInput()->withErrors($validator);

        $data = [
            'id_perusahaan' => $request->id_perusahaan,
            'nama_perusahaan' => $request->nama_perusahaan,
            'deskripsi' => $request->deskripsi,
            'kuota' => $request->kuota ?? 4,
            'status' => $request->status,
        ];

        TempatPkl::create($data);
        return redirect()->route('tempat_pkl')->with('success', 'Data berhasil ditambahkan.');
    }


    public function edit(string $id)
    {
        $data_perusahaan = TempatPkl::where('id_perusahaan', $id)->first();
        return view('admin.content.pkl.kaprodi.form.tempat_pkl_edit', compact('data_perusahaan'));
    }



    public function update(Request $request, string $id)
    {
        $validator = Validator::make($request->all(), [
            'id_perusahaan' => 'required',
            'nama_perusahaan' => 'required|unique:tempat_pkl,nama_perusahaan,' . $id . ',id_perusahaan',
            'deskripsi' => 'required',
            'kuota' => 'required',
            'status' => 'required',
        ], [
            'nama_perusahaan.unique' => 'Nama Perusahaan sudah ada. Harap gunakan nama lain.'
        ]);

        if ($validator->fails()) return redirect()->back()->withInput()->withErrors($validator);

        $data = [
            'id_perusahaan' => $request->id_perusahaan,
            'nama_perusahaan' => $request->nama_perusahaan,
            'deskripsi' => $request->deskripsi,
            'kuota' => $request->kuota ?? 4,
            'status' => $request->status,
        ];
        $tempat_pkl = TempatPkl::find($id);

        if ($tempat_pkl) {
            $tempat_pkl->update($data);
        }
        return redirect()->route('tempat_pkl')->with('success', 'Data berhasil diganti.');
    }


    public function delete(string $id)
    {
        $data_perusahaan = TempatPkl::where('id_perusahaan', $id)->first();
        if ($data_perusahaan) {
            $data_perusahaan->delete();
        }
        return redirect()->route('tempat_pkl')->with('success', 'Data berhasil dihapus.');
    }


    // public function export_excel()
    // {
    //     return Excel::download(new Exporttempat_pkl, "tempat_pkl.xlsx");
    // }


    // public function import(Request $request)
    // {
    //     try {
    //         Log::info('Import request received with file: ', [$request->file('file')->getClientOriginalName()]);
    //         Excel::import(new Importtempat_pkl, $request->file('file'));
    //         return redirect('tempat_pkl')->with('success', 'Data berhasil diimpor.');
    //     } catch (ValidationException $e) {
    //         $errorMessages = $e->errors()['duplicate_data'] ?? [];
    //         $errorString = implode('<br>', $errorMessages);
    //         Log::warning('Validation error during import: ', $errorMessages);
    //         return redirect()->back()->withErrors(['duplicate_data' => $errorString]);
    //     } catch (\Exception $e) {
    //         Log::error('General Exception: ' . $e->getMessage());
    //         Log::error('Trace: ' . $e->getTraceAsString());
    //         return redirect()->back()->with('error', 'Terjadi kesalahan saat import data: ' . $e->getMessage());
    //     }
    // }

    function getCariNomor()
    {
        $id_perusahaan = TempatPkl::pluck('id_perusahaan')->toArray();

        for ($i = 1;; $i++) {
            if (!in_array($i, $id_perusahaan)) {
                return $i;
                break;
            }
        }
        return $i;
    }
}
