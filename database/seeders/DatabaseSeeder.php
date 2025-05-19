<?php

namespace Database\Seeders;

use App\Models\Client;
use App\Models\Product;
use App\Models\ProductType;
use App\Models\Region;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Shop;
use App\Models\ShopOrder;
use App\Models\ShopProduct;
use App\Models\ShopType;
use App\Models\TransactionHistory;
use App\Models\User;
use App\Models\Wallet;
use App\Models\WalletAddress;
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
        Region::insert([
            ['parent_id' => null, 'name' => 'Sy'],
            ['parent_id' => null, 'name' => 'SAK'],
            ['parent_id' => null, 'name' => 'UAE'],
            ['parent_id' => null, 'name' => 'EG'],
            ['parent_id' => null, 'name' => 'AHK'],
        ]);
        Region::factory()->count(50)->create();
        Client::factory()->count(10)->create();
        ProductType::factory()->count(20)->create();
        Product::factory()->count(100)->create();
        ShopType::factory()->count(10)->create();
        User::factory()->asMerchant()
            ->has(Shop::factory()
                ->has(ShopProduct::factory()->count(20))
                ->has(ShopOrder::factory()->count(50)))
            ->has(Wallet::factory()
                ->has(TransactionHistory::factory()->count(5))
                ->has(TransactionHistory::factory()->withdraw()->count(5))
            ->has(WalletAddress::factory()->count(3)))
            ->count(3)->create();


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
