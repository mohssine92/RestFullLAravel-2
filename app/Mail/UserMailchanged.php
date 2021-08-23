<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class UserMailchanged extends Mailable
{
    use Queueable, SerializesModels;


    // atrributo publico contendra toda informacion del user , sera injectado directamentre en la vista que implementa build() , nb: no sera afectado los campos de hiden en el modelo
    public $user;



    /**
     * Create a new message instance.
     *
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
        // return $this->text('emails.confirm')->subject('Por favor confirma tu nuevo correo');
        return $this->markdown('emails.confirm')->subject('Por favor confirma tu nuevo correo');
    }
}

/* la posiblidad de que un user quizo cambiar su email de la cuenta con la que se registro , la logica implementada si email de la cuenta se cambia la cuenta vuelve a ser no verificada por ello
   requiere este email de verificacion
   o en caso se creo una cuenta con email mal , existe la posiblidad de cambiarlo , con email corercto

*/
