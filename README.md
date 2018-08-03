# Laravel Blog - A simple blog rest api build using Laravel

- clone this repo
- cd into the directory and run composer install

## By default this uses a sqlite database:
- touch database/database.sqlite
- cp .env.bak .env 
- set the absolute path to your database.sqlite file
- That should be it.

Now just run php-unit: vendor/bin/phpunit --color and you should hopefully get green!

### Production notes
- If you are going to use the sqlite file on your server don't forget to recreate your database.sqlite file and also make sure the path in the .env file is correct.
- Make sure you make the ENTIRE database directory writable (sudo chmod -R 777 database)
