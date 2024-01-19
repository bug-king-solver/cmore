<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Spatie\Comments\Models\Comment;
use Spatie\Comments\Models\Concerns\Interfaces\CanComment;
use Spatie\Comments\Notifications\ApprovedCommentNotification as NotificationsApprovedCommentNotification;

class ApprovedCommentNotification extends NotificationsApprovedCommentNotification implements ShouldQueue
{
    use Queueable;

    public function __construct(public Comment $comment, public CanComment $commentator)
    {
    }

    public function via()
    {
        return 'database';
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable): array
    {
        $topLevelComment = $this->comment->topLevel();

        return [
            'commentableName' => $topLevelComment->commentable->commentableName(),
            'commentatorName' => $this->comment->commentatorProperties()->name,
            'action' => $topLevelComment->commentable->commentUrl(),
            'message' => 'New comment on ":commentableName" posted by :commentatorName.',
        ];
    }
}
