---
title: API Reference

language_tabs:
- bash
- javascript

includes:

search: true

toc_footers:
- <a href='http://github.com/mpociot/documentarian'>Documentation Powered by Documentarian</a>
---
<!-- START_INFO -->
# Info

Welcome to the generated API reference.
[Get Postman Collection](http://localhost/docs/collection.json)

<!-- END_INFO -->

#general
<!-- START_369d7d791f1b8d7c209bf7b00b1e8a37 -->
## Get all products

> Example request:

```bash
curl -X GET "http://localhost/api/products" \
-H "Accept: application/json"
```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "http://localhost/api/products",
    "method": "GET",
    "headers": {
        "accept": "application/json"
    }
}

$.ajax(settings).done(function (response) {
    console.log(response);
});
```

> Example response:

```json
{
    "data": [
        {
            "id": 1,
            "name": "Aut qui.",
            "description": "Possimus qui sit.",
            "price": 2.22,
            "quantity": 740
        },
        {
            "id": 2,
            "name": "Minima.",
            "description": "Libero esse et iure.",
            "price": 3.3,
            "quantity": 925
        },
        {
            "id": 3,
            "name": "Nihil qui.",
            "description": "Dicta animi ratione.",
            "price": 3.52,
            "quantity": 94
        },
        {
            "id": 4,
            "name": "Quo ut.",
            "description": "Similique.",
            "price": 1.04,
            "quantity": 462
        },
        {
            "id": 5,
            "name": "Est est.",
            "description": "Aut sequi nulla.",
            "price": 0.53,
            "quantity": 426
        }
    ]
}
```

### HTTP Request
`GET /api/products`


<!-- END_369d7d791f1b8d7c209bf7b00b1e8a37 -->

<!-- START_3227eb24d05249763b13fc438182c113 -->
## Get one product

> Example request:

```bash
curl -X GET "http://localhost/api/product/{id}" \
-H "Accept: application/json"
```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "http://localhost/api/product/{id}",
    "method": "GET",
    "headers": {
        "accept": "application/json"
    }
}

$.ajax(settings).done(function (response) {
    console.log(response);
});
```

> Example response:

```json
{
    "data": {
        "id": 1,
        "name": "Aut qui.",
        "description": "Possimus qui sit.",
        "price": 2.22,
        "quantity": 740
    }
}
```

### HTTP Request
`GET /api/product/{id}`


<!-- END_3227eb24d05249763b13fc438182c113 -->

<!-- START_a1eb35cb4bfaddb19f52e94934a2ac3d -->
## API Login, on success return JWT Auth token

> Example request:

```bash
curl -X POST "http://localhost/api/token" \
-H "Accept: application/json"
```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "http://localhost/api/token",
    "method": "POST",
    "headers": {
        "accept": "application/json"
    }
}

$.ajax(settings).done(function (response) {
    console.log(response);
});
```


### HTTP Request
`POST /api/token`


<!-- END_a1eb35cb4bfaddb19f52e94934a2ac3d -->

<!-- START_39798dab89951f0e0c3fc59a53f859e5 -->
## Log out
Invalidate the token, so user cannot use it anymore
They have to relogin to get a new token

> Example request:

```bash
curl -X POST "http://localhost/api/logout" \
-H "Accept: application/json"
```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "http://localhost/api/logout",
    "method": "POST",
    "headers": {
        "accept": "application/json"
    }
}

$.ajax(settings).done(function (response) {
    console.log(response);
});
```


### HTTP Request
`POST /api/logout`


<!-- END_39798dab89951f0e0c3fc59a53f859e5 -->

<!-- START_f4dc3f8e7c69eb3e2fea814d8a1e3828 -->
## Refresh the token

> Example request:

```bash
curl -X GET "http://localhost/api/refresh" \
-H "Accept: application/json"
```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "http://localhost/api/refresh",
    "method": "GET",
    "headers": {
        "accept": "application/json"
    }
}

$.ajax(settings).done(function (response) {
    console.log(response);
});
```

> Example response:

```json
null
```

### HTTP Request
`GET /api/refresh`


<!-- END_f4dc3f8e7c69eb3e2fea814d8a1e3828 -->

<!-- START_0f8ecc008bbceb798251c0de85808ef8 -->
## API Register

> Example request:

```bash
curl -X POST "http://localhost/api/register" \
-H "Accept: application/json"
```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "http://localhost/api/register",
    "method": "POST",
    "headers": {
        "accept": "application/json"
    }
}

$.ajax(settings).done(function (response) {
    console.log(response);
});
```


### HTTP Request
`POST /api/register`


<!-- END_0f8ecc008bbceb798251c0de85808ef8 -->

<!-- START_ac5905d5d118885edf5996617f210f30 -->
## Returns the authenticated user

> Example request:

```bash
curl -X GET "http://localhost/api/account" \
-H "Accept: application/json"
```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "http://localhost/api/account",
    "method": "GET",
    "headers": {
        "accept": "application/json"
    }
}

$.ajax(settings).done(function (response) {
    console.log(response);
});
```

> Example response:

```json
null
```

### HTTP Request
`GET /api/account`


<!-- END_ac5905d5d118885edf5996617f210f30 -->

<!-- START_8c16c1301fd36bfcd200dffb0b713084 -->
## Create a product

> Example request:

```bash
curl -X POST "http://localhost/api/products" \
-H "Accept: application/json"
```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "http://localhost/api/products",
    "method": "POST",
    "headers": {
        "accept": "application/json"
    }
}

$.ajax(settings).done(function (response) {
    console.log(response);
});
```


### HTTP Request
`POST /api/products`


<!-- END_8c16c1301fd36bfcd200dffb0b713084 -->

<!-- START_d6dd72d1b69530dafb4600575d0c53d2 -->
## Remove the specified product.

> Example request:

```bash
curl -X DELETE "http://localhost/api/products/{id}" \
-H "Accept: application/json"
```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "http://localhost/api/products/{id}",
    "method": "DELETE",
    "headers": {
        "accept": "application/json"
    }
}

$.ajax(settings).done(function (response) {
    console.log(response);
});
```


### HTTP Request
`DELETE /api/products/{id}`


<!-- END_d6dd72d1b69530dafb4600575d0c53d2 -->

