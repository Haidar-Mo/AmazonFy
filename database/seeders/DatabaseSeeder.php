<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(RoleSeeder::class);
        $this->call(PermissionSeeder::class);


        \App\Models\Product::factory()->count(100)->create();
        \App\Models\Shop::factory()->count(10)->create();
        \App\Models\ShopProduct::factory()->count(500)->create();
        \App\Models\ShopOrder::factory()->count(5)->create();
        \App\Models\OrderItem::factory()->count(25)->create();

        /*
                $user = User::factory()->create([
                    'name' => 'Admin',
                    'email' => 'admin@admin.com',
                    'phone_number' => '123456789',
                    'password' => bcrypt('AdminPassword')
                ]);
                $user->assignRole(Role::where('name', 'supervisor')->first());
                $user->givePermissionTo(Permission::where('guard_name', 'api')->get());
            */
    }
}
