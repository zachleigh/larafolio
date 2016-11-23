# Larafolio  
##### Turn a Laravel app into a portfolio site in minutes. 

This project is still under construction. Come back soon!

TODO:
  * Make command to make .env file with key
  * Make command to publish and run migrations
  * Make command to publish resources 


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

#### Workflow
There is an artisan file in the Larafolio directory that points to the laravel instance in vendor. This gives you access to all of the artisan commands you would normally use.

###### Publish the resources from the service provider:
```
php artisan vendor:publish --provider="Larafolio\LarafolioServiceProvider" --force
```

###### Serve the project:
```
php artisan serve
```

###### Build resources:
```
gulp
```
Then publish the resources:
````
php artisan vendor:publish --provider="Larafolio\LarafolioServiceProvider" --force
```

