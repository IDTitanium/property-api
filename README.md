## PROPERTY API

This API allows a user to be able to create and retrieve a list of properties in a fast efficient manner.

## Project Setup

The easiest way to get this project up and running is through the use of docker but you could also set this up manually.

### Minimum requirements

You need to have PHP >= 8.1

### Using Docker

If you have docker installed, you need to start the docker engine, then, from the project folder

- Run `composer install`
- Next, copy the environment variables using the command `cp .env.example .env`
- Next, run the command `./vendor/bin/sail up`.
- Run the migrations `./vendor/bin/sail artisan migrate`.

The app port has been bound to 8084 to avoid clashing with the port 80 on your machine which may already be in use. Hence, your localhost base url will be at `localhost:8084/api`.

The `./vendor/bin/sail up` command will launch all required services. The docker images that will be pulled are `php 8.2`, `pgsql` and `redis`.

Once you've completed this you can skip the `Manual Setup` and go straight down to the `API Usage`.

### Manual Setup (Without Docker)

To set this up manually, you need to have the following on your device locally.

- PHP version >= 8.2
- Database - PostgreSQL 
- In-memory storage - Redis
- Laravel version - 9.x

If you cannot install Redis locally, you can change your `CACHE_DRIVER` environment variable to `file`. However, it is recommended to use Redis for better scalability.

After pulling the project from Github, you should do the following.

- Run `composer install`
- Run `php artisan key:generate`
- Set the environment variables and database credentials
- Run `php artisan migrate`

#### Environment variables

All Environment variables required can be found in the `.env.example` file.

## API Usage

### To create a single property

Endpoint - POST /api/properties

Example Request Body - 
```
{
    "address": {
        "line_1": "24",
        "line_2": "Morrybound Street",
        "postcode": "1234"
    }
}
```

Example Response Body
```
{
    "message": "Property stored successfully",
    "data": {
        "id": 4,
        "address_line_1": "24",
        "address_line_2": "Morrybound Street",
        "postcode": "1234",
        "created_at": "2023-01-27T17:07:48.000000Z",
        "updated_at": "2023-01-27T17:07:48.000000Z"
    }
}
```

### To Create a lot of Properties via file upload

You can use the bulk upload feature, to download a sample csv [Click here to download sample CSV](public/properties.csv).

Or you can call the endpoint `GET /api/properties/sample-csv` to download the sample csv.

#### Upload Endpoint

Endpoint - `POST /api/properties`

Example request body

`properties - [file] properties.csv`

The above is expected to be a form data with the key `properties` and the value, a file upload.

Example response body
```
{
    "message": "Properties uploaded successfully",
    "data": null
}
```

### To Get the list of properties created

Endpoint - `GET /api/properties`

No request body.

Example response body

```

    "message": "Properties fetched successfully",
    "data": {
        "current_page": 1,
        "data": [
            {
                "id": 1,
                "address_line_1": "12",
                "address_line_2": "Babcock street",
                "postcode": "1234",
                "created_at": "2023-01-27T17:05:49.000000Z",
                "updated_at": "2023-01-27T17:05:49.000000Z"
            },
            {
                "id": 2,
                "address_line_1": "13",
                "address_line_2": "Hellmond street",
                "postcode": "2345",
                "created_at": "2023-01-27T17:05:49.000000Z",
                "updated_at": "2023-01-27T17:05:49.000000Z"
            },
            {
                "id": 3,
                "address_line_1": "15",
                "address_line_2": "MackBen drive",
                "postcode": "8477",
                "created_at": "2023-01-27T17:05:49.000000Z",
                "updated_at": "2023-01-27T17:05:49.000000Z"
            },
            {
                "id": 4,
                "address_line_1": "24",
                "address_line_2": "Morrybound Street",
                "postcode": "1234",
                "created_at": "2023-01-27T17:07:48.000000Z",
                "updated_at": "2023-01-27T17:07:48.000000Z"
            }
        ],
        "first_page_url": "http://localhost/api/properties?page=1",
        "from": 1,
        "last_page": 1,
        "last_page_url": "http://localhost/api/properties?page=1",
        "links": [
            {
                "url": null,
                "label": "&laquo; Previous",
                "active": false
            },
            {
                "url": "http://localhost/api/properties?page=1",
                "label": "1",
                "active": true
            },
            {
                "url": null,
                "label": "Next &raquo;",
                "active": false
            }
        ],
        "next_page_url": null,
        "path": "http://localhost/api/properties",
        "per_page": 20,
        "prev_page_url": null,
        "to": 4,
        "total": 4
    }
}
```

## Testing

To run the tests in this project, you can run the command.

- With docker `./vendor/bin/sail artisan test` 
- Without docker `php artisan test`
