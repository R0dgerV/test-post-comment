# Тестовое задание для "Трубочиста"
выполнил Буняев Андрей


Настройка для проверки.
--------

Скачиваем на локаль репозитарий, заходим в папку репозитария и выполняем
```
php composer.phar install
```

создаем базу данных с именем ``commenttest``
```
echo "create database commenttest" | mysql -p -u root
```
создаем файл ``www/protected/config/local.php`` для доступа к бД (нужно вписать пользователя который имет доступ к базе с именем ``commenttest``) и его пароль
приводим к такому виду
```
<?php return [
    'components' => [
        'db' => [
            'connectionString' => 'mysql:host=localhost;dbname=commenttest',
            'username' => 'YOUNAME',
            'password' => 'YOUPASSWORD',
        ]
    ],
];

```

После нужно выполнить все миграции
```
cd www/protected/
php yiic migrate --interactive=0
```

Можно запускать локальный сервер в папке ``www``
```
cd www
php -S localhost:8000
```

 Сайт доступен по адресе 127.0.0.1:8000
 
 
 