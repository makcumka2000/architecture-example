<?php

namespace core\services;

/**
 * Class TransactionManager
 * @package core\services
 * Класс для работы с транзакциями - чтобы убрать всю повторяющуся работу в один класс
 */
class TransactionManager
{

    public function wrap(callable $function): void
    {
        $transaction = \Yii::$app->db->beginTransaction();
        try {
            $function();
            $transaction->commit();
        } catch (\Exception $e) {
            $transaction->rollBack();
            throw $e;
        }
    }
}