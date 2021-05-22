<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OrganisationCreateNotification extends Notification
{
    use Queueable;

    private $organisation;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($organisation)
    {
        $this->organisation = $organisation;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array
     */
    public function via(): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @return MailMessage
     */
    public function toMail(): MailMessage
    {
        return (new MailMessage)
            ->subject('Organisation created #' . $this->organisation->id . ' notification')
            ->level('info')
            ->greeting('Organisation: ' . $this->organisation->name)
            ->line('Hello, ' . $this->organisation->owner->name)
            ->line('Your organization ' . $this->organisation->name . ' has been successfully created. You have a 30-day trial subscription.');
    }

}
