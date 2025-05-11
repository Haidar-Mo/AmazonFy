<?php

namespace Database\Seeders;

use App\Models\Client;
use App\Models\Product;
use App\Models\Region;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Shop;
use App\Models\ShopOrder;
use App\Models\ShopProduct;
use App\Models\User;
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

        Region::factory()->count(5)->create();
        Client::factory()->count(10)->create();
        Product::factory()->count(100)->create();
        User::factory()->asMerchant()
            ->has(
                Shop::factory()->has(
                    ShopProduct::factory()->count(20)
                )
            )
            ->count(3)->create();
        ShopOrder::factory()->count(50)->create();


        $user = User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@admin.com',
            'phone_number' => '123456789',
            'password' => bcrypt('AdminPassword')
        ]);
        $user->assignRole(Role::where('name', 'supervisor')->first());
        $user->givePermissionTo(Permission::where('guard_name', 'api')->get());

        $user = User::factory()->create([
            'name' => 'haidar',
            'email' => 'haidar@gmial.com',
            'phone_number' => '0000000000',
            'password' => bcrypt('password')
        ]);
        $user->assignRole(Role::where('name', 'client')->first());

        $user = User::factory()->create([
            'name' => 'user1',
            'email' => 'user1@admin.com',
            'phone_number' => '111111111',
            'password' => bcrypt('password')
        ]);
        $user->assignRole(Role::where('name', 'client')->first());

    }
}
