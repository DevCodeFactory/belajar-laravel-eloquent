<?php

namespace Tests\Feature;

use App\Models\Category;
use Database\Seeders\CategorySeeder;
use Tests\TestCase;
use function PHPUnit\Framework\assertEquals;

class CategoryTest extends TestCase
{
    public function testInsert()
    {
        $category = new Category();
        $category->id = 'GADGET';
        $category->name = 'Gadget';
        $result = $category->save();

        self::assertTrue($result);
    }

    public function testInsertMany()
    {
        $categories = [];
        for ($i = 0; $i < 10; $i++) {
            $categories[] = [
                'id' => "ID $i",
                'name' => "Name $i"
            ];
        }

//        $result = Category::query()->insert($categories);
        $result = Category::insert($categories);
        self::assertTrue($result);

//        $total = Category::query()->count();
        $total = Category::count();
        self::assertEquals(10, $total);
    }

    public function testFind()
    {
        $this->seed(CategorySeeder::class);

        $category = Category::query()->find('FOOD');

        self::assertNotNull($category);
        assertEquals('FOOD', $category->id);
        assertEquals('Food', $category->name);
        assertEquals('Food Category', $category->description);
    }

    public function testUpdate()
    {
        $this->seed(CategorySeeder::class);

        $category = Category::find('FOOD');
        $category->name = 'Food Updated';

        $result = $category->update();
        self::assertTrue($result);
    }

    public function testSelect()
    {
        for ($i = 0; $i < 5; $i++) {
            $category = new Category();
            $category->id = 'ID ' . $i;
            $category->name = 'Name ' . $i;
            $category->save();
        }

        $categories = Category::whereNull('description')->get();
        self::assertEquals(5, $categories->count());
        $categories->each(function (Category $category) {
            self::assertNull($category->description);

            $category->description = "Updated";
            $category->update();
        });
    }

    public function testUpdateMany()
    {
        $categories = [];
        for ($i = 0; $i < 10; $i++) {
            $categories[] = [
                'id' => "ID $i",
                'name' => "Name $i"
            ];
        }

        $result = Category::insert($categories);
        self::assertTrue($result);

        Category::whereNull('description')->update([
            'description' => 'Updated'
        ]);
        $total = Category::where('description', '=', 'Updated')->count();
        self::assertEquals(10, $total);
    }

}
