<?php
/**
 * Created by PhpStorm.
 * User: makcu
 * Date: 30.08.2017
 * Time: 20:37
 */

namespace app\core\entities\Post;


use app\core\entities\Post\events\PostUpdatedEvent;
use core\entities\EventTrait;

/**
 * Корневая сущность домена
 * Для каждой корневой сущности необходимо создать репозитарий, который будет сохранять ее в БД, а также искать ее там
 * Корневая сущность также генерируем события, которую могут обрабатываться(а могут и не обрабатываться) в EventDispatcher'е
 * Здесь также импортирован трейт EventTrait для реюза двух методов, по работе с событиями
 */
class Post
{
    use EventTrait;

    private $id;
    private $title;
    private $content;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @return mixed
     */
    public function getContent()
    {
        return $this->content;
    }



    /**
     * @param $title
     * @param $content
     * Метод, который обновляет поля нашей модели, генирует событие PostUpdatedEvent
     */
    public function update($title, $content)
    {
        $this->title = $title;
        $this->content = $content;
        $this->recordEvent(new PostUpdatedEvent($this));
    }
}