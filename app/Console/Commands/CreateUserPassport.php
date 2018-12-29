<?php

namespace App\Console\Commands;


use App\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Validator;


class CreateUserPassport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'token:user 
                                    { --username= : Login new user }
                                    { --email= : Email new user }
                                    { --pass= : Password new user }';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create user, and get toke_id ';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $username = $this->option("username");
        $email = $this->option("email");
        $pass = $this->option("pass");


        if (!$username) {
            $username = $this->ask('Insert new username');
        }

        if (!$email) {
            $email = $this->ask('Insert new email');
        }

        if (!$pass) {

            $pass1 = $this->secret('Insert new pass');
            $pass2 = $this->secret('Confirm new password');

            if (strcmp($pass1, $pass2) === 0) {
                $pass = $pass1;
            } else {

                $this->error(' Passwords do not match');
                return;
            }
        }

        $validator = Validator::make(["name" => $username, "pass" => $pass, "email" => $email], [
            'name' => 'required|string|min:5|unique:users',
            'pass' => 'required|string|min:6',
            'email' => 'required|unique:users|email'
        ]);

        if ($validator->fails()) {

            foreach ($validator->errors()->toArray() as $fail) {
                $this->error($fail[0]);
                $this->line('');
            }
            return;
        }

       if($this->createdUser($username,$email,$pass)){
           $this->info('User and token successfully created');
       }


    }

    private function createdUser($username, $email, $password)
    {
        try {
            $user = new User([
                "name" => $username,
                "email" => $email,
                "email_verified_at" => new \DateTime(),
                "password" => password_hash($password, PASSWORD_BCRYPT),
                "created_at" => new \DateTime()
            ]);

            $user->save();

            $user->createToken('Personal Access Token')->token->save();

        } catch (\Exception $ex) {
            $this->error($ex->getMessage());
            return false;
        }
        return true;
    }

}
