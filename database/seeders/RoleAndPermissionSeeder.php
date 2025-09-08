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
            'view eskul members',
            
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
            'view test result',

            // Member management
            'register eskul',
            'approve member',
            'reject member',
            
            // User management
            'manage users',
            'manage staff',

            // Profile & Gallery permissions
            'view profile',
            'edit profile',
            'upload photo',
            'view gallery',
            'manage gallery',
            'manage achievements',
            
            // Report permissions
            'generate report',
            'export data',
            'view statistics',
            
            // Communication permissions
            'send announcement',
            'create discussion',
            'reply discussion',
            'manage comments',
            
            // Achievement & Certificate
            'create certificate',
            'issue certificate',
            'view achievements',
            
            // Event Management
            'manage event',
            'create event',
            'edit event',
            'delete event',
            'register event',
            
            // Evaluation System
            'create evaluation',
            'submit evaluation',
            'view evaluation',
            
            // File Management
            'upload materials',
            'download materials',
            'manage documents',
        ];

        // Create all permissions
        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // Create roles and assign permissions
        
        // Admin
        $adminRole = Role::create(['name' => 'admin']);
        $adminRole->givePermissionTo(Permission::all());

        // Pembina (sebelumnya Pembimbing)
        $pembinaRole = Role::create(['name' => 'pembina']);
        $pembinaRole->givePermissionTo([
            'view eskul',
            'view schedule',
            'view attendance',
            'view bulletin',
            'view test',
            'view profile',
            'view gallery',
            'view statistics',
            'view evaluation',
            'generate report',
            'view achievements',
            'send announcement',
            'reply discussion',
            'download materials',
        ]);

        // Pelatih
        $pelatihRole = Role::create(['name' => 'pelatih']);
        $pelatihRole->givePermissionTo([
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
            'edit profile',
            'upload photo',
            'view gallery',
            'manage gallery',
            'create certificate',
            'issue certificate',
            'create event',
            'edit event',
            'delete event',
            'create evaluation',
            'view evaluation',
            'upload materials',
            'download materials',
            'send announcement',
            'create discussion',
            'reply discussion',
            'manage comments',
            'approve member',
            'reject member',
            'manage event',
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
            'view profile',
            'view gallery',
            'register event',
            'submit evaluation',
            'download materials',
            'reply discussion',
            'view achievements',
            'register eskul',
        ]);

        // Pimpinan
        $pimpinanRole = Role::create(['name' => 'pimpinan']);
        $pimpinanRole->givePermissionTo([
            'view evaluation',
            'generate report',
            'view statistics',
            'export data',
        ]);
    }
} 