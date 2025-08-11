<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AdminRegisteredMail extends Mailable
{
    use Queueable, SerializesModels;

    public $email;
    public $password;
    public $nombre;

    public function __construct($email, $password, $nombre )
    {
        $this->email = $email;
        $this->password = $password;
        $this->nombre = $nombre;
    }

    public function build()
    {
        return $this->subject('Bienvenid@ al Sistema de Comedor')
            ->view('emails.admin_registered')->with([
                'nombre' => $this->nombre,
            ]);
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Registrad@ en el sistema del comedor',
        );
    }

    /**
     * Get the message content definition.
     */

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
