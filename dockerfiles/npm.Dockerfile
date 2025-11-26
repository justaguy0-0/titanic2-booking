FROM node:stable-alpine

WORKDIR /var/www/laravel

RUN npm run build

EXPOSE 1234

ENTRYPOINT ["npm", "run"]