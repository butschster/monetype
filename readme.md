## MONETYPE

Сайт: [monetype.ru](http://monetype.ru/)

Сервис, который позволяет читателям поощрять, а авторам зарабатывать на качественном контенте.

### Работа с media библиотеками
Для установки библиотек используется bower и bower-installer (`npm install bower`, `npm install bower-installer`).

Список всех библиотек, которые используются системой находится в файле `bower.json`.

Для запуска процесса установки необходимо выполнить комманду `bower-installer`, в процессе которой скачаются все библиотек, исходники которых будут
помещены в папку `bower_components`, а рабочие версии в `public\libs`.



### Для обновления

Выполнить комманды:  

1. bower i
2. npm install
3. composer update
4. php artisan db:clear
5. php artisan modules:migrate --seed


### Полезная информация

**Администратор:**  
admin@site.com
password
