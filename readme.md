## Installation

Clone the repo:

```sh
git clone https://github.com/pkboom/flighthub.git
cd flighthub
```

Install PHP dependencies:

```sh
composer install
```

Install JavaScript dependencies:

```sh
npm install
```

Run JavaScript build:

```sh
# To run a development build
npm run dev
```

Setup your environment variables:

```sh
cp .env.example .env
php artisan key:generate
```

Create a Mysql database:

```sh
create database flighthub
```

Update database configuration in `.env` file:

```sh
DB_CONNECTION=mysql
DB_DATABASE=flighthub
DB_USERNAME=root
DB_PASSWORD=<your-password>
```

Migrate and seed your database:

```sh
php artisan migrate --seed
```

Routes
| Method | URI | Action |
|---|---|---|
| GET | search/flights | App\Http\Controllers\SearchFlightsController |
| GET | trips | App\Http\Controllers\TripController@index |
| POST | trips | App\Http\Controllers\TripController@store |
| GET | trips/create | App\Http\Controllers\TripController@create |
| DELETE | trips/{trip} | App\Http\Controllers\TripController@destroy |
| GET | trips/{trip}/edit | App\Http\Controllers\TripController@edit |

Go to `/trips` to start booking.
