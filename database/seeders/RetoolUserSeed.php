<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Symfony\Component\Console\Output\ConsoleOutput;

class RetoolUserSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $retoolUser = [
            'name' => "Retool",
            'email' => "retool@retool.com",
            'username' => "retool",
            'password' => Str::random(24),
            'phone_number' => "000000000",
            'type' => "admin",
        ];

        $con = new ConsoleOutput();
        $con->writeln("Username: " . $retoolUser['username']);
        $con->writeln("Password: " . $retoolUser['password']);

        $retoolUser["password"] = Hash::make($retoolUser["password"]);

        $user = User::firstOrCreate([
            "username" => $retoolUser["username"],
        ], $retoolUser);

        $user->fill($retoolUser);
        $user->save();
    }
}
