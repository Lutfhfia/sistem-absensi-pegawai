<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('attendances', function(Blueprint $table){
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->date('attendance_date');
            $table->dateTime('check_in_at')->nullable();
            $table->dateTime('check_out_at')->nullable();
            $table->decimal('check_in_latitude',10,8)->nullable();
            $table->decimal('check_in_longitude',11,8)->nullable();
            $table->decimal('check_in_accuracy',8,2)->nullable();
            $table->double('check_in_distance',8,2)->nullable();
            $table->string('check_in_photo')->nullable();
            $table->decimal('check_out_latitude',10,8)->nullable();
            $table->decimal('check_out_longitude',11,8)->nullable();
            $table->decimal('check_out_accuracy',8,2)->nullable();
            $table->double('check_out_distance',8,2)->nullable();
            $table->string('check_out_photo')->nullable();
            $table->enum('status',['hadir','terlambat'])->default('hadir');
            $table->timestamps();
            $table->unique(['user_id','attendance_date']);
        });
    }
    public function down(): void { Schema::dropIfExists('attendances'); }
};
