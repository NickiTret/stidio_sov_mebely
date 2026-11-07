<?php

namespace App\Mail;

use App\Models\Message;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;

/** Письмо о новой заявке с сайта. «Ответить» в почте — сразу клиенту (Reply-To). */
class NewLeadMail extends Mailable
{
    public function __construct(public Message $lead, public bool $isTest = false)
    {
    }

    public function envelope(): Envelope
    {
        $replyTo = filter_var($this->lead->mail, FILTER_VALIDATE_EMAIL) && !$this->isTest
            ? [new Address($this->lead->mail, $this->lead->name)]
            : [];

        return new Envelope(
            subject: ($this->isTest ? '[Тест] ' : '') . 'Новая заявка с сайта kbr-mebel.ru — ' . $this->lead->name,
            replyTo: $replyTo,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.new-lead',
            text: 'emails.new-lead-text',
        );
    }
}
