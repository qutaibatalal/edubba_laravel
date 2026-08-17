<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\BusLocation;
use App\Models\TransportAssignment;
use App\Models\TransportRoute;
use App\Models\TransportVehicle;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BusTrackingController extends Controller
{
    /**
     * POST /v1/bus/{vehicle}/location
     *
     * Submit a live GPS fix from the driver app.
     */
    public function updateLocation(Request $request, int $vehicleId): JsonResponse
    {
        $request->validate([
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'heading' => 'nullable|integer|between:0,360',
            'speed' => 'nullable|integer|min:0',
        ]);

        $vehicle = TransportVehicle::findOrFail($vehicleId);

        BusLocation::create([
            'vehicle_id' => $vehicle->id,
            'route_id' => $vehicle->routes()->latest('id')->value('id'),
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'heading' => $request->heading,
            'speed' => $request->speed,
            'captured_at' => now(),
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'تم تحديث موقع الحافلة',
        ]);
    }

    /**
     * GET /v1/parent/bus-tracking
     *
     * Parents/students receive every active vehicle with its latest point,
     * the route name and ordered stops so the app can render Google Maps.
     */
    public function tracking(Request $request): JsonResponse
    {
        $user = $request->user();
        $student = $user->student;

        $routes = TransportRoute::with('vehicle', 'stops')
            ->where('active', true)
            ->get();

        $assignments = $student
            ? TransportAssignment::where('student_id', $student->id)->pluck('route_id')
            : collect([]);

        $vehicles = $routes->pluck('vehicle_id')->filter()->unique();

        $locations = BusLocation::whereIn('vehicle_id', $vehicles)
            ->get()
            ->groupBy('vehicle_id')
            ->mapWithKeys(fn ($group, $vid) => [
                $vid => $group->sortByDesc('captured_at')->first(),
            ]);

        $data = $routes->map(function (TransportRoute $route) use ($locations, $assignments) {
            $location = $locations[$route->vehicle_id] ?? null;
            $latest = $location ? [
                'lat' => (float) $location->latitude,
                'lng' => (float) $location->longitude,
                'heading' => $location->heading,
                'speed' => $location->speed,
                'captured_at' => $location->captured_at?->toDateTimeString(),
            ] : null;

            return [
                'route_id' => $route->id,
                'route_name' => $route->name,
                'vehicle' => $route->vehicle
                    ? ['plate_number' => $route->vehicle->plate_number, 'driver_name' => $route->vehicle->driver_name, 'driver_phone' => $route->vehicle->driver_phone]
                    : null,
                'stops' => $route->stops->sortBy('sequence')->map(fn ($s) => [
                    'id' => $s->id,
                    'name' => $s->name,
                    'sequence' => $s->sequence,
                    'pickup_time' => $s->pickup_time?->toTimeString(),
                ]),
                'current_location' => $latest,
                'assigned' => $assignments->contains($route->id),
            ];
        });

        return response()->json([
            'status' => 'success',
            'data' => $data->values(),
        ]);
    }
}
