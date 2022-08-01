<?php

namespace App\Http\Controllers;

use App\Interface\Midtrans;
use Illuminate\Http\Request;
use Symfony\Component\Console\Output\ConsoleOutput;

class MidtransController extends Controller
{
    public function webhook(Request $req)
    {
        // Simply logs the notification output
        $con = new ConsoleOutput();
        $midtransClient = new Midtrans();

        $con->writeln("==============================================");
        $con->writeln("Transaction ID: " . $req->input("transaction_id"));
        $con->writeln("Transaction time: " . $req->input("transaction_time"));
        $con->writeln("Transcation status: " . $req->input("transaction_status"));
        $con->writeln("Trancaction Signature: " . $req->input("signature_key"));
        $con->writeln("Signature verified: " . ($midtransClient->verifySignature(
            $req->input("order_id"),
            $req->input("status_code"),
            $req->input("gross_amount"),
            $req->input("signature_key")
        ) ? "Verified" : "Invalid"));
        $con->writeln("==============================================");
    }
}
