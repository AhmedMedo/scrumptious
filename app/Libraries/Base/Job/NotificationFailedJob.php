<?php

namespace App\Libraries\Base\Job;

use Illuminate\Notifications\Messages\SlackMessage;
use Illuminate\Notifications\Notification;

class NotificationFailedJob extends Notification
{
    private $event;

    /**
     * Create a new notification instance.
     *
     * @param mixed $event
     */
    public function __construct($event)
    {
        $this->event = $event;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param mixed $notifiable
     *
     * @return string[]
     */
    public function via($notifiable): array
    {
        return ['slack'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param mixed $notifiable
     *
     * @return SlackMessage
     */
    public function toSlack($notifiable): SlackMessage
    {
        $event = $this->event;

        return (new SlackMessage())
            ->from('Telgani-backend')
            ->to(config('slack_webhook.SLACK_CHANNEL'))
            ->error()
            ->content('Queued job failed: ' . $this->event['job'])
            ->attachment(function ($attachment) use ($event): void {
                $attachment->title($event['exception']['message'])
                    ->fields([
                        'Connection Name' => $event['connectionName'],
                        'File'            => $event['exception']['file'],
                        'Line'            => $event['exception']['line'],
                        'Server'          => env('APP_ENV'),
                        'Queue'           => $event['queue'],
                        'Job'             => $event['job'],
                        'Id'              => array_key_exists('id', $event) ? $event['id'] : '',
                    ]);
            });
    }
}
