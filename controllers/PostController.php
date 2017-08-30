<?php
/**
 * Created by PhpStorm.
 * User: makcu
 * Date: 30.08.2017
 * Time: 20:36
 */

namespace app\controllers;


use app\core\entities\Post\events\PostUpdatedEvent;
use app\core\entities\Post\Post;
use app\core\forms\PostForm;
use app\core\listeners\PostUpdateListener;
use app\core\repositories\PostArRepository;
use app\core\repositories\PostRepository;
use app\useCases\PostManageService;
use core\dispatchers\SimpleEventDispatcher;
use core\services\TransactionManager;

class PostController
{
    /**
     * @var PostRepository
     */
    private $posts;

    /**
     * @var PostManageService
     */
    private $service;

    public function __construct()
    {
        /**
         * Инициализируем репозиторий по работе с сущностью Post
         * Мы передаем туда все необходимые данные.
         * Хорошим правилом является перенос этой инициализации в dependency injection container(здесь отсутсвует)
         * Мы конфигурируем его диспетчером событий, в котором привязываем к событиям, их обработчики.
         * Таким образом мы можем одной строчкой кода отключить какой нибудь обработчик, если он больше не нужен, либо написать новый и прописать сюда
         * Также в будущем мы можем реализовать AsyncEventDispatcher который вместо реального выполнения обработчика - будем складывать их в очередь
         * Повторюсь, что обычно всю инициализацию переносят в отдельный класс, и в контроллере пишется, что-то типа такого:
         * $this->posts = Yii::$container->get(PostRepository::class);
         */
        $this->posts = new PostArRepository(new SimpleEventDispatcher(Yii::$container, [
            PostUpdatedEvent::class => [
                PostUpdateListener::class
            ],
        ]));
        //Тут мы инициализируем сервис, для работы которого нужен репозиторий, который мы создали выше и менеджер транзаций по работе с БД
        $this->service = new PostManageService($this->posts, new TransactionManager());
    }

    public function actionUpdate($id)
    {
        //Вынесли поиск обьекта в отдельный метод для реюза(например в методе view)
        $post = $this->loadPost($id);

        //Создаем форму для редактирования и инициализируем ее данными сущности, которую мы получили ранее
        //Вся работа во View идет именно с этой формой, также эта форма отвественна за валидацию данных
        $form = new PostForm($post);
        //В случае отправки нам данных, мы проверяем их на валиданость методом validate формы
        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            //В случае если данные валидны - с помощью сервиса редактируем сущность поста
            try {
                $this->service->updatePost($post->getId(), $form->title, $form->title);
                //В случае если обновление прошло успешно - перенаправляем пользователя на action view
                return $this->redirect(['view', 'id' => $post->getId()]);
            } catch (\DomainException $e) {
                //В сервисе(или где-то в методах домена) могут возникнуть исключения, перехватываем их тут и отображаем пользователю через flash сообщение
                Yii::$app->errorHandler->logException($e);
                Yii::$app->session->setFlash('error', $e->getMessage());
            }
        }

        return $this->render('update', [
            'form' => $form,
        ]);
    }

    private function loadPost($id):Post
    {
        //Делегируем поиск поста репозитарию(который мы инициализировали в конструкторе)
        $post = $this->posts->findById($id);
        //В случае если модель не найдена - генерируем http exception
        if ($post === null) {
            throw new \HttpException('Page not found', 404);
        }
        return $post;
    }
}