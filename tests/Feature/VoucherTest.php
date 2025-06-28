<?php

namespace Tests\Feature;

use App\Models\Voucher;
use Database\Seeders\CategorySeeder;
use Database\Seeders\CommentSeeder;
use Database\Seeders\ProductSeeder;
use Database\Seeders\TagSeeder;
use Database\Seeders\VoucherSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class VoucherTest extends TestCase
{
    public function testCreateVoucher()
    {
        $voucher = new Voucher();
        $voucher->name = 'Sample Voucher';
        $voucher->voucher_code = '234234';
        $voucher->save();

        self::assertNotNull($voucher->id);
    }

    public function testCreateVoucherUUID()
    {
        $voucher = new Voucher();
        $voucher->name = 'Sample Voucher';
        $voucher->save();

        self::assertNotNull($voucher->id);
        self::assertNotNull($voucher->voucher_code);
    }

    public function testSoftDelete()
    {
        $this->seed(VoucherSeeder::class);

        $voucher = Voucher::where('name', '=', 'Sample Voucher')->first();
        $voucher->delete();

        $voucher = Voucher::where('name', '=', 'Sample Voucher')->first();
        self::assertNull($voucher);

        $voucher = Voucher::withTrashed()->where('name', '=', 'Sample Voucher')->first();
        self::assertNotNull($voucher);
    }

    public function testLocalScope()
    {
        $voucher = new Voucher();
        $voucher->name = 'Sample Voucher';
        $voucher->is_active = true;
        $voucher->save();

        $total = Voucher::active()->count();
        self::assertEquals(1, $total);

        $total = Voucher::nonActive()->count();
        self::assertEquals(0, $total);
    }

    public function testOneToManyPolymorphic()
    {
        $this->seed([CategorySeeder::class, ProductSeeder::class, VoucherSeeder::class, CommentSeeder::class]);

        $voucher = Voucher::first();
        self::assertNotNull($voucher);

        $comments = $voucher->comments;
        self::assertNotNull($comments);

        foreach ($comments as $comment) {
            self::assertEquals(Voucher::class, $comment->commentable_type);
            self::assertEquals($voucher->id, $comment->commentable_id);
        }
    }

    public function testOneOfManyPolymorphic()
    {
        $this->seed([CategorySeeder::class, ProductSeeder::class, VoucherSeeder::class, CommentSeeder::class]);

        $voucher = Voucher::first();
        self::assertNotNull($voucher);

        $latestComment = $voucher->latestComment;
        self::assertNotNull($latestComment);

        $oldestComment = $voucher->oldestComment;
        self::assertNotNull($oldestComment);
    }

    public function testManyToManyPolymorphic()
    {
        $this->seed([CategorySeeder::class, ProductSeeder::class, VoucherSeeder::class, TagSeeder::class]);

        $voucher = Voucher::first();
        self::assertNotNull($voucher);

        $tags = $voucher->tags;
        self::assertNotNull($tags);

        foreach ($tags as $tag) {
            self::assertNotNull($tag->id);
            self::assertNotNull($tag->name);

            $products = $tag->products;
            self::assertNotNull($products);
            self::assertCount(1, $products);
        }
    }

}
