Задание было весьма размыто, возможно что-то не так сделал, потому что не понял.
Создание книги и автора сделал разными подходами. (Для книг все делается в сервисе, для автора в контролере)

```
cd docker
docker-compose up -d
Подождать несколько секунд
docker-compose run php-fpm ./bin/console doctrine:schema:create
docker-compose run php-fpm ./bin/console doctrine:fixtures:load
```