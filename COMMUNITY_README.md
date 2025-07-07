# 🏘️ Sistem Community KurashiNavi

## 📋 Overview

Sistem Community untuk aplikasi KurashiNavi yang memungkinkan admin mengelola events, groups, dan forum categories melalui panel admin. Sistem ini dirancang untuk membantu orang asing di Jepang berinteraksi dan berbagi informasi.

## 🗄️ Struktur Database

### Tabel Utama

#### 1. `community_events`
Menyimpan data event komunitas
```sql
- id (Primary Key)
- title (Judul event)
- description (Deskripsi event)
- event_date (Tanggal event)
- start_time (Waktu mulai)
- end_time (Waktu selesai)
- location (Lokasi event)
- category (Kategori: language_exchange, cultural_experience, sports, social, other)
- tags (JSON array untuk tag)
- max_participants (Maksimal peserta)
- current_participants (Jumlah peserta saat ini)
- image (Gambar event)
- status (upcoming, ongoing, completed, cancelled)
- featured (Boolean untuk event unggulan)
- created_at, updated_at
```

#### 2. `community_groups`
Menyimpan data grup komunitas
```sql
- id (Primary Key)
- name (Nama grup)
- description (Deskripsi grup)
- category (Kategori: language_exchange, cultural_activities, sports, hobbies, study, other)
- icon (Path icon grup)
- member_count (Jumlah anggota)
- max_members (Maksimal anggota)
- meeting_frequency (Frekuensi pertemuan)
- tags (JSON array untuk tag)
- status (active, inactive, full)
- created_at, updated_at
```

#### 3. `forum_categories`
Menyimpan kategori forum
```sql
- id (Primary Key)
- name (Nama kategori)
- description (Deskripsi kategori)
- icon (Icon Font Awesome)
- topic_count (Jumlah topik)
- view_count (Jumlah view)
- status (active, inactive)
- order_number (Urutan tampilan)
- created_at, updated_at
```

#### 4. `forum_topics`
Menyimpan topik forum
```sql
- id (Primary Key)
- category_id (Foreign Key ke forum_categories)
- user_id (Foreign Key ke users)
- title (Judul topik)
- content (Isi topik)
- view_count (Jumlah view)
- reply_count (Jumlah balasan)
- status (active, locked, deleted)
- pinned (Boolean untuk topik pin)
- created_at, updated_at
```

#### 5. `forum_replies`
Menyimpan balasan forum
```sql
- id (Primary Key)
- topic_id (Foreign Key ke forum_topics)
- user_id (Foreign Key ke users)
- content (Isi balasan)
- status (active, deleted)
- created_at, updated_at
```

### Tabel Relasi

#### 6. `event_participants`
Relasi many-to-many antara events dan users
```sql
- id (Primary Key)
- event_id (Foreign Key ke community_events)
- user_id (Foreign Key ke users)
- status (registered, attended, cancelled)
- registered_at
```

#### 7. `group_members`
Relasi many-to-many antara groups dan users
```sql
- id (Primary Key)
- group_id (Foreign Key ke community_groups)
- user_id (Foreign Key ke users)
- role (member, moderator, admin)
- joined_at
- status (active, inactive, banned)
```

## 🛠️ Setup dan Instalasi

### 1. Jalankan Setup Database
```bash
# Akses melalui browser
http://localhost/KurashiNavi/setup_community_db.php
```

### 2. Verifikasi Tabel
Pastikan semua tabel telah dibuat dengan benar:
```sql
SHOW TABLES LIKE 'community_%';
SHOW TABLES LIKE 'forum_%';
```

### 3. Test Data
Cek apakah data sample telah dimasukkan:
```sql
SELECT COUNT(*) FROM community_events;
SELECT COUNT(*) FROM community_groups;
SELECT COUNT(*) FROM forum_categories;
```

## 📁 File Struktur

```
KurashiNavi/
├── models/
│   ├── Community.php              # Model utama untuk community
│   └── community_database.sql     # Struktur database
├── admin/
│   └── manage_community.php       # Panel admin untuk mengelola community
├── setup_community_db.php         # Script setup database
└── community.php                  # Halaman community (perlu update)
```

## 🎯 Fitur Utama

### 1. Manajemen Events
- ✅ Create, Read, Update, Delete events
- ✅ Kategori events (language exchange, cultural experience, sports, social, other)
- ✅ Sistem tag untuk events
- ✅ Status events (upcoming, ongoing, completed, cancelled)
- ✅ Featured events
- ✅ Tracking peserta events

### 2. Manajemen Groups
- ✅ Create, Read, Update, Delete groups
- ✅ Kategori groups (language exchange, cultural activities, sports, hobbies, study, other)
- ✅ Sistem tag untuk groups
- ✅ Tracking anggota groups
- ✅ Role management (member, moderator, admin)

### 3. Manajemen Forum
- ✅ Create, Read, Update, Delete forum categories
- ✅ Sistem topik dan balasan
- ✅ View counting
- ✅ Pinned topics
- ✅ Status management

### 4. Panel Admin
- ✅ Dashboard dengan statistik
- ✅ Tab-based interface (Events, Groups, Forum)
- ✅ Modal forms untuk CRUD operations
- ✅ Real-time data display
- ✅ Success/error notifications

## 🔧 Cara Penggunaan

### 1. Akses Panel Admin
```
URL: http://localhost/KurashiNavi/admin/manage_community.php
Login: admin / password
```

### 2. Menambah Event Baru
1. Klik tombol "イベント追加" (Tambah Event)
2. Isi form dengan data event
3. Pilih kategori dan tambahkan tag
4. Set maksimal peserta dan status
5. Klik "作成" (Buat)

### 3. Menambah Group Baru
1. Klik tombol "グループ追加" (Tambah Group)
2. Isi form dengan data group
3. Pilih kategori dan icon
4. Set frekuensi pertemuan dan tag
5. Klik "作成" (Buat)

### 4. Menambah Forum Category
1. Klik tombol "カテゴリ追加" (Tambah Category)
2. Isi nama dan deskripsi
3. Pilih icon Font Awesome
4. Set urutan tampilan
5. Klik "作成" (Buat)

## 📊 Statistik Dashboard

Panel admin menampilkan statistik real-time:
- Total Events
- Total Groups  
- Forum Categories
- Total Members

## 🔄 Triggers Database

Sistem menggunakan triggers untuk auto-update:
- `update_event_participants_count` - Update jumlah peserta event
- `update_group_members_count` - Update jumlah anggota group
- `update_forum_topic_count` - Update jumlah topik forum

## 🌐 Multi-language Support

Sistem mendukung 3 bahasa:
- 🇯🇵 Japanese (ja)
- 🇺🇸 English (en)  
- 🇨🇳 Chinese (zh)

## 🔒 Security Features

- Session-based authentication
- SQL injection prevention dengan PDO prepared statements
- XSS prevention dengan htmlspecialchars()
- CSRF protection (perlu implementasi tambahan)

## 🚀 Langkah Selanjutnya

### 1. Update Halaman Community
Update `community.php` untuk menggunakan data dari database:
```php
require_once 'models/Community.php';
$community = new Community($pdo);
$events = $community->getAllEvents(6); // Ambil 6 events terbaru
$groups = $community->getAllGroups(3); // Ambil 3 groups terpopuler
$forumCategories = $community->getAllForumCategories();
```

### 2. Implementasi Edit Function
Tambahkan fungsi edit di `manage_community.php`:
- Modal edit untuk events
- Modal edit untuk groups  
- Modal edit untuk forum categories

### 3. Implementasi User Interface
- Halaman detail event
- Halaman detail group
- Halaman forum dengan topik dan balasan
- Sistem registrasi event/group

### 4. Advanced Features
- Email notifications
- Calendar integration
- Search functionality
- Filtering dan sorting
- Pagination
- Image upload untuk events/groups

## 🐛 Troubleshooting

### Error: Table doesn't exist
```bash
# Jalankan setup script
http://localhost/KurashiNavi/setup_community_db.php
```

### Error: Permission denied
```bash
# Pastikan folder memiliki permission yang benar
chmod 755 models/
chmod 644 models/*.php
```

### Error: Database connection
```bash
# Cek konfigurasi database di models/db.php
# Pastikan XAMPP berjalan
# Cek kredensial database
```

## 📞 Support

Untuk bantuan teknis atau pertanyaan:
- Email: kurashinavi@gmail.com
- Dokumentasi: Lihat file ini
- Issues: Buat issue di repository

---

**Dibuat dengan ❤️ untuk komunitas KurashiNavi** 