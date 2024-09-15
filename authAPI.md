# Auth API endpoints

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
