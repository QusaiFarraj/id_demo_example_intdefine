<?php

namespace App\Util;

class Shortener {

    protected $chars = "abcdfghjkmnpqrstvwxyz|ABCDFGHJKLMNPQRSTVWXYZ|0123456789";
    protected $codeLength = 7;

    public function createShortCode(): string
    {
        // API to whatever shortener service you need. Will redirect to site in long_url in DB.
        return 'https://site.com/' . $this->generateRandomString($this->codeLength);
    }

    protected function generateRandomString($length = 6): string
    {
        $sets = explode('|', $this->chars);
        $all = '';
        $randString = '';
        foreach($sets as $set){
            $randString .= $set[array_rand(str_split($set))];
            $all .= $set;
        }
        $all = str_split($all);
        for($i = 0; $i < $length - count($sets); $i++){
            $randString .= $all[array_rand($all)];
        }
        $randString = str_shuffle($randString);
        return $randString;
    }

}
