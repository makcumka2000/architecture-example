<?php
/**
 * Created by PhpStorm.
 * User: makcu
 * Date: 30.08.2017
 * Time: 20:45
 */

namespace app\core\entities\Post\events;


use app\core\entities\Post\Post;

class PostUpdatedEvent
{
    /**
     * @var Post
     */
    public $post;

    /**
     * PostUpdatedEvent constructor.
     * @param Post $post
     */
    public function __construct(Post $post)
    {
        $this->post = $post;
    }


}