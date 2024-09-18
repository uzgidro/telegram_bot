<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Users extends Model
{
    use HasFactory;

    public int $id;
    public string $chat_id;
    public string $username;
    public string $first_name;
    public string $last_name;
    public string $language = 'undefined';
    public string $destination = '/start';
    public bool $is_admin = false;
    public bool $is_anticor = false;
    public bool $is_murojaat = false;

    protected $table = 'users';

    protected $fillable = [
        'chat_id',
        'username',
        'first_name',
        'last_name',
        'language',
        'destination',
        'is_admin',
        'is_anticor',
        'is_murojaat',
    ];
}
