<?php

namespace App\Traits;

trait TokenGenerator
{
    /**
     * The "randomTokenWithLength" method of the model.
     *
     * @return string
     */
    private function randomTokenWithLength($length)
    {
        $token = substr(md5(uniqid(rand())), 0, $length - 2);
        $random_number = rand(0, 9);
        $random_alphabet = chr(rand(97, 122));
        return $token . $random_number . $random_alphabet;
    }
}
