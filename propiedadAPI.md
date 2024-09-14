# Propiedad resource API endpoints

### **/propiedad** (POST)

This endpoint will validate the given data and add a new "Propiedad" record to the database.
The body parameters constraints are, as follows:

| **name**    | **description**         | **data type** | **validations**        | **required** |
|-------------|-------------------------|---------------|------------------------|--------------|
| direccion   | propidad's address      | string        | max: 255               | YES          |
| ciudad      | propiedad's city name   | string        | none                   | YES          |
| precio      | propiedad's value       | integer       | min:1, max: 10000 (UF) | YES          |
| descripcion | propiedad's description | string        | none                   | YES          |

Request headers

| **Key**       | **Value**        | 
|---------------|------------------|
| Accept        | application/json |
| Content-Type  | application/json |
| Authorization | Bearer {TOKEN}   |

- Sample body request
```
{
    "direccion":"Avenida Matta 900",
    "ciudad": "Santiago",
    "precio": "3000",
    "descripcion": "3D, 2B"
}
```
- Sample success response [HTTP_CODE: 201]
```
{
    "success": true,
    "data": {
        "id": 1,
        "direccion": "Avenida Matta 900",
        "ciudad": null,
        "precio": 3000,
        "descripcion": "3D, 2B"
    },
    "message": "Propiedad created successfully"
}
```
- Sample validation error response [HTTP_CODE: 422]
```
{
    "message": "The direccion has already been taken. (and 1 more error)",
    "errors": {
        "direccion": [
            "The direccion has already been taken."
        ],
        "precio": [
            "The precio field must not be greater than 10000."
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

This endpoint will return a collection of the resource Propiedad allowing filters, pagination and sort.
The body parameters constraints are, as follows:

| **name**          | **description**                  | **data type** | **validations**                             | **required** |
|-------------------|----------------------------------|---------------|---------------------------------------------|--------------|
| pagination        | enables/disables pagination      | boolean       | bool                                        | YES          |
| elements_per_page | number of elements in pagination | integer       | min: 1, max:100, required_if: pagination    | NO           |
| filters           | enables/disables filters         | boolean       | bool                                        | YES          |
| direccion         | direccion filter                 | string        | sometimes                                   | NO           |
| ciudad            | ciudad filter                    | string        | sometimes                                   | NO           |
| precio_min        | precio_min filter                | integer       | sometimes, min:1                            | NO           |
| precio_max        | precio_max filter                | integer       | sometimes, min:2, 10000                     | NO           |
| sort              | enables sort                     | string        | enum("id", "direccion", "ciudad", "precio") | NO           |
| sort_asc          | defines sort direction           | boolean       | bool, required_id:sort                      | NO           |


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
    "ciudad":"Santiago",
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
                "direccion": "Avenida Matta 900",
                "ciudad": null,
                "precio": 3000,
                "descripcion": "3D, 2B"
            },
            {
                "id": 1,
                "direccion": "Avenida Matta 910",
                "ciudad": null,
                "precio":4000,
                "descripcion": "4D, 2B"
            }
        ],
        "first_page_url": "http://localhost:8000/api/propiedades?page=1",
        "from": 1,
        "last_page": 4,
        "last_page_url": "http://localhost:8000/api/propiedades?page=4",
        "links": [
            {
                "url": null,
                "label": "&laquo; Previous",
                "active": false
            },
            {
                "url": "http://localhost:8000/api/propiedades?page=1",
                "label": "1",
                "active": true
            },
            {
                "url": "http://localhost:8000/api/propiedades?page=2",
                "label": "2",
                "active": false
            },
            {
                "url": "http://localhost:8000/api/propiedades?page=3",
                "label": "3",
                "active": false
            },
            {
                "url": "http://localhost:8000/api/propiedades?page=4",
                "label": "4",
                "active": false
            },
            {
                "url": "http://localhost:8000/api/propiedades?page=2",
                "label": "Next &raquo;",
                "active": false
            }
        ],
        "next_page_url": "http://localhost:8000/api/propiedades?page=2",
        "path": "http://localhost:8000/api/propiedades",
        "per_page": 2,
        "prev_page_url": null,
        "to": 2,
        "total": 7
    },
    "message": "List of Propiedades"
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

### **/propiedad/{id}** (GET)

This endpoint will return a single Propiedad resource.
The url parameter constraints are, as follows:

| **name** | **description** | **data type** | **validations**    | **required** |
|----------|-----------------|---------------|--------------------|--------------|
| id       | propiedad's id  | integer       | exists:propiedades | YES          |

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
        "direccion": "Avenida Matta 900",
        "ciudad": null,
        "precio": 3000,
        "descripcion": "3D, 2B"
    },
    "message": ""
}
```
- Sample parameter error [HTTP_CODE: 404]
```
{
    "success": false,
    "message": "Propiedad not found"
}
```
- Sample error response [HTTP_CODE: 401]
```
{
    "message": "Unauthenticated."
}
```
### **/propiedad/{id}** (PUT/PATCH)

This endpoint will validate the given data and updates en existing "Propiedad" record in the database.

The url parameter constraints are, as follows:

| **name** | **description** | **data type** | **validations**    | **required** |
|----------|-----------------|---------------|--------------------|--------------|
| id       | propiedad's id  | integer       | exists:propiedades | YES          |

The body parameters constraints are, as follows:

| **name**    | **description**         | **data type** | **validations**        | **required** |
|-------------|-------------------------|---------------|------------------------|--------------|
| direccion   | propidad's address      | string        | max: 255               | YES          |
| ciudad      | propiedad's city name   | string        | none                   | YES          |
| precio      | propiedad's value       | integer       | min:1, max: 10000 (UF) | YES          |
| descripcion | propiedad's description | string        | none                   | YES          |

Request headers

| **Key**       | **Value**        | 
|---------------|------------------|
| Accept        | application/json |
| Content-Type  | application/json |
| Authorization | Bearer {TOKEN}   |

- Sample body request
```
{
    "direccion":"Avenida Matta 900",
    "ciudad": "Santiago",
    "precio": "3000",
    "descripcion": "3D, 2B, 50m2"
}
```
- Sample success response [HTTP_CODE: 201]
```
{
    "success": true,
    "data": {
        "direccion":"Avenida Matta 900",
        "ciudad": "Santiago",
        "precio": "3000",
        "descripcion": "3D, 2B, 50m2"
    },
    "message": "Propiedad created successfully"
}
```
- Sample validation error response [HTTP_CODE: 422]
```
{
    "message": "The precio field must not be greater than 10000.",
    "errors": {
        "precio": [
            "The precio field must not be greater than 10000."
        ]
    }
}
```
- Sample parameter error [HTTP_CODE: 404]
```
{
    "success": false,
    "message": "Propiedad not found"
}
```
- Sample error response [HTTP_CODE: 401]
```
{
    "message": "Unauthenticated."
}
```

### **/propiedad/{id}** (DELETE)

This endpoint will delete a single Propiedad resource.
The url parameter constraints are, as follows:

| **name** | **description** | **data type** | **validations**    | **required** |
|----------|-----------------|---------------|--------------------|--------------|
| id       | propiedad's id  | integer       | exists:propiedades | YES          |

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
    "message": "Propiedad deleted successfully"
}
```
- Sample parameter error [HTTP_CODE: 404]
```
{
    "success": false,
    "message": "Propiedad~~~~ not found"
}
```
- Sample error response [HTTP_CODE: 401]
```
{
    "message": "Unauthenticated."
}
```
