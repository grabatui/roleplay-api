<?php

namespace App\Containers\AppSection\Authorization\Data\Seeders;

use App\Containers\AppSection\Authorization\Tasks\CreateRoleTask;
use App\Containers\AppSection\Authorization\Tasks\GetAllRolesTask;
use App\Ship\Parents\Seeders\Seeder;

class AuthorizationRolesSeeder_2 extends Seeder
{
    public function run(): void
    {
        $allRoles = app(GetAllRolesTask::class)->run(true);

        if ($allRoles) {
            return;
        }

        // Default Roles ----------------------------------------------------------------
        app(CreateRoleTask::class)->run('admin', 'Administrator', 'Administrator Role', 999);
    }
}
