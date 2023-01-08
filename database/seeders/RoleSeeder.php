<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Role::upsert([
            [
                'name' => 'base_user',
                'display_name' => 'Base User'
            ],
            [
                'name' => 'base_admin',
                'display_name' => 'Base Admin'
            ],
            [
                'name' => 'super_admin',
                'display_name' => 'Global Admin'
            ],
        ], ['name', 'display_name'], ['name']);

        $superAdminRole = Role::where('name', 'super_admin')->first();

        User::where('email', config('app.owner_email'))->update(['role_id' => $superAdminRole->id]);
    }
}
