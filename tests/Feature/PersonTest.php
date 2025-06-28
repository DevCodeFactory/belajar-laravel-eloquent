<?php

namespace Tests\Feature;

use App\Models\Address;
use App\Models\Person;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PersonTest extends TestCase
{
    public function testPerson()
    {
        $person = new Person();
        $person->first_name = 'Azdy';
        $person->last_name = 'Fahmi';
        $person->save();

        self::assertEquals('AZDY Fahmi', $person->full_name);

        $person->full_name = 'Gibran Ramadhan';
        $person->save();

        self::assertEquals('GIBRAN', $person->first_name);
        self::assertEquals('Ramadhan', $person->last_name);
    }

    public function testAttributeCasting()
    {
        $person = new Person();
        $person->first_name = 'Azdy';
        $person->last_name = 'Fahmi';
        $person->save();

        self::assertNotNull($person->created_at);
        self::assertNotNull($person->updated_at);
        self::assertInstanceOf(Carbon::class, $person->created_at);
        self::assertInstanceOf(Carbon::class, $person->updated_at);
    }

    public function testCustomCasts()
    {
        $person = new Person();
        $person->first_name = 'Azdy';
        $person->last_name = 'Fahmi';
        $person->address = new Address('Jalan Belum Jadi', 'Jakarta', 'Indonesia', '123456');
        $person->save();

        self::assertEquals('Jalan Belum Jadi', $person->address->street);
        self::assertEquals('Jakarta', $person->address->city);
        self::assertEquals('Indonesia', $person->address->country);
        self::assertEquals('123456', $person->address->postal_code);
    }

}
