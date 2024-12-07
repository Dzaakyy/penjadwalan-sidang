<?php

namespace App\Http\Controllers\admin;

use App\Models\User;
use App\Models\Prodi;
use App\Models\Jurusan;
use App\Models\Mahasiswa;
use Illuminate\Http\Request;
use App\Exports\ExportMahasiswa;
use App\Imports\ImportMahasiswa;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class MahasiswaController extends Controller
{
    public function index()
    {
        $data_mahasiswa = Mahasiswa::with('r_prodi')
            ->orderByDesc('id_mahasiswa')
            ->get();
        return view('admin.content.admin.mahasiswa', compact('data_mahasiswa'));
    }

    public function create()
    {
        $data_prodi = Prodi::all();
        $nextNumber = $this->getCariNomor();
        return view('admin.content.admin.form.mahasiswa_form', compact('data_prodi', 'nextNumber'));
    }


    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id_mahasiswa' => 'required',
            'nama' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'nim' => 'required|unique:mahasiswa,nim',
            'prodi_id' => 'required',
            'image' => 'nullable|mimes:jpg,jpeg,png',
            'gender' => 'required',
            'status_mahasiswa' => 'required',
        ], [
            'nim.unique' => 'Nim sudah ada. Harap gunakan nim lain.',
            'email.unique' => 'Email sudah ada. Harap gunakan email lain.'
        ]);


        if ($validator->fails()) return redirect()->back()->withInput()->withErrors($validator);

        $user = User::create([
            'name' => $request->nama,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);


        $data = [
            'id_mahasiswa' => $request->id_mahasiswa,
            'nama' => $request->nama,
            'user_id' => $user->id,
            'nim' => $request->nim,
            'prodi_id' => $request->prodi_id,
            'gender' => $request->gender,
            'status_mahasiswa' => $request->status_mahasiswa,
        ];

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = $file->getClientOriginalName();
            $path = 'public/uploads/mahasiswa/image/';
            $file->storeAs($path, $filename);

            $data['image'] = $filename;
        }

        Mahasiswa::create($data);
        return redirect()->route('mahasiswa')->with('success', 'Data berhasil ditambahkan.');
    }

    public function edit(string $id)
    {

        $data_prodi = Prodi::all();
        $data_mahasiswa = Mahasiswa::with('r_user')->where('id_mahasiswa', $id)->first();


        return view('admin.content.admin.form.mahasiswa_edit', compact('data_prodi', 'data_mahasiswa'));
    }


    public function update(Request $request, string $id)
    {
        $mahasiswa = Mahasiswa::with('r_user')->where('id_mahasiswa', $id)->first();

        // Check if the dosen record exists
        if (!$mahasiswa) {
            return redirect()->route('mahasiswa')->with('error', 'Data mahasiswa tidak ditemukan.');
        }


        if ($request->email === $mahasiswa->r_user->email) {
            $validator = Validator::make($request->all(), [
                'id_mahasiswa' => 'required',
                'email' => 'email',
                'password' => 'nullable|min:6',
                'nim' => 'required|unique:mahasiswa,nim,' . $id . ',id_mahasiswa',
                'prodi_id' => 'required',
                'image' => 'nullable|mimes:jpg,jpeg,png',
                'gender' => 'required',
                'status_mahasiswa' => 'required',
            ], [
                'nim.unique' => 'Nim sudah ada. Harap gunakan nim lain.',
                'email.unique' => 'Email sudah ada. Harap gunakan email lain.'
            ]);
        } else {
            $validator = Validator::make($request->all(), [
                'id_mahasiswa' => 'required',
                'nama' => 'required',
                'email' => 'required|email|unique:users,email' . $mahasiswa->r_user->id_user,
                'password' => 'required|min:6',
                'nim' => 'required|unique:mahasiswa,nim,' . $id . ',id_mahasiswa',
                'prodi_id' => 'required',
                'image' => 'nullable|mimes:jpg,jpeg,png',
                'gender' => 'required',
                'status_mahasiswa' => 'required',
            ], [
                'nim.unique' => 'Nim sudah ada. Harap gunakan nim lain.',
                'email.unique' => 'Email sudah ada. Harap gunakan email lain.'
            ]);
        }



        if ($validator->fails()) return redirect()->back()->withInput()->withErrors($validator);

        $data = [
            'id_mahasiswa' => $request->id_mahasiswa,
            'nama' => $request->nama,
            'nim' => $request->nim,
            'prodi_id' => $request->prodi_id,
            'gender' => $request->gender,
            'status_mahasiswa' => $request->status_mahasiswa,
        ];

        $oldData = Mahasiswa::where('id_mahasiswa', $id)->first();;
        if ($oldData->image !== null && $request->hasFile('image')) {
            Storage::delete('public/uploads/mahasiswa/image/' . $oldData->image);
        }
        $filename = null;

        if ($request->hasFile('image')) {

            $file = $request->file('image');
            $filename = $file->getClientOriginalName();

            $path = 'public/uploads/mahasiswa/image/';
            $file->storeAs($path, $filename);

            $data['image'] = $filename;
        }


        $mahasiswa->update($data);

        if ($request->filled('email')) {
            $mahasiswa->r_user->update(['email' => $request->email]);
        }

        return redirect()->route('mahasiswa')->with('success', 'Data berhasil diganti.');
    }



    public function export_excel()
    {
        return Excel::download(new ExportMahasiswa, "Mahasiswa.xlsx");
    }

    public function import(Request $request)
    {
        try {
            Log::info('Import request received with file: ', [$request->file('file')->getClientOriginalName()]);
            Excel::import(new ImportMahasiswa, $request->file('file'));
            return redirect('mahasiswa')->with('success', 'Data berhasil diimpor.');
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





    public function show(string $id)
    {
        $data_mahasiswa = Mahasiswa::findOrFail($id);
        return view('admin.content.admin.mahasiswa_detail', compact('data_mahasiswa'));
    }


    public function delete(string $id)
    {
        $data_mahasiswa = Mahasiswa::where('id_mahasiswa', $id)->first();
        if ($data_mahasiswa && $data_mahasiswa->image) {
            Storage::delete('public/uploads/mahasiswa/image/' . $data_mahasiswa->image);
        }

        if ($data_mahasiswa) {
            $data_mahasiswa->delete();
        }
        return redirect()->route('mahasiswa')->with('success', 'Data berhasil dihapus.');
    }

    function getCariNomor()
    {
        $id_mahasiswa = Mahasiswa::pluck('id_mahasiswa')->toArray();
        for ($i = 1;; $i++) {

            if (!in_array($i, $id_mahasiswa)) {
                return $i;
                break;
            }
        }
        return $i;
    }
}
