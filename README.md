## Installation guide


- Run "composer install".
- Copy .env.example content to .env file.
- Create DB called "tower_test"
- Run "php artisan migrate" command

- Run "php artisan key:generate" command
- Run "php artisan serve" command
- Go to localhost:8000

## URLs for web

- for web browsing we have only 2 links that we needs to (report pages )
- 1.  http://localhost:8000/tower/temperature
- 2.  http://localhost:8000/tower/malfunctioning

## URL for API (for example via Postman)

- localhost:8000/api/sensors
- I will atach the postman collection(the request body already created )