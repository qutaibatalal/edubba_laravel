<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('fee_structures', function (Blueprint $t) {
            $t->id();
            $t->string('name');
            $t->foreignId('program_id')->nullable()->constrained('programs')->nullOnDelete();
            $t->foreignId('batch_id')->nullable()->constrained('batches')->nullOnDelete();
            $t->foreignId('academic_year_id')->nullable()->constrained('academic_years')->nullOnDelete();
            $t->boolean('active')->default(true);
            $t->timestamps();
        });

        Schema::create('fee_lines', function (Blueprint $t) {
            $t->id();
            $t->foreignId('fee_structure_id')->constrained('fee_structures')->cascadeOnDelete();
            $t->string('name');
            $t->decimal('amount', 12, 2)->default(0);
            $t->string('type')->nullable();
            $t->integer('sequence')->default(0);
            $t->timestamps();
        });

        Schema::create('invoices', function (Blueprint $t) {
            $t->id();
            $t->string('number')->nullable();
            $t->foreignId('student_id')->nullable()->constrained('students')->nullOnDelete();
            $t->foreignId('parent_id')->nullable()->constrained('parents')->nullOnDelete();
            $t->foreignId('academic_year_id')->nullable()->constrained('academic_years')->nullOnDelete();
            $t->date('date');
            $t->date('due_date')->nullable();
            $t->decimal('subtotal', 12, 2)->default(0);
            $t->decimal('tax', 12, 2)->default(0);
            $t->decimal('total', 12, 2)->default(0);
            $t->decimal('paid', 12, 2)->default(0);
            $t->decimal('balance', 12, 2)->default(0);
            $t->string('state')->default('draft');
            $t->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $t->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $t->timestamps();
            $t->softDeletes();
        });

        Schema::create('invoice_lines', function (Blueprint $t) {
            $t->id();
            $t->foreignId('invoice_id')->constrained('invoices')->cascadeOnDelete();
            $t->string('description');
            $t->decimal('qty', 8, 2)->default(1);
            $t->decimal('unit_price', 12, 2)->default(0);
            $t->decimal('amount', 12, 2)->default(0);
            $t->timestamps();
        });

        Schema::create('payments', function (Blueprint $t) {
            $t->id();
            $t->string('reference')->nullable();
            $t->foreignId('invoice_id')->nullable()->constrained('invoices')->nullOnDelete();
            $t->foreignId('student_id')->nullable()->constrained('students')->nullOnDelete();
            $t->foreignId('parent_id')->nullable()->constrained('parents')->nullOnDelete();
            $t->decimal('amount', 12, 2)->default(0);
            $t->string('method');
            $t->string('gateway')->nullable();
            $t->string('transaction_id')->nullable();
            $t->string('state')->default('draft');
            $t->date('date');
            $t->timestamps();
        });

        Schema::create('receipts', function (Blueprint $t) {
            $t->id();
            $t->string('receipt_no')->nullable();
            $t->foreignId('payment_id')->constrained('payments')->cascadeOnDelete();
            $t->foreignId('invoice_id')->nullable()->constrained('invoices')->nullOnDelete();
            $t->date('date');
            $t->decimal('amount', 12, 2)->default(0);
            $t->string('document')->nullable();
            $t->timestamps();
        });

        Schema::create('fee_waivers', function (Blueprint $t) {
            $t->id();
            $t->foreignId('invoice_id')->nullable()->constrained('invoices')->nullOnDelete();
            $t->foreignId('student_id')->nullable()->constrained('students')->nullOnDelete();
            $t->string('reason');
            $t->decimal('amount', 12, 2)->default(0);
            $t->string('state')->default('pending');
            $t->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
            $t->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fee_waivers');
        Schema::dropIfExists('receipts');
        Schema::dropIfExists('payments');
        Schema::dropIfExists('invoice_lines');
        Schema::dropIfExists('invoices');
        Schema::dropIfExists('fee_lines');
        Schema::dropIfExists('fee_structures');
    }
};
