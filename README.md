# Edubba — نظام إدارة المدارس العراقية

نظام متكامل لإدارة المدارس في العراق (Laravel + MySQL): الطلاب، الأقساط والدفع (ZainCash / Qi Card)، الحضور، الجداول، الامتحانات والنتائج، الإشعارات (WhatsApp / SMS / FCM)، التقويم العراقي، والتقارير الوزارية.

## المتطلبات

| الأداة | النسخة |
|---|---|
| PHP | 8.2+ |
| Composer | 2.x |
| MySQL | 8.x (SQLite للتطوير) |
| Redis | 7.x (إنتاج فقط) |
| Node.js | 20+ (لـ Vite) |

## التثبيت المحلي

```bash
composer install
npm install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
npm run dev
```

### Windows / XAMPP
- مطلوب تثبيت إضافات PHP: `gd`، `mbstring`، `pdo_mysql`، `fileinfo`، `openssl` (لتوليد PDF بخط Tajawal).
- ضع ملفات الخط في `storage/fonts/`:
  - `Tajawal-Regular.ttf`
  - `Tajawal-Bold.ttf`
  - (من [Google Fonts Tajawal](https://fonts.google.com/specimen/Tajawal))
- بدون `ext-pcntl`/`ext-posix` يعمل كل شيء محلياً عدا Horizon (Redis).

### Seeders
- `DatabaseSeeder` — بيانات تجريبية شاملة.
- `IraqiCalendarSeeder` — العطل الرسمية العراقية (راجعها سنوياً لأن الأعياد الهجرية تتحرك).

## الإعداد في الإنتاج

أنشئ `.env.production` من القالب واتبع تعليمات النشر:

```bash
cp .env.production.example .env.production
```

ثم راجع قسم [النشر](#النشر-deployment) أدناه.

### الإشعارات
| المتغير | الوصف |
|---|---|
| `SMS_PROVIDER` | `mock` (تجريبي) / `twilio` / `unifonic` / `iraqsms` |
| `WHATSAPP_PROVIDER` | `mock` / `meta` (Cloud API) |
| `FCM_PROJECT_ID` | مشروع Firebase لإشعارات التطبيق |
| `NOTIF_RATE_PER_SECOND` | حد الإرسال في الثانية |
| `NOTIF_DAILY_CAP` | الحد اليومي (حماية من المصاريف) |

### الدفع العراقي
- `ZAINCASH_SANDBOX=true` للتجربة، و`false` للدفع الفعلي — املأ `MERCHANT_ID/MERCHANT_SECRET/MSISDN/IQN`.
- `QICARD_SANDBOX` وباقي الحقول من مزوّد Qi Card.
- الـ webhooks:
  - `POST /api/v1/payments/zaincash/callback`
  - `POST /api/v1/payments/qicard/callback`
  - (توقيع HMAC يُتحقق منه في الخادم — لا تتطلب مصادقة)

### النسخ الاحتياطي
- `BACKUP_PATH` — مجلد النسخ (افتراضياً `storage/app/backup`).
- `BACKUP_KEEP_DAYS` — عدد النسخ المحفوظة (حذف الأقدم تلقائياً).
- `MYSQLDUMP_PATH` — مسار `mysqldump` على الخادم.

## الوظائف المجدولة

| الوقت | الوظيفة |
|---|---|
| 00:00 | توليد الجلسات اليومية |
| 01:00 | تجميع الحضور |
| 02:00 | تجديد الاشتراكات |
| 06:00 | إشعارات الغياب |
| 07:00 | إشعارات أعياد الميلاد |
| 23:30 | نسخ احتياطي + حذف القديم |

على الخادم شغّل:
```bash
crontab -e
* * * * * cd /path/to/edubba && php artisan schedule:run >> /dev/null 2>&1
```

للمعالجة غير المتزامنة استخدم Horizon (يتطلب Redis):
```bash
php artisan horizon
```
وتأكد من تشغيل `php artisan queue:restart` بعد كل نشر.

## الاختبارات

```bash
php artisan test
```

(يستخدم SQLite في الذاكرة — لا حاجة لإعداد DB.)

## هيكل الخدمات

| الخدمة | المسار |
|---|---|
| الإشعارات | `app/Services/NotificationService.php` |
| SMS | `app/Services/SmsService.php` |
| WhatsApp | `app/Services/WhatsAppService.php` |
| FCM | `app/Services/FcmService.php` |
| ZainCash | `app/Services/Payments/ZainCashService.php` |
| Qi Card | `app/Services/Payments/QiCardService.php` |
| PDF | `app/Services/PdfService.php` |
| التسلسلات (STU/CERT...) | `app/Services/SequenceService.php` |
| الحضور | `app/Services/AttendanceService.php` |
| الجداول | `app/Services/TimetableService.php` |

## المسارات الرئيسية

- **لوحة الإدارة**: `/admin/login` — الطلاب، الأقساط، الحضور، الجدول، التقويم، الامتحانات.
- **API الجوال**: `/api/v1/*` — `auth:sanctum` مع حدود طلب (`throttle:api`).
- **دفع**: webhooks عامة من ZainCash / Qi Card فقط.

## الترخيص

MIT.
