| Description | URL |
|---|---|
| API_BASE_URL  | http://127.0.0.1:8001 |

## Add post

| Method | Endpoint | Description |
|---|---|---|
| POST | /api/posts | Add post |
```
request {
    "prompt" : string,
    "description" : string,
    "imageurl" : string,
    "title" : string
}
```
```
response { "message" : string }
```

## Get posts

| Method | Endpoint | Description |
|---|---|---|
| GET | /api/posts | Get posts |
```
request { }
```
```
response { "message" : string }
```

## Get post by id

| Method | Endpoint | Description |
|---|---|---|
| GET | /api/posts/{id} | Get post by id |
```
request { }
```
```
response {
    "id": int,
    "Prompt": string,
    "Description": string,
    "ImageURL": string,
    "Title": string
}
```

## Delete post by id

| Method | Endpoint | Description |
|---|---|---|
| DELETE | /api/posts/{id} | Delete post by id |
```
request { }
```
```
response { "message" : string }
```

## Update post by id

| Method | Endpoint | Description |
|---|---|---|
| PUT | /api/posts/{id} | Update post by id |
```
request {
	"prompt" : string,
    "description" : string,
    "imageurl" : string,
    "title" : string
}
```
```
response { "message" : string }
```

## Get users

| Method | Endpoint | Description |
|---|---|---|
| GET | /api/users | Get users |
```
request { }
```
```
response {
	"id": 8,
    "name": "test",
    "roles": [],
    "password": "test",
    "description": "",
    "email": "test@m.r",
    "avatarid": ""
}
```

## Add user

| Method | Endpoint | Description |
|---|---|---|
| POST | /api/users | Add user |
```
request {
    "name" : string,
    "description" : string,
    "password" : string,
    "email" : string,
    "avatarid" : string
}
```
```
response { "message" : string }
```

## Get post by id

| Method | Endpoint | Description |
|---|---|---|
| GET | /api/users/{id} | Get post by id |
```
request { }
```
```
response {
	"id": 8,
    "name": "test",
    "roles": [],
    "password": "test",
    "description": "",
    "email": "test@m.r",
    "avatarid": ""
}
```

## Delete user by id

| Method | Endpoint | Description |
|---|---|---|
| DELETE | /api/users/{id} | Delete user by id |

```
request { }
```
```
response { "message" : string }
```

## Update user by id

| Method | Endpoint | Description |
|---|---|---|
| PUT | /api/users/{id} | Update user by id |

```
request {
	"name" : string,
    "description" : string,
    "password" : string,
    "email" : string,
    "avatarid" : string
}
```
```
response { "message" : string }
```
