# Sistem Keamanan Admin - KurashiNavi

## Overview
Sistem keamanan ini dirancang untuk melindungi folder admin dari akses tidak sah dan memastikan hanya user dengan role admin yang dapat mengakses halaman admin.

## Fitur Keamanan

### 1. Session Validation
- Memvalidasi session user
- Cek role admin
- Session timeout (1 jam)
- Auto-logout jika session expired

### 2. IP Restriction (Optional)
- Dapat dibatasi hanya untuk localhost
- Konfigurasi di `auth_check.php`

### 3. User Agent Validation
- Memvalidasi User-Agent header
- Mencegah akses dari bot/script

### 4. CSRF Protection
- Token CSRF untuk form POST
- Validasi otomatis setiap request

### 5. Activity Logging
- Log semua aktivitas admin
- Mencatat user, waktu, dan aksi

## Struktur File

```
admin/
├── .htaccess              # Apache security rules
├── auth_check.php         # Main security module
├── index.php             # Auto-redirect
├── dashboard.php         # Admin dashboard
├── manage_*.php          # Management pages
└── logout.php            # Logout handler
```

## Cara Kerja

### 1. Akses ke Folder Admin
- User mencoba akses `http://localhost/KurashiNavi/admin/`
- Sistem mengecek session dan role
- Jika tidak login atau bukan admin → redirect ke login
- Jika sudah login sebagai admin → akses diizinkan

### 2. Validasi Session
- Cek `$_SESSION['user_id']` exists
- Cek `$_SESSION['role'] === 'admin'`
- Cek session timeout
- Update last activity

### 3. CSRF Protection
- Generate token saat login
- Validasi token di setiap POST request
- Redirect jika token tidak valid

## Konfigurasi

### Session Timeout
```php
$session_timeout = 3600; // 1 jam (dalam detik)
```

### IP Restriction
```php
// Uncomment baris ini untuk membatasi akses hanya dari localhost
// validateIP();
```

### Allowed IPs
```php
$allowed_ips = ['127.0.0.1', '::1']; // localhost
```

## Error Messages

Sistem akan menampilkan pesan error yang sesuai:

- `not_logged_in` - User belum login
- `not_admin` - User bukan admin
- `session_expired` - Session sudah expired
- `invalid_token` - CSRF token tidak valid
- `invalid_ip` - IP tidak diizinkan
- `invalid_user_agent` - User-Agent tidak valid

## Logging

Semua aktivitas admin dicatat dalam error log dengan format:
```
ADMIN_ACTIVITY: 2025-01-XX XX:XX:XX - User: username (ID: X) - Action: action_name - Details: details
```

## Testing Keamanan

### 1. Test Akses Tanpa Login
- Buka `http://localhost/KurashiNavi/admin/`
- Harus redirect ke login page

### 2. Test Akses dengan User Non-Admin
- Login dengan user biasa
- Coba akses admin page
- Harus redirect ke login dengan error "not_admin"

### 3. Test Session Timeout
- Login sebagai admin
- Tunggu 1 jam
- Coba akses admin page
- Harus redirect ke login dengan error "session_expired"

## Troubleshooting

### 1. Error "Headers already sent"
- Pastikan tidak ada output sebelum `header()`
- Cek whitespace di awal file

### 2. Session tidak tersimpan
- Cek konfigurasi PHP session
- Pastikan `session_start()` dipanggil

### 3. Redirect loop
- Cek path redirect di `auth_check.php`
- Pastikan file login.php ada dan dapat diakses

## Best Practices

1. **Jangan disable session validation**
2. **Gunakan HTTPS di production**
3. **Regular security audit**
4. **Monitor admin activity logs**
5. **Update dependencies secara berkala**

## Security Headers

Tambahkan header keamanan berikut di `.htaccess`:

```apache
# Security Headers
Header always set X-Content-Type-Options nosniff
Header always set X-Frame-Options DENY
Header always set X-XSS-Protection "1; mode=block"
Header always set Referrer-Policy "strict-origin-when-cross-origin"
```

## Backup dan Recovery

1. **Backup file keamanan secara berkala**
2. **Simpan log admin activity**
3. **Dokumentasikan perubahan keamanan**
4. **Test restore procedure**

---

**Note**: Sistem keamanan ini dirancang untuk development environment. Untuk production, tambahkan layer keamanan tambahan seperti SSL/TLS, firewall, dan monitoring system. 