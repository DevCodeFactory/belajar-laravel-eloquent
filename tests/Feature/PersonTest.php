<?php

namespace Tests\Feature;

use App\Models\Person;
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

}
