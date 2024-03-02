## Installation and Guide

- Clone the project
- Run ``composer install``
- Create DB and set credentials in ``.env``
- Run ``php artisan key:generate``, ``php artisan migrate``
- Run ``php artisan db:seed`` which will seed current Russian Central Bank rates into DB.rates table
- Run ``php artisan serve`` or some local http server
- Access API endpoint via ``YOUR_LOCAL_HOST/api/rates``
- Settings are based on ``date`` and ``currency`` URL GET parameters
- Run ``php artisan test`` to test /api/rates endpoint's features
