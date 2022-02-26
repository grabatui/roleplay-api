<?php

namespace App\Containers\AppSection\User\Data\Seeders;

use App;
use App\Containers\AppSection\Authorization\Tasks\CreatePermissionTask;
use App\Containers\AppSection\Authorization\Tasks\GetAllPermissionsTask;
use App\Ship\Parents\Seeders\Seeder;
use Spatie\Permission\Models\Permission;

class UserPermissionsSeeder_1 extends Seeder
{
    public function run(): void
    {
        $allPermissions = app(GetAllPermissionsTask::class)->run(true);

        $searchUsersPermission = $allPermissions?->first(
            static fn(Permission $permission): bool => $permission->name === 'search-users',
        );

        if (! App::runningUnitTests() && $searchUsersPermission) {
            return;
        }

        // Default Permissions ----------------------------------------------------------
        $createPermissionTask = app(CreatePermissionTask::class);
        $createPermissionTask->run('search-users', 'Find a User in the DB.');
        $createPermissionTask->run('list-users', 'Get All Users.');
        $createPermissionTask->run('update-users', 'Update a User.');
        $createPermissionTask->run('delete-users', 'Delete a User.');
        $createPermissionTask->run('refresh-users', 'Refresh User data.');
    }
}
