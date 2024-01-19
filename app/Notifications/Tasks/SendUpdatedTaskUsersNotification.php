<?php

namespace App\Notifications\Tasks;

use App\Models\Tenant\Task;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class SendUpdatedTaskUsersNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * The target after update.
     */
    protected Task $task;

    public array $changes;

    /**
     * The user who made the change.
     */
    protected Authenticatable $user;

    /**
     * The owner before update the target
     */
    protected $oldOwnerId = null;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Task $task, array $changes, Authenticatable $user)
    {
        $this->task = $task;
        $this->changes = $changes;
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
        $openUrl = route('tenant.users.tasks.show', ['task' => $this->task->id]);

        return [
            'userName' => $this->user->name,
            'task' => $this->task->name,
            'message' => ':userName updated the task ":task".',
            'action' => $openUrl,
        ];
    }

    /**
     * Determine if the notification should be sent.
     *
     * @param  mixed  $notifiable
     * @param  string  $channel
     * @return bool
     */
    public function shouldSend($notifiable, $channel)
    {
        return true;

        return count($this->changes) > 1 || array_key_exists('users', $this->changes);
    }
}
