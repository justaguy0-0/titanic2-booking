FROM php:8.2-fpm-alpine

# Устанавливаем необходимые расширения PHP и cron
RUN docker-php-ext-install pdo pdo_mysql

# Устанавливаем supercronic (улучшенный cron для Docker)
ENV SUPERCRONIC_URL=https://github.com/aptible/supercronic/releases/download/v0.2.29/supercronic-linux-amd64 \
    SUPERCRONIC=supercronic-linux-amd64 \
    SUPERCRONIC_SHA1SUM=cd48d45c4b10f3f0bfdd3a57d054cd05ac96812b

RUN apk add --no-cache curl \
    && curl -fsSLO "$SUPERCRONIC_URL" \
    && echo "${SUPERCRONIC_SHA1SUM}  ${SUPERCRONIC}" | sha1sum -c - \
    && chmod +x "$SUPERCRONIC" \
    && mv "$SUPERCRONIC" "/usr/local/bin/${SUPERCRONIC}" \
    && ln -s "/usr/local/bin/${SUPERCRONIC}" /usr/local/bin/supercronic

WORKDIR /var/www/laravel

# Копируем crontab файл
COPY crontab /etc/crontabs/crontab

# Запускаем supercronic
CMD ["supercronic", "/etc/crontabs/crontab"]