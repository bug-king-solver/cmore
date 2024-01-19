<?php

namespace App\Notifications;

use App\Models\Tenant\Data;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Queue\SerializesModels;

class DataValidatorNotification extends Notification implements ShouldQueue
{
    use Queueable;
    use SerializesModels;

    protected Data $data;
    protected User $user;


    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Data $data, User $user )
    {
        $this->data = $data;
        $this->user = $user;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return [];
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        $openUrl = route('tenant.data.show', ['data' => $this->data->id]);

        return [
            'userName' => $this->user->name,
            'message' => ':userName created new data log.',
            'action' => $openUrl,
        ];
    }

    /**
     * Only send the notification if the user is not assigning the question for himself
     */
    public function shouldSend($notifiable, $channel)
    {
        // Keep send to everyone, in this case who is doing the changes knows what other receives
        return true; //$this->user->id !== $notifiable->id;
    }
}
