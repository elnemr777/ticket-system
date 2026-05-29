# 🎮 MTA Ticket System

نظام دعم فني احترافي لسيرفرات MTA (Multi Theft Auto) يتيح للاعبين التواصل مع الإدارة عبر نظام تذاكر متكامل.

## ✨ المميزات

### مميزات اللاعب
- تسجيل دخول بـ Discord أو حساب السيرفر
- فتح تذاكر دعم (تقني، باند، استرجاع، شكوى، Bug، اقتراح)
- تحديد أولوية التذكرة (عادي، مهم، عاجل)
- رفع صور وفيديوهات كدليل
- شات مباشر مع الإدارة داخل التذكرة
- تتبع حالة التذكرة لحظة بلحظة
- إغلاق التذكرة بعد حل المشكلة

### مميزات الإدارة
- لوحة تحكم بإحصائيات شاملة
- عرض جميع التذاكر مع فلتر وبحث
- استلام وتوزيع التذاكر على الإداريين
- الرد على اللاعبين عبر شات مباشر
- تغيير حالة التذكرة (مفتوحة، قيد المعالجة، تم الحل، مغلقة)

### مميزات عامة
- تصميم Dark Mode عصري
- يعمل على الموبايل والكمبيوتر (Responsive)
- ربط مباشر مع Database السيرفر
- صفحة 404 مخصصة

## 🛠️ التقنيات المستخدمة

- **Backend:** PHP + MySQL
- **Frontend:** HTML5 + CSS3 + JavaScript
- **Authentication:** Discord OAuth2 + Server Account (MD5)
- **Server:** XAMPP (Apache + MySQL)

## 📁 هيكل المشروع

```
ticket-system/
├── admin/
│   └── admin-panel.html
├── api/
│   ├── admin/
│   │   ├── tickets-info.php
│   │   ├── assign.php
│   │   └── update-status.php
│   ├── tickets/
│   │   ├── create.php
│   │   ├── my-tickets.php
│   │   ├── get-ticket.php
│   │   ├── reply.php
│   │   └── close.php
│   └── user/
│       └── get-info.php
├── assets/
│   ├── main.css
│   ├── new-ticket.css
│   ├── my-tickets.css
│   ├── ticket-details.css
│   └── admin-panel.css
├── auth/
│   ├── discord-login.php
│   ├── discord-callback.php
│   └── logout.php
├── config/
│   ├── mta.php
│   └── site.php
├── pages/
│   ├── main.html
│   ├── new-ticket.html
│   ├── my-tickets.html
│   └── ticket-details.html
├── uploads/
│   └── tickets/
├── login.php
├── 404.html
├── .htaccess
└── database-final.sql
```

## 📊 قاعدة البيانات

| الجدول | الوصف |
|--------|-------|
| `users` | المستخدمين (Discord + In-game) |
| `tickets` | التذاكر |
| `ticket_replies` | الردود والشات |
| `ticket_attachments` | المرفقات (صور/فيديوهات) |

## 🚀 التنصيب

### 1. استنساخ المشروع
```bash
git clone https://github.com/elnemr777/ticket-system.git
```

### 2. نقل الملفات
انقل مجلد `ticket-system` إلى:
```
C:\xampp\htdocs\backend\ticket-system
```

### 3. إنشاء قاعدة البيانات
- افتح phpMyAdmin: `http://localhost/phpmyadmin`
- اضغط Import
- اختر ملف `database-final.sql`
- اضغط Go

### 4. إعداد الاتصال
عدّل ملف `config/site.php`:
```php
$site_host = 'localhost';
$site_user = 'root';
$site_pass = '';
$site_db   = 'mta_tickets';
```

عدّل ملف `config/mta.php`:
```php
$host = 'localhost';
$user = 'root';
$pass = '';
$db   = 'اسم_database_السيرفر';
```

### 5. إعداد Discord (اختياري)
- اذهب إلى: https://discord.com/developers/applications
- أنشئ Application جديد
- انسخ Client ID و Client Secret
- عدّل ملف `auth/discord-login.php` و `auth/discord-callback.php`

### 6. تشغيل المشروع
```
http://localhost/backend/ticket-system/login.php
```

## 📸 Screenshots

- صفحة تسجيل الدخول
- الصفحة الرئيسية
- فتح تذكرة جديدة
- عرض التذاكر
- الشات مع الإدارة
- لوحة تحكم الإدارة

## 👨‍💻 المطور

- GitHub: [@elnemr777](https://github.com/elnemr777)

## 📄 الرخصة

هذا المشروع مفتوح المصدر.
