<?php
/**
 * Created by PhpStorm.
 * User: makcu
 * Date: 30.08.2017
 * Time: 20:43
 */

namespace app\useCases;


use app\core\repositories\PostRepository;
use core\services\TransactionManager;

/**
 * Class PostManageService
 * @package app\useCases
 * Сервис по работе с сущностью Post
 * Требования к подобным сервисам(и репозитариям) - это отсутствие состояний, то есть этот обьект инициализируется с какими-то настройками, а после остается неизменным.
 * Тем самым мы можем использовать его как singletone
 * Тут могут быть такие методы как: createPost, updatePost, movePostToDraft и тд, на текущий момент мы ограничились одним методом - updatePost
 */
class PostManageService
{
    /**
     * @var PostRepository
     */
    private $posts;

    /**
     * @var TransactionManager
     */
    private $transactionManager;

    /**
     * PostManageService constructor.
     * @param PostRepository $posts
     * @param TransactionManager $transactionManager
     */
    public function __construct(PostRepository $posts, TransactionManager $transactionManager)
    {
        $this->posts = $posts;
        $this->transactionManager = $transactionManager;
    }


    /**
     * @param $id
     * @param $title string
     * @param $content string
     * Метод редактирования поста
     * В него передается его $id и данные которые нужно изменить
     */
    public function updatePost($id, $title, $content)
    {
        //Это метод либо вернет пост, либо сгенерирует исключение и работа метода оборвется. Тем самым мы избавились от проверки на null
        $post = $this->posts->get($id);
        //Работы по редактированию данных мы поместили в саму сущность, в ней же генерируеся событие о редактировании
        $post->update($title, $content);

        $this->transactionManager->wrap(function () use ($post) {
            //Делегируем сохранение сущностью в тот же репозиторий - который либо генерируем исключение, либо сохраняет
            //Также, мы обернули сохранение поста в транзакцию - через TransactionManager
            $this->posts->save($post);
        });


    }
}