<?php

namespace EBMFields\Field;

class Option
{
    public static function getLuPlaceOfBirth(): array
    {
        $addressStates = PlaceOfBirth::getAddressStates()->toArray();

        $options = array_reduce($addressStates, function($acc, $value){
            $acc[] = [
                'key' => $value->PLACE_OF_BIRTH_ID,
                'value' => $value->PLACE_OF_BIRTH_DESC,
            ];
            return $acc;
        }, []);

        return $options;
    }
}
