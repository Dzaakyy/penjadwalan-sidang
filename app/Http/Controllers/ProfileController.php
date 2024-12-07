<?php

namespace App\Http\Controllers;

use App\Models\Dosen;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Redirect;
use App\Http\Requests\ProfileUpdateRequest;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */

    public function edit()
    {
        return view('admin.content.user.profile');
    }

    public function update(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . Auth::id(),
            'password' => 'nullable|string|min:8',
        ]);

        $user = Auth::user();

        if ($user) {
            $updateMade = false;


            if ($user->name !== $request->name || $user->email !== $request->email) {
                $updateMade = true;
            }


            if ($request->filled('password') && !Hash::check($request->password, $user->password)) {
                $updateMade = true;
            }


            if (!$updateMade) {
                return redirect()->route('profile')->with('no_update', 'Tidak ada perubahan yang dilakukan!');
            }


            $user->name = $request->name;
            $user->email = $request->email;

            if ($request->filled('password')) {
                $user->password = Hash::make($request->password);
            }

            $user->save();


            if ($user->r_dosen) {
                $user->r_dosen->update(['nama_dosen' => $request->name]);
            }

            if ($user->r_mahasiswa) {
                $user->r_mahasiswa->update(['nama' => $request->name]);
            }

            return redirect()->route('profile')->with('success', 'Profile berhasil diupdate!');
        }

        return redirect()->route('login')->with('error', 'Please login to access your profile.');
    }




    public function updateImage(Request $request)
    {
        $request->validate([
            'image' => 'nullable|mimes:jpg,jpeg,png|max:2048',
        ]);

        $user = Auth::user();


        $relatedModel = null;
        if ($user->r_dosen) {
            $relatedModel = $user->r_dosen;
            $path = 'public/uploads/dosen/image/';
        } elseif ($user->r_mahasiswa) {
            $relatedModel = $user->r_mahasiswa;
            $path = 'public/uploads/mahasiswa/image/';
        }

        if ($relatedModel && $request->hasFile('image')) {

            if ($relatedModel->image) {
                Storage::delete($path . $relatedModel->image);
            }


            $file = $request->file('image');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->storeAs($path, $filename);


            $relatedModel->image = $filename;
            $relatedModel->save();

            return redirect()->back()->with('success', 'Foto profil berhasil diupdate.');
        }

        return redirect()->back()->with('error', 'Tidak ada gambar yang diupload atau user tidak memiliki relasi.');
    }



    public function destroyImage(Request $request)
    {
        $user = Auth::user();


        $relatedModel = null;
        if ($user->r_dosen) {
            $relatedModel = $user->r_dosen;
            $path = 'public/uploads/dosen/image/';
        } elseif ($user->r_mahasiswa) {
            $relatedModel = $user->r_mahasiswa;
            $path = 'public/uploads/mahasiswa/image/';
        }


        if ($relatedModel && $relatedModel->image && $relatedModel->image !== 'default.png') {

            Storage::delete($path . $relatedModel->image);


            $relatedModel->image = null;
            $relatedModel->save();

            return redirect()->back()->with('success', 'Foto profil berhasil dihapus.');
        }

        return redirect()->back()->with('error', 'Tidak ada foto profil untuk dihapus.');
    }
}
