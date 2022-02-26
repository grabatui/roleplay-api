<?php

namespace App\Containers\AppSection\Game\Data\Seeders;

use App;
use App\Containers\AppSection\Authorization\Tasks\CreatePermissionTask;
use App\Containers\AppSection\Authorization\Tasks\GetAllPermissionsTask;
use App\Ship\Parents\Seeders\Seeder;
use Spatie\Permission\Models\Permission;

class UserWorldsPermissionsSeeder_1 extends Seeder
{
    public function run(): void
    {
        $allPermissions = app(GetAllPermissionsTask::class)->run(true);

        $changeUseWorldsPermission = $allPermissions?->first(
            static fn(Permission $permission): bool => $permission->name === 'change-user-worlds',
        );

        if (! App::runningUnitTests() && $changeUseWorldsPermission) {
            return;
        }

        $createPermissionTask = app(CreatePermissionTask::class);
        $createPermissionTask->run('change-user-worlds', 'Create, Update, Delete user worlds.');
    }
}
