<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Profil Perusahaan
        if (!Schema::hasTable('tbm_profil')) {
            Schema::create('tbm_profil', function (Blueprint $table) {
                $table->integer('id', true);
                $table->string('pemilik', 200)->nullable();
                $table->string('namaperusahaan', 200);
                $table->text('alamat')->nullable();
                $table->string('telp', 25)->nullable();
                $table->string('hp', 30)->nullable();
                $table->string('fax', 20)->nullable();
                $table->string('email', 200)->nullable();
                $table->string('url', 200)->nullable();
                $table->string('logo', 200)->nullable();
                $table->date('tgldaftar')->nullable();
                $table->smallInteger('totalhari')->default(365);
                $table->date('expire')->nullable();
                $table->tinyInteger('ppn')->default(11);
                $table->tinyInteger('service')->default(0);
                $table->tinyInteger('tinggi')->default(50);
                $table->tinyInteger('lebar')->default(100);
            });
        }

        // 2. Setting PABX
        if (!Schema::hasTable('tbm_setting')) {
            Schema::create('tbm_setting', function (Blueprint $table) {
                $table->integer('id', true);
                $table->string('ip', 35);
                $table->mediumInteger('port');
                $table->string('keterangan', 200)->nullable();
                $table->char('st', 1)->default('1');
                $table->dateTime('tgltambah')->nullable();
                $table->string('optambah', 100)->nullable();
            });
        }

        // 3. Level / Role
        if (!Schema::hasTable('tbm_level')) {
            Schema::create('tbm_level', function (Blueprint $table) {
                $table->integer('id', true);
                $table->integer('idcabang')->default(1);
                $table->string('level', 300);
                $table->enum('valid', ['Ya', 'Tidak'])->default('Ya');
            });
        }

        // 4. Pengguna Aplikasi
        if (!Schema::hasTable('tbl_user')) {
            Schema::create('tbl_user', function (Blueprint $table) {
                $table->integer('iduser', true);
                $table->string('idcabang', 80)->default('1');
                $table->integer('idlevel')->nullable();
                $table->string('username', 100)->unique();
                $table->string('password', 255);
                $table->string('nama', 100);
                $table->string('telp', 20)->nullable();
                $table->string('photo', 200)->nullable();
                $table->string('leveluser', 200)->nullable();
                $table->enum('blokir', ['Y', 'N'])->default('N');
                $table->string('id_session', 100)->nullable();
                $table->dateTime('login')->nullable();
                $table->integer('aktif')->default(1);
                $table->integer('hit')->default(0);
                $table->integer('st_del')->default(0);
                $table->integer('idmanajemen')->nullable();

                $table->foreign('idlevel')->references('id')->on('tbm_level')->onUpdate('cascade')->onDelete('set null');
                $table->foreign('idmanajemen')->references('iduser')->on('tbl_user')->onUpdate('cascade')->onDelete('set null');
            });
        }

        // 5. User Audit Log
        if (!Schema::hasTable('user_log')) {
            Schema::create('user_log', function (Blueprint $table) {
                $table->integer('idlog', true);
                $table->integer('idcabang')->default(1);
                $table->integer('iduser');
                $table->enum('aksi', ['tambah', 'edit', 'delete']);
                $table->dateTime('tgl');

                $table->foreign('iduser')->references('iduser')->on('tbl_user')->onUpdate('cascade')->onDelete('cascade');
            });
        }

        // 6. Login Log
        if (!Schema::hasTable('log_login')) {
            Schema::create('log_login', function (Blueprint $table) {
                $table->integer('idlog', true);
                $table->integer('iduser');
                $table->string('ip', 50)->nullable();
                $table->dateTime('tgl');

                $table->foreign('iduser')->references('iduser')->on('tbl_user')->onUpdate('cascade')->onDelete('cascade');
            });
        }

        // 7. Modul Menu
        if (!Schema::hasTable('tbd_modul_user')) {
            Schema::create('tbd_modul_user', function (Blueprint $table) {
                $table->integer('iddetail', true);
                $table->integer('idmodul')->default(0);
                $table->string('kelompok', 50)->nullable();
                $table->string('kategorimenu', 50)->nullable();
                $table->string('namadetail', 100);
                $table->string('namadetailin', 200)->nullable();
                $table->string('link', 100)->nullable();
                $table->string('namafile', 200)->nullable();
                $table->integer('aktif')->default(1);
                $table->integer('parent')->default(0);
                $table->string('department', 100)->nullable();
            });
        }

        // 8. Akses Modul
        if (!Schema::hasTable('tbd_akses_user')) {
            Schema::create('tbd_akses_user', function (Blueprint $table) {
                $table->integer('id', true);
                $table->integer('idlevel');
                $table->integer('iddetailmodul');
                $table->integer('tampil')->default(1);
                $table->integer('ubah')->default(0);
                $table->integer('hapus')->default(0);
                $table->integer('tambah')->default(0);
                $table->integer('aktif')->default(1);

                $table->foreign('idlevel')->references('id')->on('tbm_level')->onUpdate('cascade')->onDelete('cascade');
                $table->foreign('iddetailmodul')->references('iddetail')->on('tbd_modul_user')->onUpdate('cascade')->onDelete('cascade');
            });
        }

        // 9. Group Departemen (Divisi)
        if (!Schema::hasTable('tbm_group_department')) {
            Schema::create('tbm_group_department', function (Blueprint $table) {
                $table->integer('idgroup', true);
                $table->string('groupcode', 20)->unique();
                $table->string('namagroup', 200);
                $table->string('namadivisi', 200)->nullable();
                $table->dateTime('tgltambah')->nullable();
                $table->string('optambah', 50)->nullable();
                $table->char('st', 1)->default('1');
                $table->dateTime('tgldelete')->nullable();
                $table->string('opdelete', 50)->nullable();
            });
        }

        // 10. Departemen & Ekstensi PABX
        if (!Schema::hasTable('tbm_department')) {
            Schema::create('tbm_department', function (Blueprint $table) {
                $table->integer('id', true);
                $table->integer('idgroup')->nullable();
                $table->integer('extcode')->unique();
                $table->string('extname', 100);
                $table->string('phonepassword', 50)->nullable();
                $table->string('divisi', 100)->nullable();
                $table->string('jabatan', 100)->nullable();
                $table->string('nik', 50)->nullable();
                $table->double('additional')->default(0);
                $table->double('budget')->default(0);
                $table->dateTime('tgltambah')->nullable();
                $table->string('optambah', 50)->nullable();
                $table->dateTime('tgledit')->nullable();
                $table->string('opedit', 50)->nullable();
                $table->char('st', 1)->default('1');
                $table->dateTime('tgldelete')->nullable();
                $table->dateTime('opdelete')->nullable();

                $table->foreign('idgroup')->references('idgroup')->on('tbm_group_department')->onUpdate('cascade')->onDelete('set null');
            });
        }

        // 11. Zona Tujuan
        if (!Schema::hasTable('tbm_zona')) {
            Schema::create('tbm_zona', function (Blueprint $table) {
                $table->integer('idzone', true);
                $table->string('namazone', 200);
                $table->enum('kelompok', ['IDD', 'NDD', 'LOCAL', 'CELL'])->default('LOCAL');
                $table->char('st', 1)->default('1');
                $table->dateTime('tgltambah')->nullable();
                $table->date('optambah')->nullable();
                $table->date('tglubah')->nullable();
                $table->string('opubah', 20)->nullable();
            });
        }

        // 12. Prefix Nomor
        if (!Schema::hasTable('tbm_prefix')) {
            Schema::create('tbm_prefix', function (Blueprint $table) {
                $table->integer('idprefix', true);
                $table->integer('idzone')->nullable();
                $table->string('kodeprefix', 20);
                $table->string('distrik', 8)->nullable();
                $table->string('keterangan', 200)->nullable();
                $table->enum('kelompok', ['CELL', 'PHONE'])->default('PHONE');
                $table->char('st', 1)->default('1');
                $table->dateTime('tgltambah')->nullable();
                $table->string('optambah', 100)->nullable();
                $table->dateTime('tglubah')->nullable();
                $table->string('opubah', 100)->nullable();
                $table->dateTime('tgldelete')->nullable();
                $table->string('opdelete', 50)->nullable();

                $table->foreign('idzone')->references('idzone')->on('tbm_zona')->onUpdate('cascade')->onDelete('set null');
            });
        }

        // 13. Hari Master
        if (!Schema::hasTable('tbm_hari')) {
            Schema::create('tbm_hari', function (Blueprint $table) {
                $table->integer('id', true);
                $table->string('namahari', 30);
                $table->tinyInteger('kode');
            });
        }

        // 14. Tarif Master
        if (!Schema::hasTable('tbm_tarif')) {
            Schema::create('tbm_tarif', function (Blueprint $table) {
                $table->integer('idtarif', true);
                $table->integer('idmesin')->nullable();
                $table->string('namamesin', 100)->nullable();
                $table->string('ratecode', 50);
                $table->text('catatan')->nullable();
                $table->string('harikhusus', 80)->nullable();
                $table->string('namahari', 100)->nullable();
                $table->smallInteger('mindurasi')->default(0);
                $table->dateTime('tgltambah')->nullable();
                $table->string('optambah', 100)->nullable();
                $table->dateTime('tgledit')->nullable();
                $table->string('opedit', 50)->nullable();
                $table->char('st', 1)->default('1');

                $table->foreign('idmesin')->references('id')->on('tbm_setting')->onUpdate('cascade')->onDelete('set null');
            });
        }

        // 15. Rincian Tarif Jam (Peak & Off-Peak)
        if (!Schema::hasTable('tbd_rate')) {
            Schema::create('tbd_rate', function (Blueprint $table) {
                $table->integer('id', true);
                $table->integer('idrate');
                $table->time('mulai');
                $table->time('selesai');
                $table->string('tarifnormal', 40);
                $table->string('tarifkhusus', 50)->nullable();

                $table->foreign('idrate')->references('idtarif')->on('tbm_tarif')->onUpdate('cascade')->onDelete('cascade');
            });
        }

        // 16. Tarif Khusus / Promo
        if (!Schema::hasTable('tbm_tarif_khusus')) {
            Schema::create('tbm_tarif_khusus', function (Blueprint $table) {
                $table->integer('idharga', true);
                $table->integer('idtarif');
                $table->string('namatarif', 150);
                $table->string('keterangan', 200)->nullable();
                $table->date('tglmulai');
                $table->date('tglselesai');
                $table->smallInteger('mindurasi')->default(0);
                $table->dateTime('tgltambah')->nullable();
                $table->string('optambah', 100)->nullable();
                $table->dateTime('tglubah')->nullable();
                $table->string('opubah', 100)->nullable();
                $table->dateTime('tgldelete')->nullable();
                $table->dateTime('opdelete')->nullable();
                $table->char('st', 1)->default('1');

                $table->foreign('idtarif')->references('idtarif')->on('tbm_tarif')->onUpdate('cascade')->onDelete('cascade');
            });
        }

        // 17. Detail Tarif Khusus
        if (!Schema::hasTable('tbd_tarif_khusus')) {
            Schema::create('tbd_tarif_khusus', function (Blueprint $table) {
                $table->integer('id', true);
                $table->integer('idtarifkhusus');
                $table->time('mulai');
                $table->time('selesai');
                $table->string('tarif', 50);
                $table->date('tgltarif')->nullable();

                $table->foreign('idtarifkhusus')->references('idharga')->on('tbm_tarif_khusus')->onUpdate('cascade')->onDelete('cascade');
            });
        }

        // 18. Mapping Area
        if (!Schema::hasTable('tbm_area')) {
            Schema::create('tbm_area', function (Blueprint $table) {
                $table->integer('id', true);
                $table->integer('idmesin')->nullable();
                $table->string('namamesin', 200)->nullable();
                $table->integer('idprefix')->nullable();
                $table->string('namaprefix', 100)->nullable();
                $table->integer('idzone')->nullable();
                $table->integer('idrate')->nullable();
                $table->string('kodearea', 12);
                $table->string('zona', 200)->nullable();
                $table->string('keterangan', 200)->nullable();
                $table->string('koderate', 65)->nullable();
                $table->string('namarate', 100)->nullable();
                $table->string('tipe', 50)->nullable();
                $table->dateTime('tgltambah')->nullable();
                $table->string('optambah', 100)->nullable();
                $table->dateTime('tglubah')->nullable();
                $table->string('opubah', 100)->nullable();
                $table->dateTime('tgldelete')->nullable();
                $table->dateTime('opdelete')->nullable();
                $table->char('st', 1)->default('1');

                $table->foreign('idmesin')->references('id')->on('tbm_setting')->onUpdate('cascade')->onDelete('set null');
                $table->foreign('idprefix')->references('idprefix')->on('tbm_prefix')->onUpdate('cascade')->onDelete('set null');
                $table->foreign('idzone')->references('idzone')->on('tbm_zona')->onUpdate('cascade')->onDelete('set null');
                $table->foreign('idrate')->references('idtarif')->on('tbm_tarif')->onUpdate('cascade')->onDelete('set null');
            });
        }

        // 19. Master Bisnis
        if (!Schema::hasTable('tbm_bisnis')) {
            Schema::create('tbm_bisnis', function (Blueprint $table) {
                $table->integer('id', true);
                $table->string('nama', 40);
                $table->string('notelp', 40)->nullable();
                $table->text('alamat')->nullable();
                $table->char('st', 1)->default('1');
                $table->dateTime('tgltambah')->nullable();
                $table->string('optambah', 50)->nullable();
                $table->dateTime('tglubah')->nullable();
                $table->string('opubah', 50)->nullable();
                $table->dateTime('tgldelete')->nullable();
                $table->string('opdelete', 100)->nullable();
            });
        }

        // 20. Buffer Gabung SMDR
        if (!Schema::hasTable('tbm_gabung')) {
            Schema::create('tbm_gabung', function (Blueprint $table) {
                $table->integer('id', true);
                $table->text('gabung')->nullable();
            });
        }

        // 21-24. Schedulers
        if (!Schema::hasTable('pjs_table')) {
            Schema::create('pjs_table', function (Blueprint $table) {
                $table->integer('id', true);
                $table->string('scriptpath', 255);
                $table->string('name', 128);
                $table->integer('time_interval');
                $table->integer('fire_time');
                $table->integer('time_last_fired')->default(0);
                $table->tinyInteger('run_only_once')->default(0);
                $table->tinyInteger('currently_running')->default(0);
            });
        }

        if (!Schema::hasTable('pjs_logs_table')) {
            Schema::create('pjs_logs_table', function (Blueprint $table) {
                $table->integer('id', true);
                $table->integer('date_added');
                $table->string('script', 128);
                $table->text('output')->nullable();
                $table->string('execution_time', 60)->nullable();
            });
        }

        if (!Schema::hasTable('phpjobscheduler')) {
            Schema::create('phpjobscheduler', function (Blueprint $table) {
                $table->integer('id', true);
                $table->string('scriptpath', 255);
                $table->string('name', 128);
                $table->integer('time_interval');
                $table->integer('fire_time');
                $table->integer('time_last_fired')->default(0);
                $table->tinyInteger('run_only_once')->default(0);
                $table->tinyInteger('currently_running')->default(0);
                $table->tinyInteger('paused')->default(0);
            });
        }

        if (!Schema::hasTable('phpjobscheduler_logs')) {
            Schema::create('phpjobscheduler_logs', function (Blueprint $table) {
                $table->integer('id', true);
                $table->integer('date_added');
                $table->string('script', 128);
                $table->text('output')->nullable();
                $table->string('execution_time', 60)->nullable();
            });
        }

        // 25. Rekaman Panggilan CDR & Billing Inti
        if (!Schema::hasTable('tbm_data_masuk')) {
            Schema::create('tbm_data_masuk', function (Blueprint $table) {
                $table->integer('id', true);
                $table->string('kodeclient', 25)->nullable();
                $table->string('kodetelp', 5)->nullable();
                $table->string('nourut', 25)->nullable();
                $table->string('ext_pemanggil', 30)->nullable();
                $table->integer('iddepartment')->nullable();
                $table->string('namadepartment', 200)->nullable();
                $table->string('noline', 30)->nullable();
                $table->string('tglmasuk', 25)->nullable();
                $table->time('jammasuk')->nullable();
                $table->time('durasi')->nullable();
                $table->integer('durasi_detik')->default(0);
                $table->string('kodeakses', 25)->nullable();
                $table->string('no_tujuan', 30)->nullable();
                $table->text('gabung')->nullable();
                $table->string('no_trunk', 30)->nullable();
                $table->integer('idmesin')->nullable();
                $table->string('namamesin', 150)->nullable();
                $table->integer('idzone')->nullable();
                $table->string('zona', 100)->nullable();
                $table->integer('idprefix')->nullable();
                $table->string('kodearea', 100)->nullable();
                $table->string('ketarea', 200)->nullable();
                $table->integer('idrate')->nullable();
                $table->string('namarate', 100)->nullable();
                $table->string('formularate', 40)->nullable();
                $table->double('biaya')->default(0);
                $table->double('tambahan')->default(0);
                $table->double('subtotal')->default(0);
                $table->char('hitung', 1)->default('1');
                $table->char('st', 1)->default('1');
                $table->date('tglinsert')->nullable();
                $table->string('idd', 25)->nullable();
                $table->string('iddname', 100)->nullable();
                $table->string('cell', 30)->nullable();
                $table->integer('idperusahaan')->nullable();
                $table->string('namaperusahaan', 50)->nullable();
                $table->string('pulsa', 35)->nullable();

                // Foreign Keys
                $table->foreign('iddepartment')->references('id')->on('tbm_department')->onUpdate('cascade')->onDelete('set null');
                $table->foreign('idmesin')->references('id')->on('tbm_setting')->onUpdate('cascade')->onDelete('set null');
                $table->foreign('idzone')->references('idzone')->on('tbm_zona')->onUpdate('cascade')->onDelete('set null');
                $table->foreign('idprefix')->references('idprefix')->on('tbm_prefix')->onUpdate('cascade')->onDelete('set null');
                $table->foreign('idrate')->references('idtarif')->on('tbm_tarif')->onUpdate('cascade')->onDelete('set null');
                $table->foreign('idperusahaan')->references('id')->on('tbm_profil')->onUpdate('cascade')->onDelete('set null');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbm_data_masuk');
        Schema::dropIfExists('phpjobscheduler_logs');
        Schema::dropIfExists('phpjobscheduler');
        Schema::dropIfExists('pjs_logs_table');
        Schema::dropIfExists('pjs_table');
        Schema::dropIfExists('tbm_gabung');
        Schema::dropIfExists('tbm_bisnis');
        Schema::dropIfExists('tbm_area');
        Schema::dropIfExists('tbd_tarif_khusus');
        Schema::dropIfExists('tbm_tarif_khusus');
        Schema::dropIfExists('tbd_rate');
        Schema::dropIfExists('tbm_tarif');
        Schema::dropIfExists('tbm_hari');
        Schema::dropIfExists('tbm_prefix');
        Schema::dropIfExists('tbm_zona');
        Schema::dropIfExists('tbm_department');
        Schema::dropIfExists('tbm_group_department');
        Schema::dropIfExists('tbd_akses_user');
        Schema::dropIfExists('tbd_modul_user');
        Schema::dropIfExists('log_login');
        Schema::dropIfExists('user_log');
        Schema::dropIfExists('tbl_user');
        Schema::dropIfExists('tbm_level');
        Schema::dropIfExists('tbm_setting');
        Schema::dropIfExists('tbm_profil');
    }
};
