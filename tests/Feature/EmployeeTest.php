<?php

namespace Tests\Feature;

use App\Models\Employee;
use Tests\TestCase;

class EmployeeTest extends TestCase
{
    public function testFactory()
    {
        $employee1 = Employee::factory()->programmer()->make();
        $employee1->id = '1';
        $employee1->name = 'Employee 1';
        $employee1->save();

        self::assertNotNull(Employee::where('id', '1')->first());

        $employee2 = Employee::factory()->seniorProgrammer()->create([
            'id' => '2',
            'name' => 'Employee 2',
        ]);
        self::assertNotNull($employee2);
        self::assertEquals('2', $employee2->id);
    }

}
