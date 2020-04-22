# Getting Started Guide
## To deploy in server
You can checkout the [digital ocean tutorial](https://www.digitalocean.com/community/tutorials/how-to-deploy-a-laravel-application-with-nginx-on-ubuntu-16-04) to deploy this laravel Application on server.

This Application uses Laravel Passport for API authentication support for mobile application. So, additionally run:  
`php artisan passport:install`

Generate application key

`php artisan key:generate`

## To set up locally

Assuming you have laravel and Composer installed, Clone this repository

`git clone https://github.com/sc-ashik/epark-server.git`

Install dependencies

`composer install`

Add your database configuration at .env file of this project


```
DB_HOST=127.0.0.1
DB_DATABASE=laravel
DB_USERNAME=laraveluser
DB_PASSWORD=password
```

Migrate the databases:

`php artisan migrate`

Generate application key

`php artisan key:generate`

Install Laravel Passport:

`php artisan passport:install`

Now run `php artisan serve`, then server will be available at `localhost:8000`

# Testing Setup

Following end points are available now for testing purpose.
## 1. Fee Category Table

To create fee category:
```
curl --location --request POST 'http://128.199.156.18/api/v1/feecategory' \
--header 'Content-Type: application/json' \
--data-raw '{
    "category_name": "normal",
    "fee": 2.56
}'
```
View fee category: 
```
curl --location --request GET 'http://128.199.156.18/api/v1/feecategory/{fee_category_id}'
```

List all fee category:
```
curl --location --request GET 'http://128.199.156.18/api/v1/feecategory'
```

Update fee category:
```
curl --location --request PUT 'http://128.199.156.18/api/v1/feecategory/{fee_category_id}' \
--header 'Content-Type: application/json' \
--data-raw '{
     "fee":3.5
}'
```
Delete fee category:
```
curl --location --request DELETE 'http://128.199.156.18/api/v1/feecategory/{fee_category_id}'
```

## 2. Parking table
To create virtual parking:
```
curl --location --request POST 'http://128.199.156.18/api/v1/parking' \
--header 'Content-Type: application/json' \
--data-raw '{
     "short_area_name":"GOM",
     "zip_code":"53100",
     "latitude":"1.25",
     "longitude":"1.826",
     "fee_category":1
}'
```
View parking: 
```
curl --location --request GET 'http://128.199.156.18/api/v1/parking/{parking_id}'
```

List all parkings:
```
curl --location --request GET 'http://128.199.156.18/api/v1/parking'
```

Update parking:
```
curl --location --request PUT 'http://128.199.156.18/api/v1/parking/{parking_id}' \
--header 'Content-Type: application/json' \
--data-raw '{
     "short_area_name":"GOB"
}'
```
Delete parking:
```
curl --location --request DELETE 'http://128.199.156.18/api/v1/parking/{parking_id}'
```
**Lock** a parking:
```
curl --location --request GET 'http://128.199.156.18/api/v1/lock/{short_area_name}{zip_code}{parking_id}'
```
```
curl --location --request GET 'http://128.199.156.18/api/v1/lock/GOM531004'
```
This creates entry in Transaction table


# API Documentation

Current API gateway available at  http://128.199.156.18/api/v1
## 1. User Related

### Registration 

Request
```curl
curl --location --request POST 'http://128.199.156.18/api/v1/register' \
--header 'Content-Type: application/json' \
--data-raw '{
    "name": "John Doe",
    "email": "john.doe@gmail.com",
    "password": "pass#12"
}'
```
Response
```json
{
    "success": true,
    "token": {
        "token": "TOKEN"
    },
    "user": {
        "name": "John Doe",
        "email": "john.doe@gmail.com",
        "updated_at": "2020-04-22T11:35:30.000000Z",
        "created_at": "2020-04-22T11:35:30.000000Z",
        "id": 2
    }
}
```
if user already exist
```
{
    "success": false,
    "message": {
        "email": [
            "The email has already been taken."
        ]
    }
}
```

## Login
Request
```curl
curl --location --request POST 'http://128.199.156.18/api/v1/login' \
--header 'Content-Type: application/json' \
--data-raw '{
    "email": "john.doe@gmail.com",
    "password": "pass#12"
}'

```
Response on succes:

```json
{
    "success": true,
    "token": {
        "token": "TOKEN"
    },
    "user": {
        "id": 2,
        "name": "John Doe",
        "email": "john.doe@gmail.com",
        "email_verified_at": null,
        "created_at": "2020-04-22T11:35:30.000000Z",
        "updated_at": "2020-04-22T11:35:30.000000Z"
    }
}
```
On failure:
```
{
    "success": false,
    "message": "Invalid Email or Password"
}
```

## 2. Parking Related

After QR code scanning or parking no input App sends following request:

```
curl --location --request GET 'http://128.199.156.18/api/v1/transactions/GOM531001' \
--header 'Authorization: Bearer TOKEN'
```
which updates unlock_requested_at in Transaction Table

Response with parking fee:
```json
{
    "success": true,
    "details": {
        "parkingNo": "GOM531001",
        "hours": 0,
        "minutes": 24,
        "category": "Normal",
        "perHourRate": 2.5,
        "amountDue": 1.0166666666666666
    }
}
```

To pay and unlock

```
curl --location --request GET 'http://128.199.156.18/api/v1/processpayment/GOM531004' \
--header 'Authorization: Bearer TOKEN'
```

