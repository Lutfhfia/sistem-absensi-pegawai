<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::create('permits', function(Blueprint $table){
            $table->id(); $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->enum('jenis',['izin','sakit']); $table->date('tanggal_mulai'); $table->date('tanggal_selesai');
            $table->text('alasan'); $table->string('berkas'); $table->enum('status',['pending','disetujui','ditolak'])->default('pending');
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete(); $table->dateTime('approved_at')->nullable(); $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('permits'); }
};
