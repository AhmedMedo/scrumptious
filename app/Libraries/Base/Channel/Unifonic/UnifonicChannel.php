<?php

namespace App\Libraries\Base\Channel\Unifonic;

use Bugsnag\BugsnagLaravel\Facades\Bugsnag;
use Exception;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Notifications\Events\NotificationFailed;
use Illuminate\Notifications\Notification;
use Multicaret\Unifonic\UnifonicClient;

class UnifonicChannel
{
    private UnifonicClient $client;

    private Dispatcher $dispatcher;

    public function __construct(Dispatcher $dispatcher)
    {
        $this->dispatcher = $dispatcher;
        $this->client = new UnifonicClient(config('unifonic.app_id'));
    }

    /**
     * @param mixed $notifiable
     * @param Notification $notification
     *
     * @return bool
     */
    public function send($notifiable, Notification $notification): bool
    {
        try {
            $to = (int) $this->getTo($notifiable);
            $message = $notification->toUnifonic($notifiable);

            return $this->sendSmsMessage($message, $to);
        } catch (Exception $exception) {
            Bugsnag::notifyException($exception);

            $event = new NotificationFailed($notifiable, $notification, 'unifonic', [
                'message'   => $exception->getMessage(),
                'exception' => $exception,
            ]);

            $this->dispatcher->dispatch($event);

            return false;
        }
    }

    /**
     * @param string $to
     * @param string $message
     *
     * @return object|bool
     */
    public static function sendMessageToNumber(string $to, string $message)
    {
        try {
            $client = new UnifonicClient(config('unifonic.app_id'));
            $result = $client->send($to, $message, config('unifonic.sender_id'));

            if ($result->success === "false") {
                throw CouldNotSendNotificationException::errorCodeReceived(
                    $result->errorCode,
                    $result->message,
                    $to
                );
            }

            return $result;
        } catch (Exception $exception) {
            Bugsnag::notifyException($exception);

            return false;
        }
    }

    /**
     * Get the address to send a notification to.
     *
     * @param mixed $notifiable
     *
     * @return mixed
     * @throws CouldNotSendNotificationException
     *
     */
    private function getTo($notifiable)
    {
        if ($notifiable->routeNotificationFor('unifonic')) {
            return $notifiable->routeNotificationFor('unifonic');
        }
        if ($notifiable->routeNotificationFor('twilio')) {
            return $notifiable->routeNotificationFor('twilio');
        }
        if ($notifiable->phone) {
            return $notifiable->phone;
        }

        if (isset($notifiable->phone_number)) {
            return $notifiable->phone_number;
        }

        throw CouldNotSendNotificationException::invalidReceiver();
    }

    /**
     * Sending sms message.
     *
     * @param string $message
     * @param int $to
     *
     * @return bool
     * @throws CouldNotSendNotificationException
     *
     */
    private function sendSmsMessage(string $message, int $to): bool
    {
        $result = $this->client->send($to, $message, config('unifonic.sender_id'));

        if ($result->success === "false") {
            throw CouldNotSendNotificationException::errorCodeReceived(
                $result->errorCode,
                $result->message,
                $to
            );
        }

        return true;
    }
}
