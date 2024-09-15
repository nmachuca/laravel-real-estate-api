# VisitaPropiedad resource API endpoints

### **/visita** (POST)

This endpoint will validate the given data and add a new "VisitaPropiedad" record to the database.
The body parameters constraints are, as follows:

| **name**     | **description**           | **data type** | **validations**           | **required** |
|--------------|---------------------------|---------------|---------------------------|--------------|
| persona_id   | visit's related persona   | integer       | exists:personas,id        | YES          |
| propiedad_id | visit's related propiedad | integer       | exists:propiedades,id     | YES          |
| fecha_visita | visit date                | date          | format:Y-m-d, after:today | YES          |
| comentarios  | additional comments       | string        | none                      | NO           |

Request headers

| **Key**       | **Value**        | 
|---------------|------------------|
| Accept        | application/json |
| Content-Type  | application/json |
| Authorization | Bearer {TOKEN}   |

- Sample body request
```
{
    "persona_id":2,
    "propiedad_id": 2,
    "fecha_visita": "2024-09-16"
}
```
- Sample success response [HTTP_CODE: 201]
```
{
    "success": true,
    "data": {
        "id": 5,
        "persona": {
            "id": 2,
            "nombre": "Test a",
            "email": "ullrich.chad@example.com",
            "telefono": "+13173865830"
        },
        "propiedad": {
            "id": 2,
            "direccion": "Avenida Matta 900",
            "ciudad": "Santiago",
            "precio": 3000,
            "descripcion": "3D, 2B"
        },
        "fecha_visita": "2024-09-16 00:00:00",
        "comentarios": null
    },
    "message": "Visita created successfully"
}
```
- Sample validation error response [HTTP_CODE: 422]
```
{
    "message": "The selected persona id is invalid.",
    "errors": {
        "persona_id": [
            "The selected persona id is invalid."
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

### **/visitas** (POST)

This endpoint will return a collection of the resource VisitaPersonas allowing filters, pagination and sort.
The body parameters constraints are, as follows:

| **name**          | **description**                  | **data type** | **validations**                                          | **required** |
|-------------------|----------------------------------|---------------|----------------------------------------------------------|--------------|
| pagination        | enables/disables pagination      | boolean       | bool                                                     | YES          |
| elements_per_page | number of elements in pagination | integer       | min: 1, max:100, required_if: pagination                 | NO           |
| filters           | enables/disables filters         | boolean       | bool                                                     | YES          |
| propiedad_id      | propiedad filter                 | integer       | sometimes, exists:propiedades,id                         | NO           |
| persona_id        | persona filter                   | integer       | sometimes, exists:personas,id                            | NO           |
| fecha_min         | min date filter                  | date          | sometimes                                                | NO           |
| fecha_max         | max date filter                  | date          | sometimes                                                | NO           |
| sort              | enables sort                     | string        | enum("id", "fecha_visita", "propiedad_id", "persona_id") | NO           |
| sort_asc          | defines sort direction           | boolean       | bool, required_id:sort                                   | NO           |


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
    "propiedad_id":3
}
```
- Sample success response [HTTP_CODE: 200]
```
{
    "success": true,
    "data": [],
    "message": "List of Visitas"
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

### **/visita/{id}** (GET)

This endpoint will return a single VisitPropiedad resource.
The url parameter constraints are, as follows:

| **name** | **description** | **data type** | **validations**            | **required** |
|----------|-----------------|---------------|----------------------------|--------------|
| id       | visit's id      | integer       | exists:visitas_propiedades | YES          |

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
        "persona": {
            "id": 3,
            "nombre": "Test a",
            "email": "iwiza@example.org",
            "telefono": "+12409567576"
        },
        "propiedad": {
            "id": 2,
            "direccion": "Avenida Matta 900",
            "ciudad": "Santiago",
            "precio": 3000,
            "descripcion": "3D, 2B"
        },
        "fecha_visita": "2024-09-16 00:00:00",
        "comentarios": null
    },
    "message": ""
}
```
- Sample parameter error [HTTP_CODE: 404]
```
{
    "success": false,
    "message": "Visita not found"
}
```
- Sample error response [HTTP_CODE: 401]
```
{
    "message": "Unauthenticated."
}
```
### **/visita/{id}** (PUT/PATCH)

This endpoint will validate the given data and updates en existing "VisitaPropiedad" record in the database.

The url parameter constraints are, as follows:

| **name** | **description** | **data type** | **validations**            | **required** |
|----------|-----------------|---------------|----------------------------|--------------|
| id       | visit's id      | integer       | exists:visitas_propiedades | YES          |

The body parameters constraints are, as follows:

| **name**     | **description**           | **data type** | **validations**           | **required** |
|--------------|---------------------------|---------------|---------------------------|--------------|
| persona_id   | visit's related persona   | integer       | exists:personas,id        | YES          |
| propiedad_id | visit's related propiedad | integer       | exists:propiedades,id     | YES          |
| fecha_visita | visit date                | date          | format:Y-m-d, after:today | YES          |
| comentarios  | additional comments       | string        | none                      | NO           |

Request headers

| **Key**       | **Value**        | 
|---------------|------------------|
| Accept        | application/json |
| Content-Type  | application/json |
| Authorization | Bearer {TOKEN}   |

- Sample body request
```
{
    "persona_id":3,
    "propiedad_id": 2,
    "fecha_visita": "2024-09-16"
}
```
- Sample success response [HTTP_CODE: 201]
```
{
    "success": true,
    "data": {
        "id": 1,
        "persona": {
            "id": 3,
            "nombre": "Test a",
            "email": "iwiza@example.org",
            "telefono": "+12409567576"
        },
        "propiedad": {
            "id": 2,
            "direccion": "Avenida Matta 900",
            "ciudad": "Santiago",
            "precio": 3000,
            "descripcion": "3D, 2B"
        },
        "fecha_visita": "2024-09-16",
        "comentarios": null
    },
    "message": "Visita updated successfully"
}
```
- Sample validation error response [HTTP_CODE: 422]
```
{
    "message": "The selected persona id is invalid.",
    "errors": {
        "persona_id": [
            "The selected persona id is invalid."
        ]
    }
}
```
- Sample parameter error [HTTP_CODE: 404]
```
{
    "success": false,
    "message": "Visita not found"
}
```
- Sample error response [HTTP_CODE: 401]
```
{
    "message": "Unauthenticated."
}
```

### **/visita/{id}** (DELETE)

This endpoint will delete a single VisitaPropiedad resource.
The url parameter constraints are, as follows:

| **name** | **description** | **data type** | **validations**            | **required** |
|----------|-----------------|---------------|----------------------------|--------------|
| id       | visit's id      | integer       | exists:visitas_propiedades | YES          |

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
    "message": "Visita deleted successfully"
}
```
- Sample parameter error [HTTP_CODE: 404]
```
{
    "success": false,
    "message": "Visita not found"
}
```
- Sample error response [HTTP_CODE: 401]
```
{
    "message": "Unauthenticated."
}
```
