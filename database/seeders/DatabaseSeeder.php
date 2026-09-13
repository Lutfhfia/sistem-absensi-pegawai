<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
class DatabaseSeeder extends Seeder {
 public function run(): void {
  User::updateOrCreate(['email'=>'admin@gmail.com'],['name'=>'Super Admin','password'=>Hash::make('password123'),'role'=>'super_admin','status'=>'aktif']);
  User::updateOrCreate(['email'=>'pegawai@gmail.com'],['nip'=>'PGW001','name'=>'Budi Pegawai','password'=>Hash::make('password123'),'role'=>'pegawai','status'=>'aktif']);
 }
}
