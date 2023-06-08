
# Robin Poiron API (Chatbot)

Laravel Repository of Rob's API


## Run Locally

Clone the project

```bash
  git clone https://github.com/Robitch/api-flutter.git
```

Go to the project directory

```bash
  cd api-flutter
```

Install dependencies

```bash
  composer install
```

Copy the example env file and make the required configuration changes in the .env file

    cp .env.example .env

Generate a new application key

    php artisan key:generate

Generate a new JWT authentication secret key

    php artisan jwt:generate

Run the database migrations (**Set the database connection in .env before migrating**)

    php artisan migrate

Start the local development server

    php artisan serve --host=0.0.0.0 --port=8000

You can now access the server at http://192.168.**.**:8000

**TL;DR command list**

    git clone https://github.com/Robitch/api-flutter.git
    cd api-flutter
    composer install
    cp .env.example .env
    php artisan key:generate
    php artisan jwt:generate
# Testing API

Run the laravel development server

    php artisan serve --host=0.0.0.0 --port=8000

The api can now be accessed at

    http://192.168.**.**:8000/api

