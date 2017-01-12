# Larafolio
[![Build Status](https://travis-ci.org/zachleigh/larafolio.svg?branch=master)](https://travis-ci.org/zachleigh/larafolio)
[![Latest Stable Version](https://poser.pugx.org/zachleigh/larafolio/version.svg)](//packagist.org/packages/zachleigh/larafolio)
[![StyleCI](https://styleci.io/repos/74421920/shield?style=flat)](https://styleci.io/repos/74421920)
[![License](https://poser.pugx.org/zachleigh/larafolio/license.svg)](//packagist.org/packages/zachleigh/larafolio)  
##### Turn a Laravel app into a portfolio site in minutes. 

## This project is still under construction. Come back soon!

## Contents
  - [API](#api)
  - [Developing](#devolping)

## API

#### Larafolio\Models\Project.php

###### static allVisible($group = true, $order = true) 

###### static allHidden($group = true, $order = true)

###### static allGrouped($order = true)

###### static allOrdered()

###### static hasBlockNamed($blockName)

###### static hasImageNamed($imageName)

###### static hasLinkNamed($linkName)

###### blocks()

###### links()

###### images()

###### hasBlocks()

###### block($name)

###### blockText($name, $formatted = true)

###### getProjectBlock()

###### getProjectBlockText($formatted = true)

###### hasImages()

###### image($name)

###### imageUrl($name, $size = 'medium')

###### imageCaption($name)

###### getProjectImage()

###### getProjectImageUrl($size = 'small')

###### hasLinks()

###### link($name)

###### linkUrl($name)


#### Larafolio\Models\Image

###### thumbnail()

###### small()

###### medium()

###### full()

###### imageRoute($templateName)

###### fileName()


## Developing

#### Getting Started

###### Clone this repo:
```
git clone https://github.com/zachleigh/larafolio.git
```

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

###### Set up database connections
Currently, database credentials are in two places (yeah, this sucks...):
  - /vendor/laravel/laravel/.env.testing
  - codeception.yml   

Before submitting a pull request, please change codeception.yml back to its original values:
```yml
dsn: 'mysql:host=127.0.0.1;dbname=larafolio'
user: 'root'
password: 'password'
```

###### Artisan
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
Login logic is contained in the /login route in the underlying Laravel instance. To login and access the project, simply hit the /login route. A user will be logged in and you will be redirected to /manager. Hitting the login route also reruns the migrations and seeds it.

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
Or simply hit the /login route.

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
