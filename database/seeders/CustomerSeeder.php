<?php

namespace Database\Seeders;

use App\Models\Customer;
use Illuminate\Database\Seeder;

class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $customer = new Customer();
        $customer->id = 'FAHMI';
        $customer->name = 'Fahmi';
        $customer->email = 'fahmi@gmail.com';
        $customer->save();
    }
}
