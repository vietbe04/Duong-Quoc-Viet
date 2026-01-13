<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MigrateUserRolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Migrate existing user roles to single role_id
        $userRoles = \DB::table('user_role')->get();

        foreach ($userRoles->groupBy('user_id') as $userId => $roles) {
            // If user has multiple roles, assign the first one (usually the most important)
            $firstRole = $roles->first();
            \DB::table('users')
                ->where('id', $userId)
                ->update(['role_id' => $firstRole->role_id]);
        }

        // Optional: Drop the user_role table after migration
        // \DB::statement('DROP TABLE user_role');

        $this->command->info('User roles migrated successfully!');
        $this->command->info('Users with multiple roles have been assigned their first role.');
    }
}
