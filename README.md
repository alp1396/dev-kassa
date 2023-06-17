# Dev-kassa

## Тестовое задание

### Вариант запуска с Docker
Для запуска необходим Docker версии не ниже 4.xx, установленный docker-compose

`docker-compose up`

Скрипт сам установит зависимости Composer и запустит программу

### Вариант без Docker
Для запуска необходим PHP версии не ниже 7.2x

1. Установить зависимости Composer
`cd app && php composer.phar install`

2. Запуск программы
`php index.php`
