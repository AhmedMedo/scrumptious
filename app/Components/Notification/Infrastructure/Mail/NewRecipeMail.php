<?php

namespace App\Components\Notification\Infrastructure\Mail;

use App\Components\Auth\Data\Entity\UserEntity;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NewRecipeMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public UserEntity $user,
        public string $title,
        public string $body,
        public ?array $data = null
    ) {
    }

    public function build()
    {
        return $this->subject($this->title)
            ->view('emails.notifications.new-recipe')
            ->with([
                'userName' => $this->user->full_name ?? $this->user->first_name,
                'title' => $this->title,
                'body' => $this->body,
                'data' => $this->data,
            ]);
    }
}
