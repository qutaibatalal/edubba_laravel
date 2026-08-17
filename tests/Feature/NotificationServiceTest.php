<?php

namespace Tests\Feature;

use App\Http\Controllers\Api\AttendanceController;
use App\Jobs\SendNotificationJob;
use App\Models\AttendanceLine;
use App\Models\AttendanceSheet;
use App\Models\Batch;
use App\Models\Student;
use App\Services\NotificationService;
use App\Services\SmsService;
use App\Services\WhatsAppService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Queue;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Tests\TestCase;

class NotificationServiceTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        config(['notifications.sms.provider' => 'mock']);
        config(['notifications.whatsapp.provider' => 'mock']);
    }

    public function test_sms_mock_provider_logs_and_returns_true(): void
    {
        Log::spy();
        $this->assertTrue((new SmsService)->send('07901234567', 'رسالة اختبار'));
    }

    public function test_sms_normalizes_iraqi_local_number(): void
    {
        $service = new SmsService;
        $ref = new \ReflectionMethod($service, 'normalizeNumber');
        $ref->setAccessible(true);

        $this->assertSame('9647901234567', $ref->invoke($service, '07901234567'));
        $this->assertSame('9647901234567', $ref->invoke($service, '9647901234567'));
    }

    public function test_whatsapp_mock_provider_returns_true(): void
    {
        Log::spy();
        $service = new WhatsAppService;
        $this->assertTrue($service->send('07901234567', 'رسالة واتساب'));
        $this->assertTrue($service->sendTemplate('07901234567', 'attendance_alert', 'ar', ['name' => 'أحمد']));
    }

    public function test_notification_service_creates_log_and_dispatches_job(): void
    {
        Queue::fake();

        $student = Student::create([
            'name' => 'أحمد',
            'student_code' => 'ST-001',
            'state' => Student::STATE_ADMITTED,
        ]);

        $log = NotificationService::send('whatsapp', '07901234567', 'نص', $student);

        $this->assertDatabaseHas('notification_logs', [
            'id' => $log->id,
            'channel' => 'whatsapp',
            'student_id' => $student->id,
        ]);

        Queue::assertPushed(SendNotificationJob::class);
    }

    public function test_absence_alerts_dispatches_job(): void
    {
        Queue::fake();

        $batch = Batch::create(['name' => 'السادس علمي']);
        $student = Student::create([
            'name' => 'محمد',
            'student_code' => 'ST-002',
            'state' => Student::STATE_ADMITTED,
            'batch_id' => $batch->id,
            'mobile' => '07901234567',
        ]);

        // Create a done attendance sheet with an ABSENT line so the student is below threshold.
        $sheet = AttendanceSheet::create([
            'batch_id' => $batch->id,
            'date' => today()->toDateString(),
            'state' => AttendanceSheet::STATE_DONE,
        ]);
        AttendanceLine::create([
            'attendance_sheet_id' => $sheet->id,
            'student_id' => $student->id,
            'status' => AttendanceLine::STATUS_ABSENT,
        ]);

        NotificationService::sendAbsenceAlerts(75);

        Queue::assertPushed(SendNotificationJob::class);
    }

    public function test_qr_attendance_signature_roundtrip(): void
    {
        $student = Student::create([
            'name' => 'علي',
            'student_code' => 'ST-003',
            'state' => Student::STATE_ADMITTED,
        ]);

        $controller = new AttendanceController;

        $ref = new \ReflectionMethod($controller, 'signStudent');
        $ref->setAccessible(true);
        $qr = $ref->invoke($controller, $student);

        $verify = new \ReflectionMethod($controller, 'verifySignedStudent');
        $verify->setAccessible(true);
        $resolved = $verify->invoke($controller, $qr);

        $this->assertSame($student->id, $resolved->id);
    }

    public function test_qr_signature_rejects_tampered_payload(): void
    {
        $this->expectException(HttpException::class);

        $controller = new AttendanceController;
        $verify = new \ReflectionMethod($controller, 'verifySignedStudent');
        $verify->setAccessible(true);
        $verify->invoke($controller, '1.forgedsignature');
    }
}
