<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OpenAI extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'openai_chat';

    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
