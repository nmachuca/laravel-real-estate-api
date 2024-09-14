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

## API documentation

### **/register** (POST)

This endpoint will allow the creation of new user records into the database. The parameters constraints are, as follows:

| **name**              | **description**              | **data type** | **validations**                | **required** |
|-----------------------|------------------------------|---------------|--------------------------------|--------------|
| name                  | user's name                  | string        | length: min 2                  | YES          |
| email                 | user's email                 | string        | rfc, dns, format, unique:users | YES          |
| password              | user's password              | string        | length: min 8                  | YES          |
| password_confirmation | user's password confirmation | string        | confirmed                      | YES          |

- Sample request
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
The parameters constraints are, as follows:

| **name**              | **description**              | **data type** | **validations**                | **required** |
|-----------------------|------------------------------|---------------|--------------------------------|--------------|
| email                 | user's email                 | string        | rfc, dns, format, exists:users | YES          |
| password              | user's password              | string        | length: min 8                  | YES          |

- Sample request
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
