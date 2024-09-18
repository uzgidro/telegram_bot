<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Messages extends Model
{
    use HasFactory;

    public int $id;
    public string $text;
    public string $username;
    public string $first_name;
    public string $last_name;
    public string $type;
    public int $created_at;

    protected $table = 'messages';

    protected $fillable = [
      'text',
      'username',
      'first_name',
      'last_name',
      'type',
      'created_at'
    ];
}
