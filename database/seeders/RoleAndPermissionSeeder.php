<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleAndPermissionSeeder extends Seeder
{
    public function run()
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create permissions
        $permissions = [
            // Eskul permissions
            'view eskul',
            'create eskul',
            'edit eskul',
            'delete eskul',
            'manage eskul members',
            
            // Schedule permissions
            'view schedule',
            'create schedule',
            'edit schedule',
            'delete schedule',
            
            // Attendance permissions
            'view attendance',
            'create attendance',
            'edit attendance',
            
            // Bulletin permissions
            'view bulletin',
            'create bulletin',
            'edit bulletin',
            'delete bulletin',
            
            // Test permissions
            'view test',
            'create test',
            'edit test',
            'delete test',
            'take test',
            
            // User management
            'manage users',
            'manage staff',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // Create roles and assign permissions
        
        // Kepala Sekolah / Admin
        $adminRole = Role::create(['name' => 'admin']);
        $adminRole->givePermissionTo(Permission::all());

        // Pelatih
        $pelatihRole = Role::create(['name' => 'pelatih']);
        $pelatihRole->givePermissionTo([
            'view eskul',
            'manage eskul members',
            'view schedule',
            'create schedule',
            'edit schedule',
            'delete schedule',
            'view attendance',
            'create attendance',
            'edit attendance',
            'view bulletin',
            'create bulletin',
            'edit bulletin',
            'delete bulletin',
            'view test',
            'create test',
            'edit test',
            'delete test',
        ]);

        // Wakil Pelatih
        $wakilPelatihRole = Role::create(['name' => 'wakil_pelatih']);
        $wakilPelatihRole->givePermissionTo([
            'view eskul',
            'view schedule',
            'create schedule',
            'edit schedule',
            'delete schedule',
            'view attendance',
            'create attendance',
            'edit attendance',
            'view bulletin',
            'create bulletin',
            'edit bulletin',
            'delete bulletin',
            'view test',
            'create test',
            'edit test',
            'delete test',
        ]);

        // Siswa
        $siswaRole = Role::create(['name' => 'siswa']);
        $siswaRole->givePermissionTo([
            'view eskul',
            'view schedule',
            'view attendance',
            'view bulletin',
            'view test',
            'take test',
        ]);
    }
} 