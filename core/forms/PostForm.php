<?php
/**
 * Created by PhpStorm.
 * User: makcu
 * Date: 30.08.2017
 * Time: 20:37
 */

namespace app\core\forms;


use app\core\entities\Post\Post;

/**
 * Class PostForm
 * @package app\core\forms
 * Форма, необходимая для редактирования сущности Post,
 * Ее главная обязанность валидация входящих данных и передача их дальше в сервис
 */
class PostForm extends Model
{
    public $title;
    public $content;

    public function __construct(Post $post = null)
    {
        if ($post !== null) {
            $this->content = $post->getContent();
            $this->title = $post->getTitle();
        }
    }

    public function rules()
    {
        return [
            [['title', 'content', 'required']],
        ];
    }
}