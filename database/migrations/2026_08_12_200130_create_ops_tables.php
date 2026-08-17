<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('library_books', function (Blueprint $t) {
            $t->id();
            $t->string('title');
            $t->string('author')->nullable();
            $t->string('isbn')->nullable();
            $t->string('category')->nullable();
            $t->integer('total_qty')->default(1);
            $t->integer('available_qty')->default(1);
            $t->boolean('active')->default(true);
            $t->timestamps();
        });

        Schema::create('library_memberships', function (Blueprint $t) {
            $t->id();
            $t->foreignId('student_id')->constrained('students')->cascadeOnDelete();
            $t->date('start_date');
            $t->date('end_date')->nullable();
            $t->string('state')->default('active');
            $t->timestamps();
        });

        Schema::create('library_issues', function (Blueprint $t) {
            $t->id();
            $t->foreignId('book_id')->constrained('library_books')->cascadeOnDelete();
            $t->foreignId('student_id')->nullable()->constrained('students')->nullOnDelete();
            $t->date('issue_date');
            $t->date('due_date');
            $t->date('return_date')->nullable();
            $t->decimal('fine', 10, 2)->default(0);
            $t->string('state')->default('issued');
            $t->timestamps();
        });

        Schema::create('library_fines', function (Blueprint $t) {
            $t->id();
            $t->foreignId('library_issue_id')->constrained('library_issues')->cascadeOnDelete();
            $t->decimal('amount', 10, 2)->default(0);
            $t->string('reason')->nullable();
            $t->string('state')->default('pending');
            $t->timestamps();
        });

        Schema::create('library_returns', function (Blueprint $t) {
            $t->id();
            $t->foreignId('library_issue_id')->constrained('library_issues')->cascadeOnDelete();
            $t->date('return_date');
            $t->decimal('fine_applied', 10, 2)->default(0);
            $t->text('note')->nullable();
            $t->timestamps();
        });

        Schema::create('transport_vehicles', function (Blueprint $t) {
            $t->id();
            $t->string('plate_number')->unique();
            $t->string('model')->nullable();
            $t->integer('capacity')->default(0);
            $t->string('driver_name')->nullable();
            $t->string('driver_phone')->nullable();
            $t->boolean('active')->default(true);
            $t->timestamps();
        });

        Schema::create('transport_routes', function (Blueprint $t) {
            $t->id();
            $t->string('name');
            $t->foreignId('vehicle_id')->nullable()->constrained('transport_vehicles')->nullOnDelete();
            $t->text('description')->nullable();
            $t->boolean('active')->default(true);
            $t->timestamps();
        });

        Schema::create('transport_stops', function (Blueprint $t) {
            $t->id();
            $t->foreignId('route_id')->constrained('transport_routes')->cascadeOnDelete();
            $t->string('name');
            $t->time('pickup_time')->nullable();
            $t->integer('sequence')->default(0);
            $t->timestamps();
        });

        Schema::create('transport_assignments', function (Blueprint $t) {
            $t->id();
            $t->foreignId('student_id')->constrained('students')->cascadeOnDelete();
            $t->foreignId('route_id')->constrained('transport_routes')->cascadeOnDelete();
            $t->foreignId('stop_id')->nullable()->constrained('transport_stops')->nullOnDelete();
            $t->date('start_date');
            $t->date('end_date')->nullable();
            $t->string('state')->default('active');
            $t->unique(['student_id', 'route_id']);
            $t->timestamps();
        });

        Schema::create('hostels', function (Blueprint $t) {
            $t->id();
            $t->string('name');
            $t->text('address')->nullable();
            $t->string('warden_name')->nullable();
            $t->boolean('active')->default(true);
            $t->timestamps();
        });

        Schema::create('hostel_rooms', function (Blueprint $t) {
            $t->id();
            $t->foreignId('hostel_id')->constrained('hostels')->cascadeOnDelete();
            $t->string('room_no');
            $t->integer('capacity')->default(2);
            $t->integer('occupied')->default(0);
            $t->decimal('monthly_rent', 12, 2)->default(0);
            $t->string('state')->default('available');
            $t->timestamps();
        });

        Schema::create('hostel_allocations', function (Blueprint $t) {
            $t->id();
            $t->foreignId('room_id')->constrained('hostel_rooms')->cascadeOnDelete();
            $t->foreignId('student_id')->constrained('students')->cascadeOnDelete();
            $t->date('start_date');
            $t->date('end_date')->nullable();
            $t->string('state')->default('active');
            $t->timestamps();
        });

        Schema::create('store_categories', function (Blueprint $t) {
            $t->id();
            $t->string('name');
            $t->boolean('active')->default(true);
            $t->timestamps();
        });

        Schema::create('store_products', function (Blueprint $t) {
            $t->id();
            $t->foreignId('category_id')->nullable()->constrained('store_categories')->nullOnDelete();
            $t->string('name');
            $t->string('code')->nullable();
            $t->integer('stock_qty')->default(0);
            $t->decimal('unit_price', 12, 2)->default(0);
            $t->boolean('active')->default(true);
            $t->timestamps();
        });

        Schema::create('store_stocks', function (Blueprint $t) {
            $t->id();
            $t->foreignId('product_id')->constrained('store_products')->cascadeOnDelete();
            $t->integer('qty')->default(0);
            $t->string('type')->default('in');
            $t->date('date');
            $t->text('note')->nullable();
            $t->timestamps();
        });

        Schema::create('store_issues', function (Blueprint $t) {
            $t->id();
            $t->foreignId('product_id')->constrained('store_products')->cascadeOnDelete();
            $t->foreignId('student_id')->nullable()->constrained('students')->nullOnDelete();
            $t->integer('qty')->default(1);
            $t->date('date');
            $t->text('note')->nullable();
            $t->timestamps();
        });

        Schema::create('store_requests', function (Blueprint $t) {
            $t->id();
            $t->foreignId('product_id')->constrained('store_products')->cascadeOnDelete();
            $t->foreignId('student_id')->nullable()->constrained('students')->nullOnDelete();
            $t->integer('qty')->default(1);
            $t->string('state')->default('pending');
            $t->timestamps();
        });

        Schema::create('alumni', function (Blueprint $t) {
            $t->id();
            $t->foreignId('student_id')->nullable()->constrained('students')->nullOnDelete();
            $t->string('name');
            $t->string('graduation_year')->nullable();
            $t->string('contact')->nullable();
            $t->text('note')->nullable();
            $t->timestamps();
        });

        Schema::create('events', function (Blueprint $t) {
            $t->id();
            $t->string('name');
            $t->text('description')->nullable();
            $t->date('date');
            $t->string('venue')->nullable();
            $t->integer('capacity')->default(0);
            $t->string('state')->default('planned');
            $t->timestamps();
        });

        Schema::create('event_registrations', function (Blueprint $t) {
            $t->id();
            $t->foreignId('event_id')->constrained('events')->cascadeOnDelete();
            $t->foreignId('student_id')->nullable()->constrained('students')->nullOnDelete();
            $t->string('name')->nullable();
            $t->string('phone')->nullable();
            $t->string('state')->default('registered');
            $t->unique(['event_id', 'student_id']);
            $t->timestamps();
        });

        Schema::create('newsletters', function (Blueprint $t) {
            $t->id();
            $t->string('title');
            $t->text('body');
            $t->date('send_date')->nullable();
            $t->string('state')->default('draft');
            $t->timestamps();
        });

        Schema::create('id_cards', function (Blueprint $t) {
            $t->id();
            $t->foreignId('student_id')->nullable()->constrained('students')->nullOnDelete();
            $t->string('template')->nullable();
            $t->string('document')->nullable();
            $t->date('issue_date')->nullable();
            $t->string('state')->default('draft');
            $t->timestamps();
        });

        Schema::create('certificates', function (Blueprint $t) {
            $t->id();
            $t->foreignId('student_id')->nullable()->constrained('students')->nullOnDelete();
            $t->string('type');
            $t->string('document')->nullable();
            $t->date('issue_date')->nullable();
            $t->string('state')->default('draft');
            $t->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('certificates');
        Schema::dropIfExists('id_cards');
        Schema::dropIfExists('newsletters');
        Schema::dropIfExists('event_registrations');
        Schema::dropIfExists('events');
        Schema::dropIfExists('alumni');
        Schema::dropIfExists('store_requests');
        Schema::dropIfExists('store_issues');
        Schema::dropIfExists('store_stocks');
        Schema::dropIfExists('store_products');
        Schema::dropIfExists('store_categories');
        Schema::dropIfExists('hostel_allocations');
        Schema::dropIfExists('hostel_rooms');
        Schema::dropIfExists('hostels');
        Schema::dropIfExists('transport_assignments');
        Schema::dropIfExists('transport_stops');
        Schema::dropIfExists('transport_routes');
        Schema::dropIfExists('transport_vehicles');
        Schema::dropIfExists('library_returns');
        Schema::dropIfExists('library_fines');
        Schema::dropIfExists('library_issues');
        Schema::dropIfExists('library_memberships');
        Schema::dropIfExists('library_books');
    }
};
