<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TwoFACodeMail extends Mailable
{
    use Queueable, SerializesModels;

    public $code; // Definir la propiedad $code

    public function __construct($code)
    {
        $this->code = $code;
    }
    
    public function build()
    {
        return $this->view('emails.two_fa_code')
                    ->with([
                        'code' => $this->code, // Ahora puedes pasar 'code' a la vista sin problemas
                    ]);
    }
}
