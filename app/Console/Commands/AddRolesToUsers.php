<?php

namespace App\Console\Commands;

use App\Models\Role;
use App\Models\User;
use Illuminate\Console\Command;

class AddRolesToUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'roles:base-user';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Gives existing users a base user role';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $baseRole = Role::where('name', 'base_user')->first();

        $users = User::query()
            ->where('role_id', null)
            ->whereNot('email', config('app.owner_email'))
            ->update(['role_id' => $baseRole->id]);

        return Command::SUCCESS;
    }
}
