<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Voucher extends Model
{
    use HasUuids, SoftDeletes;

    protected $table = 'vouchers';
    protected $primaryKey = 'id';
    protected $keyType = 'string';
    public $incrementing = false;
    public $timestamps = false;

    public function uniqueIds(): array
    {
        return [$this->primaryKey, 'voucher_code'];
    }

    public function scopeActive(Builder $builder): void
    {
        $builder->where('is_active', true);
    }

    public function scopeNonActive(Builder $builder): void
    {
        $builder->where('is_active', false);
    }

    public function comments(): MorphMany
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    public function latestComment(): MorphOne
    {
        return $this->morphOne(Comment::class, 'commentable')->latest('created_at');
    }

    public function oldestComment(): MorphOne
    {
        return $this->morphOne(Comment::class, 'commentable')->oldest('created_at');
    }

}
