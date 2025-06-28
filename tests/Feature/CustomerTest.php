<?php

namespace Tests\Feature;

use App\Models\Customer;
use App\Models\Wallet;
use Database\Seeders\CategorySeeder;
use Database\Seeders\CustomerSeeder;
use Database\Seeders\ImageSeeder;
use Database\Seeders\ProductSeeder;
use Database\Seeders\VirtualAccountSeeder;
use Database\Seeders\WalletSeeder;
use Tests\TestCase;

class CustomerTest extends TestCase
{
    public function testOneToOne()
    {
        $this->seed([CustomerSeeder::class, WalletSeeder::class]);

        $customer = Customer::find('FAHMI');
        self::assertNotNull($customer);

//        $wallet = Wallet::where('customer_id', $customer->id)->first();
        $wallet = $customer->wallet;
        self::assertNotNull($wallet);
        self::assertEquals(1_000_000, $wallet->amount);
    }

    public function testOneToOneQuery()
    {
        $customer = new Customer();
        $customer->id = 'FAHMI';
        $customer->name = 'Fahmi';
        $customer->email = 'fahmi@gmail.com';
        $customer->save();

        $wallet = new Wallet();
        $wallet->amount = 1_000_000;

        $customer->wallet()->save($wallet);

        self::assertNotNull($wallet->customer_id);
    }

    public function testHasOneThrough()
    {
        $this->seed([CustomerSeeder::class, WalletSeeder::class, VirtualAccountSeeder::class]);

        $customer = Customer::find('FAHMI');
        self::assertNotNull($customer);

        $virtualAccount = $customer->virtualAccount;
        self::assertNotNull($virtualAccount);
        self::assertEquals('BCA', $virtualAccount->bank);
    }

    public function testManyToMany()
    {
        $this->seed([CustomerSeeder::class, CategorySeeder::class, ProductSeeder::class]);

        $customer = Customer::find('FAHMI');
        self::assertNotNull($customer);

        $customer->likeProducts()->attach('1');

        $products = $customer->likeProducts;
        self::assertCount(1, $products);
        self::assertNotNull('1', $products[0]->id);
    }

    public function testManyToManyDetach()
    {
        $this->testManyToMany();

        $customer = Customer::find('FAHMI');
        $customer->likeProducts()->detach('1');

        $products = $customer->likeProducts;
        self::assertCount(0, $products);
    }

    public function testPivotAttribute()
    {
        $this->testManyToMany();

        $customer = Customer::find('FAHMI');
        $products = $customer->likeProducts;

        foreach ($products as $product) {
            $pivot = $product->pivot;
            self::assertNotNull($pivot);
            self::assertNotNull($pivot->customer_id);
            self::assertNotNull($pivot->product_id);
            self::assertNotNull($pivot->created_at);
        }
    }

    public function testPivotAttributeCondition()
    {
        $this->testManyToMany();

        $customer = Customer::find('FAHMI');
        $products = $customer->likeProductsLastWeek;

        foreach ($products as $product) {
            $pivot = $product->pivot;
            self::assertNotNull($pivot);
            self::assertNotNull($pivot->customer_id);
            self::assertNotNull($pivot->product_id);
            self::assertNotNull($pivot->created_at);
        }
    }

    public function testPivotModel()
    {
        $this->testManyToMany();

        $customer = Customer::find('FAHMI');
        $products = $customer->likeProducts;

        foreach ($products as $product) {
            $pivot = $product->pivot; // object Model Like
            self::assertNotNull($pivot);
            self::assertNotNull($pivot->customer_id);
            self::assertNotNull($pivot->product_id);
            self::assertNotNull($pivot->created_at);

            self::assertNotNull($pivot->customer);
            self::assertNotNull($pivot->product);
        }
    }

    public function testOneToOnePolymorphic()
    {
        $this->seed([CustomerSeeder::class, ImageSeeder::class]);

        $customer = Customer::find('FAHMI');
        self::assertNotNull($customer);

        $image = $customer->image;
        self::assertNotNull($image);
        self::assertEquals('https://www.devcodefactory.com/customers/image/1.jpg', $image->url);
    }

    public function testEager()
    {
        $this->seed([CustomerSeeder::class, WalletSeeder::class, ImageSeeder::class]);

        $customer = Customer::find('FAHMI');
        self::assertNotNull($customer);
    }

}
