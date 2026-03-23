<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;

class ContactUsMail extends Mailable
{
    public $contact;
    public $type;

    public function __construct($contact, $type = 'customer')
    {
        $this->contact = $contact;
        $this->type = $type;
    }

    public function build()
    {
        $subject = $this->type == 'admin'
            ? 'New Contact Us Submission'
            : 'Thank You for Contacting Us';

        return $this->subject($subject)
            ->view('email-templates.contact_us');
    }
}