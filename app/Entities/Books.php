<?php

namespace App\Entities;
use CodeIgniter\Entity\Entity;

class Books extends Entity
{
    final public function toAssociativeArray():array
    {
        $result = [];

        foreach ($this->toArray() as $item) {
            if (isset($item['@attributes']['id'])) {
                $id = $item['@attributes']['id'];
                $result[$id] = $item;
            }
        }

        return $result;
    }

}