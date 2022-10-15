<?php

namespace App\Interface;

enum FraudStatus: string
{
    case Accept = "accept";
    case Deny = "deny";
    case Challenge = "challenge";

    function all()
    {
        return [
            FraudStatus::Accept,
            FraudStatus::Deny,
            FraudStatus::Challenge,
        ];
    }
}
