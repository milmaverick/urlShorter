<?php

class DB {

    private static $instance;  // экземпляр объекта

    private $pdo = false;

    /* Защищаем от создания через new DB */

    private function __construct() {

    }

    /* Защищаем от создания через клонирование */

    private function __clone() {

    }

    /**
     * Возвращает единственный экземпляр класса
     * @return DB
     */
    public static function getInstance() {
        if (empty(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Подключаемся к БД
     */
    public function connect() {
        try {
            $this->pdo = new PDO('mysql:host=localhost;dbname=short_urls', 'root', '');
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Получить ссылку на PDO
     * @return boolean
     */
    public function get_pdo() {
        if ($this->pdo instanceof PDO) {
            return $this->pdo;
        }
        return false;
    }

    /**
     * Закрываем соединение с БД
     */
    public function close() {
        $this->pdo = null;
    }

}

try {
    DB::getInstance()->connect();

} catch (Exception $e) {
    echo $e->getMessage();
    exit;
}
