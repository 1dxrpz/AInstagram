 | API_BASE_URL | http://127.0.0.1:8001 |

 | POST | /api/posts | Add post
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

 | GET | /api/posts | Get posts |
```request { }```
```response { "message" : string }```

 | GET | /api/posts/{id} | Get post by id |
```request { }```
```response {
	"id": int,
    "Prompt": string,
    "Description": string,
    "ImageURL": string,
    "Title": string
}```

 | DELETE | /api/posts/{id} | Delete post by id |
```request { }```
```response { "message" : string }```

 | PUT | /api/posts/{id} | Update post by id |
```request {
	"prompt" : string,
    "description" : string,
    "imageurl" : string,
    "title" : string
}```
```response { "message" : string }```

 | GET | /api/users | Get users |
```request { }```
```response {
	"id": 8,
    "name": "test",
    "roles": [],
    "password": "test",
    "description": "",
    "email": "test@m.r",
    "avatarid": ""
}```

 | POST | /api/users | Add user |
```request {
    "name" : string,
    "description" : string,
    "password" : string,
    "email" : string,
    "avatarid" : string
}```
```response { "message" : string }```

 | GET | /api/users/{id} | Get post by id |
```request { }```
```response {
	"id": 8,
    "name": "test",
    "roles": [],
    "password": "test",
    "description": "",
    "email": "test@m.r",
    "avatarid": ""
}```

 | DELETE | /api/users/{id} | Delete user by id |
```request { }```
```response { "message" : string }```

 | PUT | /api/users/{id} | Update user by id |
```request {
	"name" : string,
    "description" : string,
    "password" : string,
    "email" : string,
    "avatarid" : string
}```
```response { "message" : string }```