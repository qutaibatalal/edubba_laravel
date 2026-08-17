<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Hostel;
use App\Models\HostelRoom;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class HostelController extends Controller
{
    public function index(): View
    {
        $hostels = Hostel::with('rooms')->get();

        return view('admin.hostel.index', compact('hostels'));
    }

    public function create(): View
    {
        return view('admin.hostel.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'address' => 'nullable|string',
            'warden_name' => 'nullable|string|max:50',
            'active' => 'boolean',
        ]);

        $hostel = Hostel::create($validated);

        if ($request->filled('rooms')) {
            foreach (range(1, $request->input('rooms')) as $num) {
                HostelRoom::create([
                    'hostel_id' => $hostel->id,
                    'room_no' => 'غرفة '.$num,
                    'capacity' => 2,
                    'occupied' => 0,
                    'monthly_rent' => 0,
                    'state' => HostelRoom::STATE_AVAILABLE,
                ]);
            }
        }

        return back()->with('success', 'تم إنشاء السكن بنجاح.');
    }
}
