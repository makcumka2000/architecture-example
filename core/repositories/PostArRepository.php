<?php
/**
 * Created by PhpStorm.
 * User: makcu
 * Date: 30.08.2017
 * Time: 20:48
 */

namespace app\core\repositories;


use app\core\entities\Post\Post;
use core\dispatchers\EventDispatcher;

/**
 * Class PostArRepository
 * @package app\core\repositories
 * Реализация PostRepository
 * Тут также дополнительно добавлен EventDispatcher для обработки событий, которые генерируются в сущности
 * События обрабатываются только в методе save, так как в остальных методах генерация событий пока не предвидется
 */
class PostArRepository implements PostRepository
{
    /**
     * @var EventDispatcher
     */
    private $eventDispatcher;

    /**
     * PostArRepository constructor.
     * @param EventDispatcher $eventDispatcher
     */
    public function __construct(EventDispatcher $eventDispatcher)
    {
        $this->eventDispatcher = $eventDispatcher;
    }


    public function findById($id)
    {
        return Post::findOne($id);
    }

    public function get($id):Post
    {
        /**@var $model Post*/
        $model = Post::findOne($id);
        if ($model === null) {
            throw new NotFoundException('Can not find Post');
        }

        $this->eventDispatcher->dispatchAll($model->releaseEvents());

        return $model;
    }

    public function save(Post $post)
    {
        if(!$post->save()){
            throw new \RuntimeException('Saving error');
        }
    }

}