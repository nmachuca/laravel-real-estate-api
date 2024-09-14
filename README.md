# LARAVEL-REAL-STATE-API (v.1.0)

This is intended as the resolution API for a coding challenge in a company selection process.
The company in question is "La casa de Juana" which is real state related company.

## CHANGELOG

The version 1.0 of this project comply only with the requirements of the challenge. In this section will inform of added dependencies to the project and their purpose.

laravel/sanctum => API authorization package

## Considerations and assumptions

This version considers 3 entities: "Persona", "Propiedad" y "SolicitudVisita". A "Persona" (Person entity) refers to either the owner of a "Propiedad" (real state entity) or a contact of the same. In addition we have "SolicitudVisita" (visit request entity). In terms of relationships we will consider the following:

- "SolicitudVisita" : "Persona" := 1:1 (A visit request belongs to a single person)
- "Persona" : "SolicitudVisita" := 1:N (A single person can have multiple visit requests)
- "SolicitudVisita" : "Propiedad" := 1:1 (A visit request is related to a single real state)
- "Propiedad" : "SolicitudVisita" := 1:N (A real state can have multiple visit requests)

In this version we will have minimal constraints regarding security or role considerations.
All endpoints will be accessible for all authorized users with no scopes.

## Local setup

For local setup please execute the following steps:

1. Clone the repository
2. Install PHP dependencies (```composer install```)
    - If prompted, update composer and repeat step 2 (```composer update```)
    - For incompatible package errors, uncomment extensions in your php.ini file by removing `;` in front of required extensions
3. Copy .env.example into .env (```cp .env.example .env```)
4. Set application key (```php artisan key:generate```)
5. Create local DB
6. Set local DB parameters in .env file (DB_* fields)
7. Set default user credentials in .env file (DEFAULT_USER_* fields)
8. Create tables into DB (```php artisan migrate```)
9. Create default user record (```php artisan migrate```)
10. Run web server (```php artisan serve```)

## API documentation

All endpoints base URL is, assuming instructions from local setup section followed, ```http://localhost:8000/api```.
To this base URL you should concatenate the endpoint path.

### **/register** (POST)

This endpoint will allow the creation of new user records into the database.
The body parameters constraints are, as follows:

| **name**              | **description**              | **data type** | **validations**                | **required** |
|-----------------------|------------------------------|---------------|--------------------------------|--------------|
| name                  | user's name                  | string        | length: min 2                  | YES          |
| email                 | user's email                 | string        | rfc, dns, format, unique:users | YES          |
| password              | user's password              | string        | length: min 8                  | YES          |
| password_confirmation | user's password confirmation | string        | confirmed                      | YES          |

Request headers

| **Key**       | **Value**        | 
|---------------|------------------|
| Accept        | application/json |
| Content-Type  | application/json |

- Sample body request
```
{
    "name" : "test",
    "email": "sample@gmail.com",
    "password": "qweryt123",
    "password_confirmation": "qwerty123"
}
```
- Sample success response [HTTP_CODE: 201]
```
{
    "success": true,
    "data": {
        "id": 2,
        "email": "sample@gmail.com"
    },
    "message": "User registered successfully"
}
```
- Sample validation error response [HTTP_CODE: 422]
```
{
    "message": "The email has already been taken.",
    "errors": {
        "email": [
            "The email has already been taken."
        ]
    }
}
```

### **/login** (POST)

This endpoint will validate the given credentials and assign a token to the related user for API purposes.
The body parameters constraints are, as follows:

| **name**              | **description**              | **data type** | **validations**                | **required** |
|-----------------------|------------------------------|---------------|--------------------------------|--------------|
| email                 | user's email                 | string        | rfc, dns, format, exists:users | YES          |
| password              | user's password              | string        | length: min 8                  | YES          |

Request headers

| **Key**       | **Value**        | 
|---------------|------------------|
| Accept        | application/json |
| Content-Type  | application/json |

- Sample body request
```
{
    "email": "sample@gmail.com",
    "password": "qweryt123",
}
```
- Sample success response [HTTP_CODE: 201]
```
{
    "success": true,
    "data": {
        "type": "Bearer",
        "token": "2|j5DgqVWoLCrDEIrfYaqWaveKd8wgr3z7YJqZvZp39dfee2fb"
    },
    "message": "User logged in successfully"
}
```
- Sample validation error response [HTTP_CODE: 422]
```
{
    "message": "The selected email is invalid.",
    "errors": {
        "email": [
            "The selected email is invalid."
        ]
    }
}
```
- Sample error response [HTTP_CODE: 401]
```
{
    "success": false,
    "message": "Credentials not valid"
}
```

### **/persona** (POST)

This endpoint will validate the given data and add a new "Persona" record to the database.
The body parameters constraints are, as follows:

| **name** | **description**   | **data type** | **validations**                   | **required** |
|----------|-------------------|---------------|-----------------------------------|--------------|
| nombre   | person's name     | string        | length: min 3                     | YES          |
| email    | person's email    | string        | rfc, dns, format, unique:personas | YES          |
| telefono | person's password | string        | valid chilean mobile phone number | YES          |

Request headers

| **Key**       | **Value**        | 
|---------------|------------------|
| Accept        | application/json |
| Content-Type  | application/json |
| Authorization | Bearer {TOKEN}   |

- Sample body request
```
{
    "nombre":"test"
    "email": "sample@gmail.com",
    "telefono": "+56998765432",
}
```
- Sample success response [HTTP_CODE: 201]
```
{
    "success": true,
    "data": {
        "id": 1,
        "nombre": "test",
        "email": "sample@gmail.com",
        "telefono": "+56998765432"
    },
    "message": "Persona created successfully"
}
```
- Sample validation error response [HTTP_CODE: 422]
```
{
    "message": "The selected email is invalid.",
    "errors": {
        "email": [
            "The selected email is invalid."
        ]
    }
}
```
- Sample error response [HTTP_CODE: 401]
```
{
    "message": "Unauthenticated."
}
```

### **/personas** (POST)

This endpoint will return a collection of the resource Persona allowing filters, pagination and sort.
The body parameters constraints are, as follows:

| **name**          | **description**                  | **data type** | **validations**                          | **required** |
|-------------------|----------------------------------|---------------|------------------------------------------|--------------|
| pagination        | enables/disables pagination      | boolean       | bool                                     | YES          |
| elements_per_page | number of elements in pagination | integer       | min: 1, max:100, required_if: pagination | NO           |
| filters           | enables/disables filters         | boolean       | bool                                     | YES          |
| nombre            | nombre filter                    | string        | sometimes                                | NO           |
| email             | email filter                     | string        | sometimes                                | NO           |
| sort              | enables sort                     | string        | enum("id", "nombre", "email")            | NO           |
| sort_asc          | defines sort direction           | boolean       | bool, required_id:sort                   | NO           |


Request headers

| **Key**       | **Value**        | 
|---------------|------------------|
| Accept        | application/json |
| Content-Type  | application/json |
| Authorization | Bearer {TOKEN}   |

- Sample body request
```
{
    "pagination":true,
    "elements_per_page":5,
    "filters":true,
    "email":"asd",
    "sort": "nombre",
    "sort_asc":false
}
```
- Sample success response [HTTP_CODE: 200]
```
{
    "success": true,
    "data": {
        "current_page": 1,
        "data": [
            {
                "id": 1,
                "nombre": "test",
                "email": "qwerty@asd.com",
                "telefono": "+56920677912"
            },
            {
                "id": 2,
                "nombre": "test",
                "email": "qwerty1@asd.com",
                "telefono": "+56920677912"
            }
        ],
        "first_page_url": "http://localhost:8000/api/personas?page=1",
        "from": 1,
        "last_page": 4,
        "last_page_url": "http://localhost:8000/api/personas?page=4",
        "links": [
            {
                "url": null,
                "label": "&laquo; Previous",
                "active": false
            },
            {
                "url": "http://localhost:8000/api/personas?page=1",
                "label": "1",
                "active": true
            },
            {
                "url": "http://localhost:8000/api/personas?page=2",
                "label": "2",
                "active": false
            },
            {
                "url": "http://localhost:8000/api/personas?page=3",
                "label": "3",
                "active": false
            },
            {
                "url": "http://localhost:8000/api/personas?page=4",
                "label": "4",
                "active": false
            },
            {
                "url": "http://localhost:8000/api/personas?page=2",
                "label": "Next &raquo;",
                "active": false
            }
        ],
        "next_page_url": "http://localhost:8000/api/personas?page=2",
        "path": "http://localhost:8000/api/personas",
        "per_page": 2,
        "prev_page_url": null,
        "to": 2,
        "total": 7
    },
    "message": "List of Personas"
}
```
- Sample validation error response [HTTP_CODE: 422]
```
{
    "message": "The pagination field must be true or false.",
    "errors": {
        "pagination": [
            "The pagination field must be true or false."
        ]
    }
}
```
- Sample error response [HTTP_CODE: 401]
```
{
    "message": "Unauthenticated."
}
```

### **/persona/{id}** (GET)

This endpoint will return a single Persona resource.
The url parameter constraints are, as follows:

| **name** | **description**   | **data type** | **validations**                   | **required** |
|----------|-------------------|---------------|-----------------------------------|--------------|
| id       | person's id       | integer       | exists:personas                   | YES          |

Request headers

| **Key**       | **Value**        | 
|---------------|------------------|
| Accept        | application/json |
| Authorization | Bearer {TOKEN}   |

- Sample URL request
```
/persona/1
```
- Sample success response [HTTP_CODE: 201]
```
{
    "success": true,
    "data": {
        "id": 1,
        "nombre": "test",
        "email": "qwerty@asd.com",
        "telefono": "+56920677912"
    },
    "message": ""
}
```
- Sample parameter error [HTTP_CODE: 404]
```
{
    "success": false,
    "message": "Persona not found"
}
```
- Sample error response [HTTP_CODE: 401]
```
{
    "message": "Unauthenticated."
}
```
### **/persona/{id}** (PUT/PATCH)

This endpoint will validate the given data and updates en existing "Persona" record in the database.

The url parameter constraints are, as follows:

| **name** | **description**   | **data type** | **validations**                   | **required** |
|----------|-------------------|---------------|-----------------------------------|--------------|
| id       | person's id       | integer       | exists:personas                   | YES          |

The body parameters constraints are, as follows:

| **name** | **description**   | **data type** | **validations**                   | **required** |
|----------|-------------------|---------------|-----------------------------------|--------------|
| nombre   | person's name     | string        | length: min 3                     | YES          |
| email    | person's email    | string        | rfc, dns, format, unique:personas | YES          |
| telefono | person's password | string        | valid chilean mobile phone number | YES          |

Request headers

| **Key**       | **Value**        | 
|---------------|------------------|
| Accept        | application/json |
| Content-Type  | application/json |
| Authorization | Bearer {TOKEN}   |

- Sample body request
```
{
    "nombre":"test"
    "email": "sample@gmail.com",
    "telefono": "+56998765432",
}
```
- Sample success response [HTTP_CODE: 201]
```
{
    "success": true,
    "data": {
        "id": 1,
        "nombre": "test",
        "email": "sample@gmail.com",
        "telefono": "+56998765432"
    },
    "message": "Persona created successfully"
}
```
- Sample validation error response [HTTP_CODE: 422]
```
{
    "message": "The selected email is invalid.",
    "errors": {
        "email": [
            "The selected email is invalid."
        ]
    }
}
```
- Sample parameter error [HTTP_CODE: 404]
```
{
    "success": false,
    "message": "Persona not found"
}
```
- Sample error response [HTTP_CODE: 401]
```
{
    "message": "Unauthenticated."
}
```

### **/persona/{id}** (DELETE)

This endpoint will delete a single Persona resource.
The url parameter constraints are, as follows:

| **name** | **description**   | **data type** | **validations**                   | **required** |
|----------|-------------------|---------------|-----------------------------------|--------------|
| id       | person's id       | integer       | exists:personas                   | YES          |

Request headers

| **Key**       | **Value**        | 
|---------------|------------------|
| Accept        | application/json |
| Authorization | Bearer {TOKEN}   |

- Sample URL request
```
/persona/1
```
- Sample success response [HTTP_CODE: 201]
```
{
    "success": true,
    "data": [],
    "message": "Persona deleted successfully"
}
```
- Sample parameter error [HTTP_CODE: 404]
```
{
    "success": false,
    "message": "Persona not found"
}
```
- Sample error response [HTTP_CODE: 401]
```
{
    "message": "Unauthenticated."
}
```
