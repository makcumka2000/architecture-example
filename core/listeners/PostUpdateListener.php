<?php
/**
 * Created by PhpStorm.
 * User: makcu
 * Date: 30.08.2017
 * Time: 20:52
 */

namespace app\core\listeners;


use app\core\entities\Post\events\PostUpdatedEvent;

class PostUpdateListener
{
    private $mailer;

    /**
     * PostUpdateListener constructor.
     * @param $mailer
     */
    public function __construct($mailer)
    {
        $this->mailer = $mailer;
    }


    public function handle(PostUpdatedEvent $event)
    {
        $post = $event->post;

        $this->mailer->send('test@example.com', "Post with ID {$post->getId()} has been updated");
    }
}