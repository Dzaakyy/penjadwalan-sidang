<?php

namespace App\Http\Controllers\admin;

use App\Models\User;
use App\Models\Dosen;
use App\Models\Prodi;
use App\Models\Jurusan;
use App\Exports\ExportDosen;
use App\Imports\ImportDosen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class DosenController extends Controller
{
    public function index()
    {
        $data_dosen = Dosen::with('r_prodi', 'r_jurusan')
            ->orderByDesc('id_dosen')
            ->get();
        return view('admin.content.admin.dosen', compact('data_dosen'));
    }

    public function create()
    {
        $data_jurusan = Jurusan::with('r_prodi')->get();
        // $data_prodi = Prodi::all();
        $nextNumber = $this->getCariNomor();
        return view('admin.content.admin.form.dosen_form', compact('data_jurusan', 'nextNumber'));
    }


    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id_dosen' => 'required',
            'nama_dosen' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'nidn' => 'required|unique:dosen,nidn',
            'nip' => 'required|unique:dosen,nip',
            'gender' => 'required',
            'jurusan_id' => 'required',
            'prodi_id' => 'required',
            'image' => 'nullable|mimes:jpg,jpeg,png',
            'golongan' => 'required|in:0,1,2,3',
            'status_dosen' => 'required',
        ], [
            'nidn.unique' => 'Nidn sudah ada. Harap gunakan nidn lain.',
            'nip.unique' => 'Nip sudah ada. Harap gunakan nip lain.',
            'email.unique' => 'Email sudah terdaftar. Harap gunakan email lain.',
        ]);


        if ($validator->fails()) return redirect()->back()->withInput()->withErrors($validator);

        $user = User::create([
            'name' => $request->nama_dosen,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);
        $user->assignRole('dosen');

        $data = [
            'id_dosen' => $request->id_dosen,
            'nama_dosen' => $request->nama_dosen,
            'user_id' => $user->id,
            'nidn' => $request->nidn,
            'nip' => $request->nip,
            'gender' => $request->gender,
            'jurusan_id' => $request->jurusan_id,
            'prodi_id' => $request->prodi_id,
            // 'password' => bcrypt($request->password),
            'golongan' => $request->golongan,
            'status_dosen' => $request->status_dosen,
        ];

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = $file->getClientOriginalName();
            $path = 'public/uploads/dosen/image/';
            $file->storeAs($path, $filename);

            $data['image'] = $filename;
        }


        Dosen::create($data);
        return redirect()->route('dosen')->with('success', 'Data berhasil ditambahkan.');
    }

    public function edit(string $id)
    {

        $data_jurusan = Jurusan::all();
        $data_prodi = Prodi::all();
        // $data_dosen = Dosen::where('id_dosen', $id)->first();
        $data_dosen = Dosen::with('r_user')->where('id_dosen', $id)->first();

        return view('admin.content.admin.form.dosen_edit', compact('data_jurusan', 'data_prodi', 'data_dosen'));
    }


    public function update(Request $request, string $id)
    {
        $dosen = Dosen::with('r_user')->where('id_dosen', $id)->first();

        // Check if the dosen record exists
        if (!$dosen) {
            return redirect()->route('dosen')->with('error', 'Data dosen tidak ditemukan.');
        }

        if ($request->email === $dosen->r_user->email) {
            $validator = Validator::make($request->all(), [
                'id_dosen' => 'required',
                'nama_dosen' => 'required',
                'email' => 'email',
                'password' => 'nullable|min:6',
                'nidn' => 'required|unique:dosen,nidn,' . $id . ',id_dosen',
                'nip' => 'required|unique:dosen,nip,' . $id . ',id_dosen',
                'gender' => 'required',
                'jurusan_id' => 'required|exists:jurusan,id_jurusan',
                'prodi_id' => 'required|exists:prodi,id_prodi',
                'image' => 'nullable|mimes:jpg,jpeg,png',
                'golongan' => 'required|in:0,1,2,3',
                'status_dosen' => 'required',
            ], [
                'nidn.unique' => 'Nidn sudah ada. Harap gunakan Nidn lain.',
                'nip.unique' => 'Nip sudah ada. Harap gunakan Nip lain.',
                'email.unique' => 'Email sudah terdaftar. Harap gunakan email lain.'
            ]);
        } else {
            $validator = Validator::make($request->all(), [
                'id_dosen' => 'required',
                'nama_dosen' => 'required',
                'email' => 'required|email|unique:users,email,' . $dosen->r_user->id_user,
                'password' => 'nullable|min:6',
                'nidn' => 'required|unique:dosen,nidn,' . $id . ',id_dosen',
                'nip' => 'required|unique:dosen,nip,' . $id . ',id_dosen',
                'gender' => 'required',
                'jurusan_id' => 'required',
                'prodi_id' => 'required',
                'image' => 'nullable|mimes:jpg,jpeg,png',
                'golongan' => 'required',
                'status_dosen' => 'required',
            ], [
                'nidn.unique' => 'Nidn sudah ada. Harap gunakan Nidn lain.',
                'nip.unique' => 'Nip sudah ada. Harap gunakan Nip lain.',
                'email.unique' => 'Email sudah terdaftar. Harap gunakan email lain.'
            ]);
        }


        if ($validator->fails()) return redirect()->back()->withInput()->withErrors($validator);

        $data = [
            'id_dosen' => $request->id_dosen,
            'nama_dosen' => $request->nama_dosen,
            'nidn' => $request->nidn,
            'nip' => $request->nip,
            'gender' => $request->gender,
            'jurusan_id' => $request->jurusan_id,
            'prodi_id' => $request->prodi_id,
            'golongan' => $request->golongan,
            'status_dosen' => $request->status_dosen,
        ];
        $oldData = Dosen::where('id_dosen', $id)->first();;
        if ($oldData->image !== null && $request->hasFile('image')) {
            Storage::delete('public/uploads/dosen/image/' . $oldData->image);
        }
        $filename = null;

        if ($request->hasFile('image')) {

            $file = $request->file('image');
            $filename = $file->getClientOriginalName();

            $path = 'public/uploads/dosen/image/';
            $file->storeAs($path, $filename);

            $data['image'] = $filename;
        }

        $dosen->update($data);

        if ($request->filled('email')) {
            $dosen->r_user->update(['email' => $request->email]);
        }
        return redirect()->route('dosen')->with('success', 'Data berhasil diganti.');
    }



    public function export_excel(){
        return Excel::download(new ExportDosen, "Dosen.xlsx");
    }


    public function import(Request $request)
    {
        try {
            Log::info('Import request received with file: ', [$request->file('file')->getClientOriginalName()]);
            Excel::import(new ImportDosen, $request->file('file'));
            return redirect('dosen')->with('success', 'Data berhasil diimpor.');
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



        public function show(string $id){
            $data_dosen = Dosen::findOrFail($id);
            return view('admin.content.admin.dosen_detail', compact('data_dosen'));
        }


    public function delete(string $id)
    {
        $data_dosen = Dosen::with('r_user')->where('id_dosen', $id)->first();

        if ($data_dosen && $data_dosen->image) {
            Storage::delete('public/uploads/dosen/image/' . $data_dosen->image);
        }
        if ($data_dosen && $data_dosen->r_user && $data_dosen->r_user->id == Auth::id()) {
            return redirect()->route('dosen')->with('error', 'Anda tidak dapat menghapus data akun sendiri.');
        }

        if ($data_dosen && $data_dosen->r_user) {
            $data_dosen->r_user->delete();
        }

        if ($data_dosen) {
            $data_dosen->delete();
        }
        return redirect()->route('dosen')->with('success', 'Data berhasil dihapus.');
    }


    function getCariNomor()
    {
        $id_dosen = dosen::pluck('id_dosen')->toArray();
        for ($i = 1;; $i++) {
            if (!in_array($i, $id_dosen)) {
                return $i;
                break;
            }
        }
        return $i;
    }
}
