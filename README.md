Titanic2 Booking

Проект на Laravel + Docker.
Laravel-код находится в папке src/.
Docker-конфиги — в корне (docker-compose.yaml, nginx/, dockerfiles/).

Быстрый старт

Клонировать репозиторий:

git clone git@github.com:YOUR_GITHUB_USERNAME/titanic2-booking.git
cd titanic2-booking

Создать .env на основе примера:

cp src/.env.example src/.env

Запустить контейнеры:

docker compose up -d --build

Установить зависимости и сгенерировать ключ приложения:

docker compose exec app composer install
docker compose exec app php artisan key:generate

Запустить миграции (если используется база данных):

docker compose exec app php artisan migrate --seed
Структура проекта
titanic2-booking/
├── dockerfiles/        # Dockerfile'ы для разных сервисов
├── end/                # Дополнительные файлы или сервисы
├── nginx/              # Конфигурации nginx
├── src/                # Laravel-приложение
│    ├── artisan
│    ├── composer.json
│    └── ...
├── docker-compose.yaml # Основной файл docker-compose
├── .gitignore
├── .dockerignore
└── README.md
Примечания

Все реальные пароли и ключи хранятся в src/.env (он добавлен в .gitignore и в git не попадает).

В репозитории лежит только src/.env.example — шаблон, чтобы другие могли скопировать и заполнить своими данными.

Для локальной разработки можно использовать docker-compose.override.yml (он в .gitignore).