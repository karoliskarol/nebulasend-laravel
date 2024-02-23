<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\Builder as QueryBuilder;

class EmailMessage extends Model
{
    use HasFactory, HasUlids, SoftDeletes;

    public $fillable = ['sent_to', 'subject', 'message', 'user_id', 'sent_by', 'recipient', 'summary'];
    public $incrementing = false;

    const UPDATED_AT = null;

    public function scopeSearch(Builder $query, $search): Builder|QueryBuilder
    {
        $arr = ['like', '%' . $search . '%'];

        return $query->where(function ($query) use ($arr) {
            $query->where('message', ...$arr)
                ->orWhere('subject', ...$arr)
                ->orWhere('recipient', ...$arr);
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public static function booted()
    {
        static::creating(function ($model) {
            $model->id = Str::ulid();
        });
    }
}
