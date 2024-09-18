<?php

namespace App\Models;

class Chat
{
    public int $id;
    public string $title;
    public string $username;
    public string $firstName;
    public string $lastName;

    /**
     * @param int $id
     * @param string $title
     * @param string $username
     * @param string $firstName
     * @param string $lastName
     */
    public function __construct(array $request)
    {
        $this->id = $request['id'];
        $this->title = $request['title'];
        $this->username = $request['username'];
        $this->firstName = $request['first_name'];
        $this->lastName = $request['last_name'];
    }


}
