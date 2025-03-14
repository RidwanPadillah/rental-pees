<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = ['admin', 'user'];

        $permissions = [
            'create transaction',
            'view transaction',
            'approve transaction',
            'create device',
            'edit device',
            'delete device',
            'publish device',
            'view device'
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        foreach ($roles as $role) {
            $roleInstance = Role::firstOrCreate(['name' => $role]);

            if ($role === 'admin') {
                $roleInstance->givePermissionTo($permissions);
            } elseif ($role === 'user') {
                $roleInstance->givePermissionTo(['create transaction', 'view transaction']);
            }
        }

        $user = User::create([
            'name' => 'Administrator',
            'email' => 'admin@rental.com',
            'password' => Hash::make('password'),
        ]);

        $user->assignRole('admin');
    }
}
