<?php

namespace App\Containers\AppSection\Authorization\Data\Seeders;

use App;
use App\Containers\AppSection\Authorization\Tasks\CreatePermissionTask;
use App\Containers\AppSection\Authorization\Tasks\GetAllPermissionsTask;
use App\Ship\Parents\Seeders\Seeder;
use Spatie\Permission\Models\Permission;

class AuthorizationPermissionsSeeder_1 extends Seeder
{
    public function run(): void
    {
        $allPermissions = app(GetAllPermissionsTask::class)->run(true);

        $manageRolesPermission = $allPermissions?->first(
            static fn(Permission $permission): bool => $permission->name === 'manage-roles',
        );

        if (! App::runningUnitTests() && $manageRolesPermission) {
            return;
        }

        // Default Permissions ----------------------------------------------------------
        $createPermissionTask = app(CreatePermissionTask::class);
        $createPermissionTask->run('manage-roles', 'Create, Update, Delete, Get All, Attach/detach permissions to Roles and Get All Permissions.');
        $createPermissionTask->run('create-admins', 'Create new Users (Admins) from the dashboard.');
        $createPermissionTask->run('manage-admins-access', 'Assign users to Roles.');
        $createPermissionTask->run('access-dashboard', 'Access the admins dashboard.');
    }
}
