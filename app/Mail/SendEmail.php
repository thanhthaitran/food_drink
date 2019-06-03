<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\User;

class SendEmail extends Mailable
{
    use Queueable, SerializesModels;

    protected $user;
    public $password;

    /**
     * Create a new message instance.
     *
     * @param User $user User object
     *
     * @return void
     */
    public function __construct(User $user, $password)
    {
        $this->user = $user;
        $this->password = $password;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('email.content')
        ->with(['email'=>$this->user->email,
                'name'=>$this->user->name,
                'password'=>$this->password,
                'address'=>$this->user->userInfo->address,
                'phone'=>$this->user->userInfo->phone,
                ]);
    }
}
