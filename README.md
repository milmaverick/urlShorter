Задача №2 "Сокращение ссылок"
Для того, чтобы запустить и проверить API:
1)Скопируйте содержимое папки "02" и вставьте в корневую директорию вашего сайта.
2)Измените реквизиты для вашей базы данных. Настройки БД находятся в App/conf/Db.php (  public function connect() )
3)Обратитесь по адресу : http://{ДОМЕН САЙТА НА КОТОРОМ API}/index/getShort/?fulURl={ПОЛНЫЙ URL, который нужно сократить}
3.5) URL будет проверен на существование
4)Полученное значение и будет короткой ссылкой.
5)Воспользуйтесь короткой ссылкой и перейдите на исходный сайт.
Также имеется Web-interface. Протестировать его можно http://{ДОМЕН САЙТА НА КОТОРОМ API}

Конфиги БД:
-- База данных: `short_urls`
CREATE DATABASE `short_urls` CHARACTER SET utf8 COLLATE utf8_general_ci;
-- Структура таблицы `short_urls`;
CREATE TABLE `short_urls` (
  `id` int(11) NOT NULL,
  `long_url` varchar(255) NOT NULL,
  `short_code` varchar(255) NOT NULL,
  `hits` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
--

Тесты :
1. https://loadimpact.com/ / loadimpact.jpg . Выложил сайт на хостинг и протестировал его
2. ApacheBenchmark / ab.jpg


!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!! ВОЗМОЖНЫЕ ОШИБКИ
 ЕСЛИ В КОНСОЛЬ БРАУЗЕРА ВЫХОДИТ ОШИБКА НЕВЕРНЫЙ URL. ПРОВЕРЬТЕ КОРРЕКТНОСТЬ ИМЕНИ ФАЙЛА .htaccess (МОЖЕТ БЫТЬ _htaccess)
