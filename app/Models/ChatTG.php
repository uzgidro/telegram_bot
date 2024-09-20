<?php

namespace App\Models;

class ChatTG
{
    public int $id;
    public ?string $title;
    public ?string $firstName;
    public ?string $lastName;
    public ?string $username;

    /**
     * @param int $id
     * @param string $title
     * @param string $firstName
     * @param string $lastName
     * @param string $username
     */
    public function __construct(mixed $request)
    {
        if ($request === null) {
            return;
        }

        $this->id = $request['id'];
        $this->title = $request['title'] ?? null;
        $this->firstName = $request['first_name'] ?? null;
        $this->lastName = $request['last_name'] ?? null;
        $this->username = $request['username'] ?? null;
    }


}
