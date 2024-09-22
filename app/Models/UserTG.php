<?php

namespace App\Models;

class UserTG
{
    public int $id;
    public string $firstName;
    public ?string $lastName;
    public ?string $username;
    public ?string $languageCode;

    /**
     * @param int $id
     * @param string $firstName
     * @param string|null $lastName
     * @param string|null $username
     */
    public function __construct(mixed $data)
    {
        if ($data === null) {
            return;
        }

        $this->id = $data['id'];
        $this->firstName = $data['first_name'];
        $this->lastName = $data['last_name'] ?? null;
        $this->username = $data['username'] ?? null;
        $this->languageCode = $data['language_code'] ?? null;
    }


}
