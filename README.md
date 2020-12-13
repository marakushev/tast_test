Задание было весьма размыто, возможно что-то не так сделал, потому что не понял.
Создание книги и автора сделал разными подходами. (Для книг все делается в сервисе, для автора в контролере)

```
cd docker
docker-compose up -d (Пропадёт 502 ошибка)
Дождаться создание и установки конейнеров
docker-compose run php-fpm ./bin/console doctrine:fixtures:load --append
(эту команду тоже можно было вставить в docker файл)
```

Примеры запросов:
```
curl --location --request GET 'http://127.0.0.1/api/en/book/23'
curl --location --request GET 'http://127.0.0.1/api/ru/book/23'

curl --location --request POST 'http://127.0.0.1/api/en/book/create' \
--form 'name="Test Book"' \
--form 'author="Author 1"'

curl --location --request POST 'http://127.0.0.1/api/author/create' \
--form 'name="Test Author"'

curl --location --request GET 'http://127.0.0.1/api/search/book/book 9'