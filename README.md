# Task assigning application

Default web page with users' tasks. A public resource.</br>
Logged in users can add/edit/delete/complete a task.</br>

## Setup
```
git clone https://github.com/killedit/2025-10-25-progress-task.git
cd 2025-10-25-progress-task
docker compose up -d --build
```
If you want to follow the docker container logs remove the `-d` flad so you don't detach at the build process.

The application should run migrations and seeders.</br>
There are two example users `test@example.com` with password `test123` and `user@example.com` with password `user123`.</br>
Creating new users is possible and recommended, but not not necessary to test functionality.

`http://127.0.0.1:8007/`

![Web application landingpage](laravel/resources/images/2025-10-25-progress-task-landing-page.png)

## Overall project structure:

```
2025-10-20-progress-task
├── /docker
│   └── /nginx
│       └── default.conf     ← Nginx configuration file for serving the application
│   └── /php
│       └── /conf.d
│           └── xdebug.ini   ← PHP configuration for Xdebug
├── /laravel                 ← Backend
│   ├── Dockerfile           ← Dockerfile for the Laravel backend
│   ├── .env                 ← Laravel specific environment variables - Laravel DB settings
│   └── ...
├── /vuejs                   ← Frontend
│   ├── Dockerfile           ← Dockerfile for the Vue.js frontend
│   └── ...
├── docker-compose.yaml
├── .env                     ← main environment variables - MySQL docker container intialization
```

## REST API resources

| Method   | Endpoint                   | Controller             | Description                                                    |
| -------- | -------------------------- | ---------------------- | -------------------------------------------------------------- |
| `GET`    | `/api/tasks`               | TaskController@index   | List all tasks (auth user’s tasks or public tasks). Paginated. |
| `POST`   | `/api/tasks`               | TaskController@store   | Create a new task (auth user).                                 |
| `GET`    | `/api/tasks/{id}`          | TaskController@show    | View a single task (only if user owns or created it).          |
| `PUT`    | `/api/tasks/{id}`          | TaskController@update  | Update task fields (only if user owns or created it).          |
| `DELETE` | `/api/tasks/{id}`          | TaskController@destroy | Delete task (only if user owns or created it).                 |
| `PATCH`  | `/api/tasks/{id}/complete` | TaskController@update  | Mark a task as completed (only if user owns or created it).    |

### Way to test the API endpoints:

1. To run tests import the Postman collection and enviroment from Laravel folder:

`/laravel/postman/2025-10-25-progress-task-environment.json` </br>
`/laravel/postman/2025-10-20-progress-task-postman_collection.json`

![Postman collection](laravel/resources/images/2025-10-25-progress-task-postman-collection.png)
![Postman environemtn](laravel/resources/images/2025-10-25-progress-task-postman-environment.png)

2. Run curls in the terminal:
```
curl -v -X POST 'http://127.0.0.1:8007/api/login' -H 'Content-Type: application/json' -d '{"email":"test@example.com","password":"test123"}'

    HTTP/1.1 200 OK
    {"user":{"id":1,"name":"Test","email":"test@example.com","email_verified_at":"2025-10-26T11:01:04.000000Z","created_at":"2025-10-26T11:01:04.000000Z","updated_at":"2025-10-26T11:01:04.000000Z"},"token":"11|HQE6aZH1hvxCTfDd0CbqLlHYu1aKMbBuaRoEYfu81279e952"}

curl -v -X GET 'http://127.0.0.1:8007/api/tasks' -H 'Accept: application/json' -H 'Autorization: Bearer {HQE6aZH1hvxCTfDd0CbqLlHYu1aKMbBuaRoEYfu81279e952}'

    HTTP/1.1 200 OK
    {"current_page":1,"data":[{"id":1,"title":"Understand Task Requirements.","description":" ...

http://127.0.0.1:8007/api                           ← If opened in the browser should show "API is running".
http://127.0.0.1:8007/api/tasks
http://127.0.0.1:8007/api/tasks/{id}
http://127.0.0.1:8007/api/tasks/{id}/complete
```

Things to improve:
- Create a swagger with OpenAPI for the REST API resources. Only Postman collection and environment are provided.
- For convenience `.env` files are in project. I understand `.env` file should NOT be in repository and different keys are used for each environment (dev/test/stage?/prod). Secret management services should be used like environment variables in CI/CD Platforms, Docker secrets, etc.
- Store information about deleted records and option to soft delete records - `del` of type enum('no','yes').
- Store information about who modified a record and how many times a records was modified.
- Let users manipulate only tasks they have created or they are assigned to.