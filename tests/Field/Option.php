<?php

namespace EBMQ\Tests\Field;

use EBMQ\Tests\Model\PlaceOfBirth;

class Option
{
    public static function getLuPlaceOfBirth(): array
    {
        $addressStates = PlaceOfBirth::find(1)->get()->toArray();

        $options = array_reduce($addressStates, function($acc, $value){
            $acc[] = [
                'key' => $value['id'],
                'value' => $value['description'],
            ];
            return $acc;
        }, []);

        return $options;
    }
}
