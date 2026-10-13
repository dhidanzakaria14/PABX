<?php

namespace App\Http\Controllers;

use App\Models\TbmSetting;
use App\Models\TbmBisnis;
use App\Models\TbmGroupDepartment;
use App\Models\TbmDepartment;
use App\Models\TbmTarif;
use App\Models\TbmTarifKhusus;
use App\Models\TblUser;
use App\Models\TbmPrefix;
use App\Models\TbmLevel;
use App\Models\TbmZona;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    /**
     * 1. Manage PABX (Admin Setting)
     */
    public function admin()
    {
        $settings = TbmSetting::orderBy('id', 'asc')->get();
        return view('settings.admin', compact('settings'));
    }

    public function storeAdmin(Request $request)
    {
        $request->validate([
            'ip' => 'required',
            'port' => 'required',
            'keterangan' => 'required',
        ]);

        TbmSetting::create([
            'ip' => $request->ip,
            'port' => $request->port,
            'keterangan' => $request->keterangan,
            'st' => '0',
        ]);

        return redirect()->route('settings.admin')->with('success', 'Data PABX berhasil ditambahkan.');
    }

    public function updateAdmin(Request $request, $id)
    {
        $request->validate([
            'ip' => 'required',
            'port' => 'required',
            'keterangan' => 'required',
        ]);

        $setting = TbmSetting::findOrFail($id);
        $setting->update([
            'ip' => $request->ip,
            'port' => $request->port,
            'keterangan' => $request->keterangan,
        ]);

        return redirect()->route('settings.admin')->with('success', 'Data PABX berhasil diperbarui.');
    }

    public function destroyAdmin($id)
    {
        $setting = TbmSetting::findOrFail($id);
        $setting->delete();

        return redirect()->route('settings.admin')->with('success', 'Data PABX berhasil dihapus.');
    }

    /**
     * 2. Business Phone No
     */
    public function businessPhone()
    {
        $businesses = TbmBisnis::whereNull('tgldelete')->orderBy('id', 'asc')->get();
        return view('settings.business_phone', compact('businesses'));
    }

    public function storeBusinessPhone(Request $request)
    {
        $request->validate([
            'nama' => 'required',
            'notelp' => 'required',
            'alamat' => 'nullable',
        ]);

        TbmBisnis::create([
            'nama' => $request->nama,
            'notelp' => $request->notelp,
            'alamat' => $request->alamat,
            'st' => '0',
            'tgltambah' => now(),
            'optambah' => auth()->user()->nama ?? 'Administrator',
        ]);

        return redirect()->route('settings.business-phone')->with('success', 'Data Business Phone berhasil ditambahkan.');
    }

    public function updateBusinessPhone(Request $request, $id)
    {
        $request->validate([
            'nama' => 'required',
            'notelp' => 'required',
            'alamat' => 'nullable',
        ]);

        $biz = TbmBisnis::findOrFail($id);
        $biz->update([
            'nama' => $request->nama,
            'notelp' => $request->notelp,
            'alamat' => $request->alamat,
            'tglubah' => now(),
            'opubah' => auth()->user()->nama ?? 'Administrator',
        ]);

        return redirect()->route('settings.business-phone')->with('success', 'Data Business Phone berhasil diperbarui.');
    }

    public function destroyBusinessPhone($id)
    {
        $biz = TbmBisnis::findOrFail($id);
        $biz->update([
            'tgldelete' => now(),
            'opdelete' => auth()->user()->nama ?? 'Administrator',
        ]);

        return redirect()->route('settings.business-phone')->with('success', 'Data Business Phone berhasil dihapus.');
    }

    /**
     * 3. Division - Department (Department Group)
     */
    public function departmentGroup()
    {
        $groups = TbmGroupDepartment::whereNull('tgldelete')->orderBy('idgroup', 'asc')->get();
        return view('settings.department_group', compact('groups'));
    }

    public function storeDepartmentGroup(Request $request)
    {
        $request->validate([
            'groupcode' => 'required',
            'namadivisi' => 'required',
            'namagroup' => 'required',
        ]);

        TbmGroupDepartment::create([
            'groupcode' => $request->groupcode,
            'namadivisi' => $request->namadivisi,
            'namagroup' => $request->namagroup,
            'st' => '1',
            'tgltambah' => now(),
            'optambah' => auth()->user()->nama ?? 'Administrator',
        ]);

        return redirect()->route('settings.department-group')->with('success', 'Data Department Group berhasil ditambahkan.');
    }

    public function updateDepartmentGroup(Request $request, $id)
    {
        $request->validate([
            'groupcode' => 'required',
            'namadivisi' => 'required',
            'namagroup' => 'required',
        ]);

        $group = TbmGroupDepartment::findOrFail($id);
        $group->update([
            'groupcode' => $request->groupcode,
            'namadivisi' => $request->namadivisi,
            'namagroup' => $request->namagroup,
        ]);

        return redirect()->route('settings.department-group')->with('success', 'Data Department Group berhasil diperbarui.');
    }

    public function destroyDepartmentGroup($id)
    {
        $group = TbmGroupDepartment::findOrFail($id);
        $group->update([
            'tgldelete' => now(),
            'opdelete' => auth()->user()->nama ?? 'Administrator',
        ]);

        return redirect()->route('settings.department-group')->with('success', 'Data Department Group berhasil dihapus.');
    }

    /**
     * 4. Master Phone Code
     */
    /**
     * 4. Master Phone Code / Extension
     */
    public function phoneCode()
    {
        $departments = TbmDepartment::with('group')->whereNull('tgldelete')->orderBy('extcode', 'asc')->get();
        $groups = TbmGroupDepartment::whereNull('tgldelete')->orderBy('namagroup', 'asc')->get();
        return view('settings.phone_code', compact('departments', 'groups'));
    }

    public function storePhoneCode(Request $request)
    {
        $request->validate([
            'extcode' => 'required',
            'extname' => 'required|string|max:100',
        ]);

        $group = null;
        if ($request->filled('idgroup')) {
            $group = TbmGroupDepartment::find($request->idgroup);
        }

        TbmDepartment::create([
            'extcode' => $request->extcode,
            'extname' => $request->extname,
            'idgroup' => $request->idgroup ?: null,
            'divisi' => $request->divisi ?: ($group->namadivisi ?? 'AP'),
            'jabatan' => $request->jabatan ?: ($group->namagroup ?? 'AP'),
            'nik' => $request->nik,
            'phonepassword' => $request->phonepassword,
            'additional' => 0,
            'budget' => 0,
            'st' => '0',
            'tgltambah' => now(),
            'optambah' => auth()->user()->nama ?? 'Administrator',
        ]);

        return redirect()->route('settings.phone-code')->with('success', 'Data Master Phone Code berhasil ditambahkan.');
    }

    public function updatePhoneCode(Request $request, $id)
    {
        $dept = TbmDepartment::findOrFail($id);
        $request->validate([
            'extcode' => 'required',
            'extname' => 'required|string|max:100',
        ]);

        $group = null;
        if ($request->filled('idgroup')) {
            $group = TbmGroupDepartment::find($request->idgroup);
        }

        $dept->update([
            'extcode' => $request->extcode,
            'extname' => $request->extname,
            'idgroup' => $request->idgroup ?: null,
            'divisi' => $request->divisi ?: ($group->namadivisi ?? $dept->divisi),
            'jabatan' => $request->jabatan ?: ($group->namagroup ?? $dept->jabatan),
            'nik' => $request->nik,
            'phonepassword' => $request->phonepassword ?: $dept->phonepassword,
            'tgledit' => now(),
            'opedit' => auth()->user()->nama ?? 'Administrator',
        ]);

        return redirect()->route('settings.phone-code')->with('success', 'Data Master Phone Code berhasil diperbarui.');
    }

    public function destroyPhoneCode($id)
    {
        $dept = TbmDepartment::findOrFail($id);
        $dept->update([
            'tgldelete' => now(),
            'opdelete' => now(),
        ]);

        return redirect()->route('settings.phone-code')->with('success', 'Data Master Phone Code berhasil dihapus.');
    }

    /**
     * 5. Master Rate
     */
    public function masterRate()
    {
        $rates = TbmTarif::orderBy('idtarif', 'asc')->get();
        $machines = TbmSetting::orderBy('id', 'asc')->get();
        return view('settings.master_rate', compact('rates', 'machines'));
    }

    public function storeMasterRate(Request $request)
    {
        $request->validate([
            'ratecode' => 'required',
            'catatan' => 'required',
            'mindurasi' => 'required|numeric',
        ]);

        $machine = TbmSetting::find($request->idmesin);
        $namaMesin = $machine ? $machine->keterangan : ($request->namamesin ?: 'MESIN 1');

        TbmTarif::create([
            'idmesin' => $request->idmesin ?: 1,
            'namamesin' => $namaMesin,
            'ratecode' => $request->ratecode,
            'catatan' => $request->catatan,
            'mindurasi' => $request->mindurasi,
            'st' => '0',
            'tgltambah' => now(),
            'optambah' => auth()->user()->nama ?? 'Administrator',
        ]);

        return redirect()->route('settings.rate')->with('success', 'Data Master Rate berhasil ditambahkan.');
    }

    public function updateMasterRate(Request $request, $id)
    {
        $rate = TbmTarif::findOrFail($id);
        $request->validate([
            'ratecode' => 'required',
            'catatan' => 'required',
            'mindurasi' => 'required|numeric',
        ]);

        $machine = TbmSetting::find($request->idmesin);
        $namaMesin = $machine ? $machine->keterangan : ($request->namamesin ?: $rate->namamesin);

        $rate->update([
            'idmesin' => $request->idmesin ?: $rate->idmesin,
            'namamesin' => $namaMesin,
            'ratecode' => $request->ratecode,
            'catatan' => $request->catatan,
            'mindurasi' => $request->mindurasi,
            'tgledit' => now(),
            'opedit' => auth()->user()->nama ?? 'Administrator',
        ]);

        return redirect()->route('settings.rate')->with('success', 'Data Master Rate berhasil diperbarui.');
    }

    public function destroyMasterRate($id)
    {
        $rate = TbmTarif::findOrFail($id);
        $rate->delete();

        return redirect()->route('settings.rate')->with('success', 'Data Master Rate berhasil dihapus.');
    }

    /**
     * 6. Master Special Rate
     */
    public function masterSpecialRate()
    {
        $specialRates = TbmTarifKhusus::whereNull('tgldelete')->orderBy('idharga', 'asc')->get();
        $rates = TbmTarif::orderBy('ratecode', 'asc')->get();
        return view('settings.master_special_rate', compact('specialRates', 'rates'));
    }

    public function storeMasterSpecialRate(Request $request)
    {
        $request->validate([
            'namatarif' => 'required',
            'idtarif' => 'required',
            'tglmulai' => 'required|date',
            'tglselesai' => 'required|date',
        ]);

        TbmTarifKhusus::create([
            'idtarif' => $request->idtarif,
            'namatarif' => $request->namatarif,
            'keterangan' => $request->keterangan,
            'tglmulai' => $request->tglmulai,
            'tglselesai' => $request->tglselesai,
            'mindurasi' => $request->mindurasi ?: 0,
            'st' => '1',
            'tgltambah' => now(),
            'optambah' => auth()->user()->nama ?? 'Administrator',
        ]);

        return redirect()->route('settings.special-rate')->with('success', 'Data Master Special Rate berhasil ditambahkan.');
    }

    public function updateMasterSpecialRate(Request $request, $id)
    {
        $sr = TbmTarifKhusus::findOrFail($id);
        $request->validate([
            'namatarif' => 'required',
            'idtarif' => 'required',
            'tglmulai' => 'required|date',
            'tglselesai' => 'required|date',
        ]);

        $sr->update([
            'idtarif' => $request->idtarif,
            'namatarif' => $request->namatarif,
            'keterangan' => $request->keterangan,
            'tglmulai' => $request->tglmulai,
            'tglselesai' => $request->tglselesai,
            'mindurasi' => $request->mindurasi ?: 0,
            'tglubah' => now(),
            'opubah' => auth()->user()->nama ?? 'Administrator',
        ]);

        return redirect()->route('settings.special-rate')->with('success', 'Data Master Special Rate berhasil diperbarui.');
    }

    public function destroyMasterSpecialRate($id)
    {
        $sr = TbmTarifKhusus::findOrFail($id);
        $sr->update([
            'tgldelete' => now(),
            'opdelete' => auth()->user()->nama ?? 'Administrator',
        ]);

        return redirect()->route('settings.special-rate')->with('success', 'Data Master Special Rate berhasil dihapus.');
    }

    /**
     * 7. Master User
     */
    public function masterUser()
    {
        $users = TblUser::with('level')->where('st_del', 0)->orderBy('iduser', 'asc')->get();
        $levels = TbmLevel::orderBy('id', 'asc')->get();
        return view('settings.master_user', compact('users', 'levels'));
    }

    public function storeMasterUser(Request $request)
    {
        $request->validate([
            'username' => 'required|string|unique:tbl_user,username',
            'nama' => 'required|string|max:100',
            'password' => 'required|min:4',
            'idlevel' => 'required',
        ]);

        $level = TbmLevel::find($request->idlevel);
        $levelName = $level ? $level->level : 'User';

        TblUser::create([
            'username' => $request->username,
            'nama' => $request->nama,
            'password' => bcrypt($request->password),
            'idcabang' => 1,
            'idlevel' => $request->idlevel,
            'leveluser' => $levelName,
            'telp' => $request->telp,
            'blokir' => $request->blokir ?? 'N',
            'aktif' => 1,
            'st_del' => 0,
        ]);

        return redirect()->route('settings.user')->with('success', 'Data Master User berhasil ditambahkan.');
    }

    public function updateMasterUser(Request $request, $id)
    {
        $user = TblUser::findOrFail($id);
        $request->validate([
            'username' => 'required|string|unique:tbl_user,username,' . $id . ',iduser',
            'nama' => 'required|string|max:100',
            'idlevel' => 'required',
        ]);

        $level = TbmLevel::find($request->idlevel);
        $levelName = $level ? $level->level : ($user->leveluser ?: 'User');

        $updateData = [
            'username' => $request->username,
            'nama' => $request->nama,
            'idlevel' => $request->idlevel,
            'leveluser' => $levelName,
            'telp' => $request->telp,
            'blokir' => $request->blokir ?? 'N',
        ];

        if ($request->filled('password')) {
            $updateData['password'] = bcrypt($request->password);
        }

        $user->update($updateData);

        return redirect()->route('settings.user')->with('success', 'Data Master User berhasil diperbarui.');
    }

    public function destroyMasterUser($id)
    {
        $user = TblUser::findOrFail($id);
        $user->update(['st_del' => 1]);

        return redirect()->route('settings.user')->with('success', 'Data Master User berhasil dihapus.');
    }

    /**
     * 8. Prefix Code
     */
    public function prefixCode()
    {
        $prefixes = TbmPrefix::with('zona')->whereNull('tgldelete')->orderBy('kodeprefix', 'asc')->get();
        $zonas = TbmZona::orderBy('idzone', 'asc')->get();
        return view('settings.prefix_code', compact('prefixes', 'zonas'));
    }

    public function storePrefixCode(Request $request)
    {
        $request->validate([
            'kodeprefix' => 'required|string|max:20',
            'keterangan' => 'required|string|max:200',
            'kelompok' => 'required|in:CELL,PHONE',
        ]);

        TbmPrefix::create([
            'kodeprefix' => $request->kodeprefix,
            'distrik' => $request->distrik,
            'keterangan' => $request->keterangan,
            'kelompok' => $request->kelompok,
            'idzone' => $request->idzone ?: null,
            'st' => '1',
            'tgltambah' => now(),
            'optambah' => auth()->user()->nama ?? 'Administrator',
        ]);

        return redirect()->route('settings.prefix')->with('success', 'Data Prefix Code berhasil ditambahkan.');
    }

    public function updatePrefixCode(Request $request, $id)
    {
        $prefix = TbmPrefix::findOrFail($id);
        $request->validate([
            'kodeprefix' => 'required|string|max:20',
            'keterangan' => 'required|string|max:200',
            'kelompok' => 'required|in:CELL,PHONE',
        ]);

        $prefix->update([
            'kodeprefix' => $request->kodeprefix,
            'distrik' => $request->distrik,
            'keterangan' => $request->keterangan,
            'kelompok' => $request->kelompok,
            'idzone' => $request->idzone ?: null,
            'tglubah' => now(),
            'opubah' => auth()->user()->nama ?? 'Administrator',
        ]);

        return redirect()->route('settings.prefix')->with('success', 'Data Prefix Code berhasil diperbarui.');
    }

    public function destroyPrefixCode($id)
    {
        $prefix = TbmPrefix::findOrFail($id);
        $prefix->update([
            'tgldelete' => now(),
            'opdelete' => auth()->user()->nama ?? 'Administrator',
        ]);

        return redirect()->route('settings.prefix')->with('success', 'Data Prefix Code berhasil dihapus.');
    }
}
