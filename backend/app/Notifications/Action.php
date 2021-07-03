<?php

namespace App\Notifications;

use App\Models\Group;
use Illuminate\Bus\Queueable;
use NotificationChannels\Fcm\FcmChannel;
use Illuminate\Notifications\Notification;
use NotificationChannels\Fcm\FcmMessage;
use Illuminate\Database\Eloquent\Model;
use ReflectionClass;

class Action extends Notification
{
    use Queueable;

    protected $title = "";
    protected $message = "";
    protected $type = "";
    protected $action = 0;
    protected $model = "";
    protected $model_id = -1;
    protected $group_id = "";

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($title, $message, Model $model, int $type, Group $group)
    {
        $reflect = new ReflectionClass($model);

        $this->title = $title;
        $this->message = $message;
        $this->model = $reflect->getShortName();
        $this->model_id = strval($model->id);
        $this->group_id = strval($group->id);
        $this->type = strval($type);
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        //faire le check pour le user (notifiable) si les paramètres sont ok

        return ['database', FcmChannel::class];
    }

    public function toFcm($notifiable)
    {
        return FcmMessage::create()
            ->setData(['type' => "$this->type", 'model_id' => "$this->model_id", 'model' => $this->model, "group_id" => $this->group_id])
            ->setNotification(\NotificationChannels\Fcm\Resources\Notification::create()
                ->setTitle($this->title)
                ->setBody($this->message));
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            "title" => $this->title,
            "message" => $this->message,
            "type" => $this->type,
            "model" => $this->model,
            "model_id" => $this->model_id,
            "group_id" => $this->group_id,
        ];
    }
}
