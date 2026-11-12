<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\Carrier;
use App\Models\Location;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    /**
     * Halaman Utama Admin Console (Slide 05)
     */
    public function index()
    {
        // Proteksi Otorisasi: Wajib Role Admin
        if (!Auth::user()->isAdmin()) {
            abort(403, 'Akses Ditolak: Hanya Administrator yang dapat mengakses konsol ini.');
        }

        $auditLogs = AuditLog::with('user')->latest('id')->paginate(15);
        $carriers = Carrier::latest('id')->get();
        $locations = Location::orderBy('name')->get();

        return view('admin.index', compact('auditLogs', 'carriers', 'locations'));
    }

    /**
     * Tambah Armada Carrier Baru (Slide 05)
     */
    public function storeCarrier(Request $request)
    {
        if (!Auth::user()->isAdmin()) { abort(403); }

        $validated = $request->validate([
            'name'          => 'required|string|max:255',
            'code'          => 'required|string|max:10|unique:carriers,code',
            'service_type'  => 'required|in:SEA_FREIGHT,AIR_FREIGHT,MULTIMODAL',
            'contact_email' => 'nullable|email',
            'contact_phone' => 'nullable|string|max:50',
        ]);

        Carrier::create($validated);

        return redirect()->route('admin.index')
            ->with('success', "Carrier '{$validated['name']}' berhasil ditambahkan!");
    }

    /**
     * Tambah Pelabuhan / Lokasi Baru (Slide 05)
     */
    public function storeLocation(Request $request)
    {
        if (!Auth::user()->isAdmin()) { abort(403); }

        $validated = $request->validate([
            'name'         => 'required|string|max:255',
            'code'         => 'required|string|max:10|unique:locations,code',
            'type'         => 'required|in:PORT,AIRPORT,WAREHOUSE,CHECKPOINT',
            'city'         => 'required|string|max:100',
            'country'      => 'required|string|max:100',
            'country_code' => 'required|string|max:3',
            'latitude'     => 'required|numeric|between:-90,90',
            'longitude'    => 'required|numeric|between:-180,180',
        ]);

        Location::create($validated);

        return redirect()->route('admin.index')
            ->with('success', "Lokasi Pelabuhan '{$validated['name']}' berhasil didaftarkan!");
    }
}