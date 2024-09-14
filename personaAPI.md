# Persona resource API endpoints

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
