# PRD — PLC Warning System

## 1. Ringkasan Proyek
### 1.1 Tujuan
Dokumen PRD ini menguraikan persyaratan untuk aplikasi MINA PLC Warning System, sebuah subsistem untuk menampilkan status komponen mesin pada lini produksi di PT Mitsuba Indonesia berdasarkan data PLC yang diklasifikasikan menjadi tiga status yaitu **Standard**, **Warning**, dan **Danger** dalam bentuk dashboard untuk mendukung proses maintenance mesin oleh teknisi, serta menyediakan dashboard pelaporan bagi supervisor dan teknisi dengan data yang terbagi berdasarkan plant masing-masing.

### 1.2 Target Pengguna
- Teknisi yang bertugas untuk melakukan pengecekan dan perawatan komponen mesin produksi PT Mitsuba Indonesia
- Supervisor pada setiap plant produksi PT Mitsuba Indonesia untuk memonitoring kondisi mesin supaya meminimalisir gangguan selama proses produksi

## 2. Fitur Utama
- **Pemantauan dan Sinkronisasi Batch Otomatis**  
Mengintegrasikan sistem penarikan data mentah dari PLC mesin secara berkala menggunakan sistem Scheduler setiap hari jam 07:00 WIB serta opsi pemicu manual bagi supervisor.

- **Kontrol Akses Berbasis Peran (RBAC) dan Scope berdasarkan Plant**  
Menerapkan pembatasan hak akses multi-aktor (Super Admin, Supervisor, Teknisi) yang diperketat menggunakan mekanisme Plant Scoping. Data mesin, grafik, dan log gangguan otomatis difilter sesuai dengan Plant dan lini produksi (Line) yang ditugaskan ke masing-masing aktor.

- **Dashboard Data Monitoring Dinamis**  
Menampilkan visualisasi ringkasan status kesehatan komponen mesin secara dinamis ke dalam 3 kategori utama (**STANDARD**, **WARNING**, **DANGER**) yang dilengkapi dengan indikator waktu sinkronisasi terakhir demi validitas data lapangan.

- **Maintenance Logging Workflow**  
Menyediakan Custom Action Button interaktif sebagai respon tindakan yang dilakukan teknisi terhadap status komponen

- **Secure API Token Gateway & Integration Framework**  
Menyediakan modul Token Generator internal bagi Super Admin untuk menjembatani integrasi data dengan aplikasi divisi lain dengan standar keamanan pada database.

- **Integrated Interactive API Documentation**  
Menyediakan halaman panduan teknis integrasi (API Docs) bawaan di dalam dashboard control system sebagai panduan untuk mengonsumsi data endpoint PLC menggunakan metode autentikasi Bearer Token.

## 3. Teknologi yang Digunakan
- **Backend** : Laravel 12
- **User Interface** : Filament V5 dengan Tailwind CSS 4
- **Database** : SQL Server 2022
- **Email** : Mailtrap
- **API** : REST API 

## 4. Kebutuhan Teknis
### **Database**  
- Konfigurasi database SQL Server 2022 (SSMS) pada Laravel
- Migrations untuk semua table yang dibutuhkan
- Stored Procedure untuk index query yang efisien

### **Backend**
- Repository pattern untuk manajemen query database dan pemisahan logika akses data
- Service layer untuk logika bisnis utama 
- Job queue untuk pemrosesan data secara batch dan pengiriman email otomatis
- Konfigurasi task scheduler untuk sinkronisasi otomatis harian
- Autentikasi API menggunakan Laravel Sanctum (Bearer Token)

### **User Interface**
- Integrasi panel filament v5 untuk tampilan dashboard
- Visibilitas data yang dibatasi berdasarkan plant yang ditugaskan ke user yang login
- Data report dinamis untuk pemantauan status 
- Navigasi sidebar yang terkelompok

### **Security**
- Role-Based Access Control (RBAC) menggunakan package filament-shield
- Eloquent query scope untuk isolasi data yang ketat
- Manajemen siklus hidup token API yang aman

### **Performance**
- Arsitektur batch processing untuk meminimalkan beban input/output pada database utama
- Optimasi query Eloquent untuk meminimalkan beban query data
- Eksekusi proses background untuk tugas sinkronisasi yang berjalan lama lewat queue
