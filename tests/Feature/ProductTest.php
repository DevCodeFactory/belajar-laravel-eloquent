<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use Database\Seeders\CategorySeeder;
use Database\Seeders\CommentSeeder;
use Database\Seeders\ImageSeeder;
use Database\Seeders\ProductSeeder;
use Database\Seeders\VoucherSeeder;
use Tests\TestCase;
use function PHPUnit\Framework\assertEquals;

class ProductTest extends TestCase
{
    public function testOneToMany()
    {
        $this->seed([CategorySeeder::class, ProductSeeder::class]);

        $product = Product::find('1');
        self::assertNotNull($product);

        $category = $product->category;
        self::assertNotNull($category);
        self::assertEquals('FOOD', $category->id);
    }

    public function testHasOneOfMany()
    {
        $this->seed([CategorySeeder::class, ProductSeeder::class]);

        $category = Category::find('FOOD');
        self::assertNotNull($category);

        $cheapestProduct = $category->cheapestProduct;
        self::assertNotNull($cheapestProduct);
        self::assertEquals('1', $cheapestProduct->id);

        $mostExpensiveProduct = $category->mostExpensiveProduct;
        self::assertNotNull($mostExpensiveProduct);
        self::assertEquals('2', $mostExpensiveProduct->id);
    }

    public function testOneToOnePolymorphic()
    {
        $this->seed([CategorySeeder::class, ProductSeeder::class, ImageSeeder::class]);

        $product = Product::find('1');
        self::assertNotNull($product);

        $image = $product->image;
        self::assertNotNull($image);
        self::assertEquals('https://www.devcodefactory.com/products/image/1.jpg', $image->url);
    }

    public function testOneToManyPolymorphic()
    {
        $this->seed([CategorySeeder::class, ProductSeeder::class, VoucherSeeder::class, CommentSeeder::class]);

        $product = Product::find('1');
        self::assertNotNull($product);

        $comments = $product->comments;
        foreach ($comments as $comment) {
            assertEquals(Product::class, $comment->commentable_type);
            assertEquals($product->id, $comment->commentable_id);
        }
    }

    public function testOneOfManyPolymorphic()
    {
        $this->seed([CategorySeeder::class, ProductSeeder::class, VoucherSeeder::class, CommentSeeder::class]);

        $product = Product::find('1');
        self::assertNotNull($product);

        $latestComment = $product->latestComment;
        self::assertNotNull($latestComment);

        $oldestComment = $product->oldestComment;
        self::assertNotNull($oldestComment);
    }

}
