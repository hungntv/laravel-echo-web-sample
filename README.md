# Laravel Echo Web Sample without Redis

The concept of this project: Laravel + RabbitMQ + laravel-echo-server-with-rabbitmq + Laravel-echo
- Laravel: https://laravel.com/
- RabbitMQ: open source message broker https://www.rabbitmq.com/
- Laravel-echo-server-with-rabbitmq: node.js project to make a websocker server.
- Laravel-echo: websocket client, use it for frontend https://github.com/laravel/echo

If I complete this project, we can use any message broker, connection queue instead of Redis only.

----------------------------------------

Thanks @zsasko

Now, I'm gonna remove Redis that will replaced by RabbitMQ

Time: Team Vitality vs Natus Vincere, now the score is 1:2. today s1mple is so crazy :)))

----------------------------------------

This project is sample application displaying how user can create and broadcast Laravel Echo message to clients (web and android applications). 

![](https://cdn-images-1.medium.com/max/800/1*m8hG2m8mmC3gXULQ_HZawA.gif)

It's a source code for the following article on the medium:

- https://medium.com/@zoransasko/receiving-laravel-echo-socket-io-messages-in-android-application-a2e1a3d83c5d

**As an initial setup of project, make sure that you have executed the following commands:**
```
npm install
composer install
php artisan key:generate
php artisan config:clear
php artisan serve
```
**And you need to start Laravel Echo Server by executing the following command (in separate console):**
```
laravel-echo-server start
```

After you have configured all dependencies, you can access messages web page from where you can send Echo messages:
- http://localhost:8000/messages

