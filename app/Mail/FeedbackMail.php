<?php

namespace App\Mail;

use App\Models\Setting;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class FeedbackMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    /**
     * @var array
     */
    protected $details;

    /**
     * FeedbackMail constructor.
     * @param array $details
     */
    public function __construct(array $details)
    {
        $this->details = $details;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build(): self
    {
        return $this->from(config('contacts.feedback_email'))
            ->to(Setting::query()->supportEmail()->first()->getValue())
            ->subject(trans('email.feedback.subject'))
            ->view('mails.feedback')
            ->with('details', $this->details);
    }
}
