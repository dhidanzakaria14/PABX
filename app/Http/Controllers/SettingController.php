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
    public function phoneCode()
    {
        $departments = TbmDepartment::with('group')->whereNull('tgldelete')->orderBy('extcode', 'asc')->get();
        $groups = TbmGroupDepartment::whereNull('tgldelete')->get();
        return view('settings.phone_code', compact('departments', 'groups'));
    }

    /**
     * 5. Master Rate
     */
    public function masterRate()
    {
        $rates = TbmTarif::orderBy('idtarif', 'asc')->get();
        return view('settings.master_rate', compact('rates'));
    }

    /**
     * 6. Master Special Rate
     */
    public function masterSpecialRate()
    {
        $specialRates = TbmTarifKhusus::orderBy('idharga', 'asc')->get();
        return view('settings.master_special_rate', compact('specialRates'));
    }

    /**
     * 7. Master User
     */
    public function masterUser()
    {
        $users = TblUser::with('level')->where('st_del', 0)->orderBy('iduser', 'asc')->get();
        return view('settings.master_user', compact('users'));
    }

    /**
     * 8. Prefix Code
     */
    public function prefixCode()
    {
        $prefixes = TbmPrefix::with('zona')->whereNull('tgldelete')->orderBy('kodeprefix', 'asc')->get();
        return view('settings.prefix_code', compact('prefixes'));
    }
}
