# Laravel-Chat-System

This chat system has been developed using Laravel Livewire for the user-facing user interface and filament php for the admin dashboard

Steps to run project

## Backend

1. Git clone `git clone <project-url>`

2. Navigate to the project root and run composer install `composer install`

3. Copy .env.example to .env `cp .env.example .env`

4. Update .env and set your database credentials

5. Generate laravel Key `php artisan key:generate`

6. Run migrations and seeder `php artisan migrate --seed`


## Frontend

1. Set up project's front end dependencies `npm install`

2. Run `npm run dev` to compile assets


## RealTime Updates

1. Run this command to start the local web sockets server  `php artisan websockets:serve`

2. Navigate to `http://localhost:8000/laravel-websockets` ,set port as `6001`

3. Click the connect button on the interface. If successful, you should get a `Channels current state is connected`

4. Start the queue worker to process jobs `php artisan queue:work`

5. Navigate to `http://localhost:8000` and register an account and navigate to `/users` to send a message