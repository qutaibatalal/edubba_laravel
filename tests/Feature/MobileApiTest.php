<?php

namespace Tests\Feature;

use App\Models\ApiUser;
use App\Models\Batch;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\Student;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MobileApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_returns_token_for_valid_student(): void
    {
        $student = Student::create([
            'name' => 'أحمد',
            'student_code' => 'ST-100',
            'state' => Student::STATE_ADMITTED,
        ]);

        $user = ApiUser::create([
            'username' => 'ahmed',
            'password' => 'secret123',
            'role' => ApiUser::ROLE_STUDENT,
            'student_id' => $student->id,
            'active' => true,
        ]);

        $response = $this->postJson('/api/v1/login', [
            'username' => 'ahmed',
            'password' => 'secret123',
        ]);

        $response->assertStatus(200)
            ->assertJsonPath('status', 'success')
            ->assertJsonStructure(['data' => ['token']]);
    }

    public function test_login_rejects_wrong_password(): void
    {
        $user = ApiUser::create([
            'username' => 'ahmed',
            'password' => 'secret123',
            'role' => ApiUser::ROLE_STUDENT,
            'active' => true,
        ]);

        $this->postJson('/api/v1/login', [
            'username' => 'ahmed',
            'password' => 'wrongpass',
        ])->assertStatus(401);
    }

    public function test_student_profile_isolated_to_own_student(): void
    {
        $other = Student::create([
            'name' => 'شخص آخر',
            'student_code' => 'ST-200',
            'state' => Student::STATE_ADMITTED,
        ]);

        $student = Student::create([
            'name' => 'زينب',
            'student_code' => 'ST-201',
            'state' => Student::STATE_ADMITTED,
        ]);

        $user = ApiUser::create([
            'username' => 'zainab',
            'password' => 'secret123',
            'role' => ApiUser::ROLE_STUDENT,
            'student_id' => $student->id,
            'active' => true,
        ]);

        $token = $user->createToken('test')->plainTextToken;

        $this->getJson('/api/v1/student/profile', ['Authorization' => "Bearer $token"])
            ->assertOk()
            ->assertJsonPath('data.student_code', 'ST-201')
            ->assertJsonMissing(['ST-200']);
    }

    public function test_unauthenticated_request_rejected(): void
    {
        $this->getJson('/api/v1/student/profile')->assertStatus(401);
    }

    public function test_zaincash_webhook_settles_open_invoice(): void
    {
        $batch = Batch::create(['name' => 'صف A']);
        $student = Student::create([
            'name' => 'أحمد',
            'student_code' => 'ST-300',
            'state' => Student::STATE_ADMITTED,
            'batch_id' => $batch->id,
        ]);

        $invoice = Invoice::create([
            'number' => 'INV-500',
            'student_id' => $student->id,
            'date' => today()->toDateString(),
            'due_date' => now()->addDays(5)->toDateString(),
            'subtotal' => 1000,
            'tax' => 0,
            'total' => 1000,
            'paid' => 0,
            'balance' => 1000,
            'state' => Invoice::STATE_OPEN,
        ]);

        config(['zaincash.merchant_secret' => 'test-secret']);

        // Compute a valid signature using the same HMAC scheme verifyCallback expects.
        $payload = [
            'merchant_id' => 'm1',
            'status' => 'success',
            'transaction_id' => 'TXN-1',
            'order_id' => 'INV-500',
            'amount' => '1000',
            'msisdn' => '9647901234567',
        ];
        $payload['signature'] = hash_hmac(
            'sha256',
            implode('', [$payload['merchant_id'], $payload['status'], $payload['transaction_id'], $payload['order_id'], $payload['amount'], $payload['msisdn']]),
            'test-secret'
        );

        $this->postJson('/api/v1/payments/zaincash/callback', $payload)
            ->assertOk();

        $this->assertDatabaseHas('payments', [
            'gateway' => 'zaincash',
            'transaction_id' => 'TXN-1',
            'state' => Payment::STATE_DONE,
        ]);

        $this->assertDatabaseHas('invoices', [
            'id' => $invoice->id,
            'state' => Invoice::STATE_PAID,
            'paid' => 1000,
        ]);
    }

    public function test_zaincash_webhook_rejects_bad_signature(): void
    {
        $this->postJson('/api/v1/payments/zaincash/callback', [
            'merchant_id' => 'm1',
            'status' => 'success',
            'transaction_id' => 'TXN-2',
            'order_id' => 'INV-999',
            'amount' => '1000',
            'msisdn' => '9647901234567',
            'signature' => 'forged',
        ])->assertStatus(403);
    }
}
