<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bus_locations', function (Blueprint $t) {
            $t->id();
            $t->foreignId('vehicle_id')->constrained('transport_vehicles')->cascadeOnDelete();
            $t->foreignId('route_id')->nullable()->constrained('transport_routes')->nullOnDelete();
            $t->decimal('latitude', 10, 7);
            $t->decimal('longitude', 10, 7);
            $t->smallInteger('heading')->nullable();
            $t->smallInteger('speed')->nullable();
            $t->timestamp('captured_at')->useCurrent();
            $t->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bus_locations');
    }
};
