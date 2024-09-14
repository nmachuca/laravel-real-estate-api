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

- [Auth API endpoints](authAPI.md)
- [Persona API endpoints](personaAPI.md)
- [Propiedad API endpoints](propiedadAPI.md)

