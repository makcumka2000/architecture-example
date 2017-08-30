<?php
/**
 * Created by PhpStorm.
 * User: makcu
 * Date: 30.08.2017
 * Time: 20:49
 */

namespace app\core\repositories;

/**
 * Class NotFoundException
 * @package app\core\repositories
 * Исключение которое мы будем генерировать в наших репозитариях, в случае если не найдется сущность
 * Создали свое исключение - для того, чтобы можно было его отличить от других, и как нибудь по другому обрабатывать
 */
class NotFoundException extends \DomainException
{

}