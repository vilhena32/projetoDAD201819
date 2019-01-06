<?php

namespace App\Mail;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class GmailExample extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */

    public $user;
    public function __construct(User $user)
    {
        //
        $this->user = $user;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {   
      //  dd($this->user);

 
        $this->from('mail@example.com', 'Restaurant Management')
            ->subject('Reset your password')
            ->markdown('mails.example')
            ->with([
                'name' => $this->user->name,
                'token' => $this->user->remember_token,

            ]);

            return redirect('/');
      
    }
}
