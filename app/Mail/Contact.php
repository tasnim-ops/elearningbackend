<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class Contact extends Mailable
{
    use Queueable, SerializesModels;

    public $name;
    public $email;
    public $messageText;

    /**
     * Create a new message instance.
     *
     * @param string $name
     * @param string $email
     * @param string $messageText
     */
    public function __construct($name, $email, $messageText)
    {
        $this->name = $name;
        $this->email = $email;
        $this->messageText = $messageText;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this
            ->from(config('mail.from.address'), config('mail.from.name'))
            ->subject('New Contact Form Submission')
            ->view('emails.contact');
    }
}
