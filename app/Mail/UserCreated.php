<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class UserCreated extends Mailable
{
    use Queueable, SerializesModels;

    public $user;




    /**
     * Create a new message instance.
     * la noticia es que laravel injecta directamentre los atrtibutos del mailable class en la vista que tenemos implemnetada en build
     * @return void
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
      // 119
      // return $this->text('emails.welcome')->subject('Por favor confirma tu correo electronico'); // para inviar solo texto plano
      //return $this->view('view.name');

      return $this->markdown('emails.welcome')->subject('Por favor confirma tu correo electronico');
    }



}
