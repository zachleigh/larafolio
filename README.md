# Larafolio  
##### Turn a Laravel app into a portfolio site in minutes. 

This project is still under construction. Come back soon!

## Developing

#### Getting Started

###### Install php dependencies:
```
composer install
```

###### Install javascript dependencies:
```
yarn
```
Or, if you like pain and suffering:
```
npm install
```

###### Create a .env file
You need to go into vendor/laravel/laravel and create a .env file. Enter in your database info. Hopefully this process will be made easier in the future.   

There is an artisan file in the Larafolio directory that points to the laravel instance in vendor. This gives you access to all of the artisan commands you would normally use.

###### Publish the resources from the service provider:
```
php artisan vendor:publish --provider="Larafolio\LarafolioServiceProvider" --force
```

###### Run migrations
```
php artisan migrate
```

#### Workflow

###### Serve the project:
```
php artisan serve
```

###### Login and access project
Login logic is contained in the /login route in the underlying Laravel instance. To login and access the project, simply hit the /login route. A user will be logged in and you will be redirected to /manager.

###### Build resources:
```
gulp
```
CSS and JS will be built and moved into the vendor laravel instance automatically.    

###### Watch for changes and build automatically:
````
gulp watch
```

###### Create test data
```
php artisan migrate:refresh --seed
```

#### Testing
This project contains both phpunit tests and Codeception tests.    

Run all tests:
```
composer test
```

Run phpunit tests:
```
phpunit
```

Run Codeception tests:
```
codecept run acceptance
```
