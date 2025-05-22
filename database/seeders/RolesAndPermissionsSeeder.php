<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        // ✅ Rimuove i permessi in cache
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // ✅ Disabilita vincoli temporaneamente per evitare errori di FK
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // ✅ Pulisce le tabelle correlate ai permessi/ruoli
        DB::table('model_has_roles')->truncate();
        DB::table('role_has_permissions')->truncate();
        DB::table('permissions')->truncate();
        DB::table('roles')->truncate();

        // ✅ Riattiva i vincoli
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // 🎯 Crea permessi
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

        // 🎯 Crea ruoli
        $recruiter = Role::firstOrCreate(['name' => 'recruiter']);
        $candidate = Role::firstOrCreate(['name' => 'candidate']);

        // 🔗 Assegna permessi ai ruoli
        $recruiter->syncPermissions([
            'create jobs',
            'edit jobs',
            'delete jobs'
        ]);

        $candidate->syncPermissions([
            'view jobs',
            'apply for jobs'
        ]);
    }
}
