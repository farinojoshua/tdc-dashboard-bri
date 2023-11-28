<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Define permissions
        $permissions = [
            'view deployments', 'manage deployments',
            'view module deployments', 'manage module deployments',
            'view server type deployments', 'manage server type deployments',
            'view background jobs', 'manage background jobs',
            'view process background jobs', 'manage process background jobs',
            'view incidents user management', 'manage incidents user management',
            'view monthly target user management', 'manage monthly target user management',
            'view incidents brisol', 'manage incidents brisol',
            'view monthly target brisol', 'manage monthly target brisol',
            'view users', 'manage users',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // Create roles and assign permissions

        // Super Admin - has all permissions
        $superAdminRole = Role::create(['name' => 'Super Admin']);
        $superAdminRole->givePermissionTo(Permission::all());

        // Admin Deployments - can manage and view deployments
        $adminDeploymentsRole = Role::create(['name' => 'Admin Deployments']);
        $adminDeploymentsRole->givePermissionTo(['manage deployments', 'view deployments', 'manage module deployments', 'view module deployments', 'manage server type deployments', 'view server type deployments']);

        // User Deployments - can manage deployments and view module and server type deployments
        $userDeploymentsRole = Role::create(['name' => 'User Deployments']);
        $userDeploymentsRole->givePermissionTo(['manage deployments', 'view deployments', 'view module deployments', 'view server type deployments']);

        // Admin Background Jobs - can manage and view background jobs
        $adminBackgroundJobsRole = Role::create(['name' => 'Admin Background Jobs']);
        $adminBackgroundJobsRole->givePermissionTo(['manage background jobs', 'view background jobs', 'manage process background jobs', 'view process background jobs']);

        // User Background Jobs - can manage background jobs and view process background jobs
        $userBackgroundJobsRole = Role::create(['name' => 'User Background Jobs']);
        $userBackgroundJobsRole->givePermissionTo(['manage background jobs', 'view background jobs', 'view process background jobs']);

        // Admin User Management - can manage incidents and manage monthly target
        $adminUserManagementRole = Role::create(['name' => 'Admin Usman']);
        $adminUserManagementRole->givePermissionTo(['manage incidents user management', 'view incidents user management', 'manage monthly target user management', 'view monthly target user management']);

        // User User Management - can manage incidents and view monthly target
        $userUserManagementRole = Role::create(['name' => 'User Usman']);
        $userUserManagementRole->givePermissionTo(['manage incidents user management', 'view incidents user management', 'view monthly target user management']);

        // Admin Brisol - can manage incidents and manage monthly target
        $adminBrisolRole = Role::create(['name' => 'Admin Brisol']);
        $adminBrisolRole->givePermissionTo(['manage incidents brisol', 'view incidents brisol', 'manage monthly target brisol', 'view monthly target brisol']);

        // User Brisol - can manage incidents and view monthly target
        $userBrisolRole = Role::create(['name' => 'User Brisol']);
        $userBrisolRole->givePermissionTo(['manage incidents brisol', 'view incidents brisol', 'view monthly target brisol']);
    }
}
