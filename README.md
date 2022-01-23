<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400"></a></p>


## Real Time Chat

Live Chat is a simple application created with Laravel. The version used in this application is Jetstream. The visualization uses Vue3.

- Using the Jetstream package.
- Websocket Laravel.
- Tailwind CSS.
- Vuex.

This is an example application and uses Mysql, which for such applications is not recommended. Migrations are ready to use and can be served in your database. 

## How to use

Some settings need to be done in .env.example and the file be renamed to .env

it is necessary to generate an app key for the application to do this just run the command in the terminal: **php artisan key:generate**

configure database access

Now we need to install the dependencies to use the Mix with: **npm install**

Now we need to install the dependencies to use Composer with: **composer install**

Ok, if everything went well, just start the server with the commands below:

**php artisan serve** // Start Server Composer

**npm run watch** //to watch the Mix changes

**php artisan websocket:serve** //To start websocket

Start by registering users and then log in from the menu above.
