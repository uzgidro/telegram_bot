<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Users extends Model
{
    use HasFactory;

    public string $chat_id;
    public string $username;
    public string $first_name;
    public string $last_name;
    public string $language;
    public string $destination;
    public bool $is_admin;
    public bool $is_anticor ;
    public bool $is_murojaat;

    protected $table = 'users';
    protected $primaryKey = 'chat_id';
    protected $keyType = 'string';
    public $incrementing = false;


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
