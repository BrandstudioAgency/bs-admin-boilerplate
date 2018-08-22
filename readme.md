# Brandstudio Admin Boilerplate

Boilerplate for Brandstudio Admin Panel, powered by Laravel 5.6

### System Requirements

To be able to run this boilerplate you have to meet the following requirements:
- PHP > 7.0
- PHP Extensions: PDO, cURL, Mbstring, Tokenizer, Mcrypt, XML, GD
- Composer > 1.0.0

### Installation

1. Install Composer using detailed installation instructions [here](https://getcomposer.org/doc/00-intro.md#installation-linux-unix-osx)

2. Clone repository
```
$ git clone https://github.com/BrandstudioAgency/bs-admin-boilerplate.git your-project-name
```
3. Change into the working directory
```
$ cd your-project-name
```
4. Copy `.env.example` to `.env` and modify according to your environment
```
//for windows based system
$ copy .env.example .env

//for linux based system
$ cp .env.example .env 
```

5. Install composer dependencies

```
$ composer install --prefer-dist
```

6. An application key can be generated with the command
```
$ php artisan key:generate
```

7. Run these commands to create the tables within the defined database and populate seed data
```
$ php artisan migrate --seed
```

### Run

To start the PHP built-in server
```
$ php artisan serve --port=8080
or
$ php -S localhost:8080 -t public/
```

Now you can browse the site at [http://localhost:8080](http://localhost:8080)  🙌

### Pre-installed packages
- Backpack for Laravel
- barryvdh/laravel-cors
- barryvdh/laravel-elfinder
- artesaos/seotools
- jenssegers/date
- cviebrock/eloquent-sluggable


License
----

MIT


**Free Software, Hell Yeah!**