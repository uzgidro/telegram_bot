<?php

namespace App\Models;

use DateTime;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Messages extends Model
{
    use HasFactory;

    public int $id;
    public int $chatId;
    public string $text;
    public string $type;
    public DateTime $created_at;
    public DateTime $updated_at;

    public static function createFromData($data): Messages
    {
        // Создаем новый экземпляр модели Users
        $message = new self();

        // Устанавливаем значения свойств из объекта $data
        $message->id = $data->id;
        $message->chatId = $data->chat_id;
        $message->text = $data->text;
        $message->type = $data->type;

        // Преобразуем даты в формат DateTime, если они присутствуют
        $message->created_at = isset($data->created_at) ?
            DateTime::createFromFormat('Y-m-d H:i:s', $data->created_at) : null;

        $message->updated_at = isset($data->updated_at) ?
            DateTime::createFromFormat('Y-m-d H:i:s', $data->updated_at) : null;

        // Возвращаем созданный объект
        return $message;
    }
}
