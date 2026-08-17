<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TransportRoute;
use App\Models\TransportStop;
use App\Models\TransportVehicle;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TransportController extends Controller
{
    public function index(): View
    {
        $vehicles = TransportVehicle::with('routes')->get();
        $routes = TransportRoute::with('vehicle', 'stops')->get();

        return view('admin.transport.index', compact('vehicles', 'routes'));
    }

    public function create(): View
    {
        return view('admin.transport.create_vehicle');
    }

    public function storeVehicle(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'plate_number' => 'required|string|max:20',
            'model' => 'nullable|string',
            'capacity' => 'required|integer|min:1',
            'driver_name' => 'nullable|string',
            'driver_phone' => 'nullable|string',
            'active' => 'boolean',
        ]);

        $validated['active'] = $request->boolean('active');

        $vehicle = TransportVehicle::create($validated);

        if ($request->filled('stops')) {
            foreach (range(1, $request->input('stops')) as $num) {
                TransportStop::create([
                    'route_id' => null,
                    'name' => 'وقف '.$num,
                    'pickup_time' => null,
                    'sequence' => $num,
                ]);
            }
        }

        return back()->with('success', 'تم إضافة المركبة بنجاح.');
    }

    public function storeRoute(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'vehicle_id' => 'exists:transport_vehicles,id',
            'description' => 'nullable|string',
            'active' => 'boolean',
        ]);

        $validated['active'] = $request->boolean('active');

        TransportRoute::create($validated);

        return back()->with('success', 'تم إضافة المسار بنجاح.');
    }
}
