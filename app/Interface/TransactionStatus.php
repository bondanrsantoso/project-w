<?php

namespace App\Interface;

use App\Models\Transaction;

enum TransactionStatus: string
{
    case Unpaid = "unpaid";
    case Authorize = "authorize";
    case Capture = "capture";
    case Settlement = "settlement";
    case Deny = "deny";
    case Pending = "pending";
    case Cancel = "cancel";
    case Refund = "refund";
    case Partial_refund = "partial_refund";
    case Chargeback = "chargeback";
    case Partial_chargeback = "partial_chargeback";
    case Expire = "expire";
    case Failure = "failure";

    function all()
    {
        return [
            TransactionStatus::Authorize,
            TransactionStatus::Capture,
            TransactionStatus::Settlement,
            TransactionStatus::Deny,
            TransactionStatus::Pending,
            TransactionStatus::Cancel,
            TransactionStatus::Refund,
            TransactionStatus::Partial_refund,
            TransactionStatus::Chargeback,
            TransactionStatus::Partial_chargeback,
            TransactionStatus::Expire,
            TransactionStatus::Failure,
            TransactionStatus::Unpaid,
        ];
    }
}
