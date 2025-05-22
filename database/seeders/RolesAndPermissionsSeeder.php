<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run()
    {
        // Facoltativo: pulisce i ruoli e permessi esistenti
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Elimina i vecchi ruoli (opzionale)
        Role::truncate();
        Permission::truncate();

        // Crea i permessi base
        $permissions = [
            'view jobs',
            'create jobs',
            'edit jobs',
            'delete jobs',
            'apply for jobs'
        ];

        foreach ($permissions as $perm) {
            Permission::firstOrCreate(['name' => $perm]);
        }

        // Crea ruoli standard
        $recruiter = Role::firstOrCreate(['name' => 'recruiter']);
        $candidate = Role::firstOrCreate(['name' => 'candidate']);

        // Assegna permessi ai ruoli
        $recruiter->syncPermissions(['create jobs', 'edit jobs', 'delete jobs']);
        $candidate->syncPermissions(['view jobs', 'apply for jobs']);
    }
}
