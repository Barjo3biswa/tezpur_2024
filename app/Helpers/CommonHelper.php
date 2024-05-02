<?php
namespace App\Helpers;

class CommonHelper
{
    public static function admission_decline_rules()
    {
        return [
            "I have selected in other programm in Tezpur University.",
            "I got admission to another university.",
            // "Unable to arrange admission fee.",
            // "Don't want to continue my studies.",
            // "Will take admission on next session.",
            "Declined due to non-fulfilment of requirements for admission",
            "Other reason",
        ];
    }

    public static function admission_withdraw_rules()
    {
        return [
            "I have selected in other programm in Tezpur University.",
            "I got admission to another university.",
            "Other reason",
        ];
    }
}
