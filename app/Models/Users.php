<?php

namespace App\Models;

use DateTime;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Users extends Model
{
    use HasFactory;

    public string $chat_id;
    public ?string $username;
    public string $first_name;
    public ?string $last_name;
    public ?string $language;
    public ?string $destination;
    public ?bool $is_admin;
    public ?bool $is_anticor;
    public ?bool $is_murojaat;
    public DateTime $created_at;
    public DateTime $updated_at;

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

    public static function createFromData($data): Users
    {
        // Создаем новый экземпляр модели Users
        $user = new self();

        // Устанавливаем значения свойств из объекта $data
        $user->chat_id = $data->chat_id;
        $user->username = $data->username ?? null; // Если username отсутствует, установим null
        $user->first_name = $data->first_name;
        $user->last_name = $data->last_name ?? null; // Если last_name отсутствует, установим null
        $user->language = $data->language ?? null; // Если language отсутствует, установим null
        $user->destination = $data->destination ?? null; // Если destination отсутствует, установим null
        $user->is_admin = $data->is_admin ?? null; // Если is_admin отсутствует, установим null
        $user->is_anticor = $data->is_anticor ?? null; // Если is_anticor отсутствует, установим null
        $user->is_murojaat = $data->is_murojaat ?? null; // Если is_murojaat отсутствует, установим null

        // Преобразуем даты в формат DateTime, если они присутствуют
        $user->created_at = isset($data->created_at) ?
            DateTime::createFromFormat('Y-m-d H:i:s', $data->created_at) : null;

        $user->updated_at = isset($data->updated_at) ?
            DateTime::createFromFormat('Y-m-d H:i:s', $data->updated_at) : null;

        // Возвращаем созданный объект
        return $user;
    }

}
