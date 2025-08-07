<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ConfirmarNuevaContrasena extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $url;
    public $nombre;

    /**
     * Create a new message instance.
     *
     * @param mixed $user
     * @param string $url
     * @param string $nombre
     */
    public function __construct($user, $url, $nombre)
    {
        $this->user = $user;
        $this->url = $url;
        $this->nombre = $nombre;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Confirma el cambio de contraseña')
                    ->view('emails.admin-confirmacion')
                    ->with([
                        'nombre' => $this->nombre,
                        'url' => $this->url,
                        'user' => $this->user,
                    ]);
    }

    /**
     * Get the message envelope.
     *
     * @return \Illuminate\Mail\Mailables\Envelope
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Confirmar Nueva Contraseña',
        );
    }
}
