## Console Command

```bash
php artisan rates:get {date?}
```
{date?} is optional

d/m/Y -- CBR api format

## API Request route 

/api/rates

params:
```bash
start_date=2024-03-11&end_date=2024-03-29
```
date format is Y-m-d

if end date < start date, you will get error

## Usage

```
Clone from repository

run: composer install

run: cp .env.example to .env

add CBR endpoint URL to  .env
CBR_ENDPOINT=http://www.cbr.ru/scripts/XML_daily.asp

add DB connection to .env ,insert tables
run: php artisan:migrate

run: php artisan serve or php -S localhost:8000 -t public
```
Fetch data from API CBR is in the Console/Commands
Added Schedule to the Kernel.php 1 time per day to fetch new rates , 


```
run: php artisan schedule:run
```

Main logic is in the Services

CRUD functional is in the Repository