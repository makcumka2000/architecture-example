<?php
/**
 * Created by PhpStorm.
 * User: makcu
 * Date: 30.08.2017
 * Time: 20:38
 */

namespace app\core\repositories;


use app\core\entities\Post\Post;

/**
 * Interface PostRepository
 * @package app\core\repositories
 * Интерфейс репозитория по работе с сущностью Post

 */
interface PostRepository
{
    /**
     * @param $id
     * @return Post|null
     * Просто ищет сущность в БД, в случае отсутствия возвращает NUll
     */
    public function findById($id);

    /**
     * @param $id
     * @return Post
     * @throws NotFoundException
     * Ищет сущность в БД, в случае отсутствия - кидает NotFoundException
     */
    public function get($id):Post;

    /**
     * @param Post $post
     * @return mixed
     * @throws \RuntimeException
     * Сохраняет сущность в БД, в случае ошибки - кидает \RuntimeException
     */
    public function save(Post $post);
}