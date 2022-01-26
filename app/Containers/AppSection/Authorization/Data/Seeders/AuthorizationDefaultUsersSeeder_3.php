<?php

namespace App\Containers\AppSection\Authorization\Data\Seeders;

use App\Containers\AppSection\Authorization\Tasks\FindRoleTask;
use App\Containers\AppSection\User\Tasks\CreateUserByCredentialsTask;
use App\Containers\AppSection\User\Tasks\FindUserByEmailTask;
use App\Ship\Exceptions\NotFoundException;
use App\Ship\Parents\Seeders\Seeder;

class AuthorizationDefaultUsersSeeder_3 extends Seeder
{
    public function run(): void
    {
        try {
            app(FindUserByEmailTask::class)->run('admin@admin.com');
        } catch (NotFoundException) {
            // Default Users (with their roles) ---------------------------------------------
            $admin = app(CreateUserByCredentialsTask::class)->run('admin@admin.com', 'admin', 'Super Admin');
            $admin->assignRole(app(FindRoleTask::class)->run('admin'));
            $admin->email_verified_at = now();
            $admin->save();
        }
    }
}
