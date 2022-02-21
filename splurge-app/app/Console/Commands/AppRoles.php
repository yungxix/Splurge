<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Models\User;
use App\Models\UserRole;

class AppRoles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:role {role} {action} {user}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Add or remove user from role';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $user = User::findOrFail($this->argument('user'));

        switch ($this->argument('action')) {
            case 'add':
            case 'a':
                return $this->addRoleToUser($user);
            case 'remove':
            case 'delete':
            case 'd':
                return $this->removeRoleFromUser($user);
        }   
        return 0;
    }

    private function addRoleToUser(User $user) {
        $role = $this->argument('role');
        $this->info("Adding user {$user->id} to role $role...");
        $attrs = ['user_id' => $user->id, 'name' => $role];
        if (is_null(UserRole::where($attrs)->selectRaw('1')->first())) {
            UserRole::create($attrs);
        }
        return 0;
    }

    private function removeRoleFromUser(User $user) {
        $role = $this->argument('role');
        $attrs = ['user_id' => $user->id, 'name' => $role];
        UserRole::where($attrs)->delete();
        return 0;
    }
}
