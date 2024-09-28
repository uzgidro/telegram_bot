<?php

namespace App\Models;

class MessageEntitiesTG
{
    public int $offset;
    public int $length;
    public string $type;

    /**
     * @param mixed $data
     */
    public function __construct(mixed $data)
    {
        if ($data === null) {return;}

        $this->offset = $data['offset'];
        $this->length = $data['length'];
        $this->type = $data['type'];
    }


}
