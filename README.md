# Task assigning application

Overall project structure (without fluff):

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

## Test the REST API resuorces

| Method   | Endpoint                   | Description                                                     |
| -------- | -------------------------- | --------------------------------------------------------------- |
| `GET`    | `/api/tasks`               | List all tasks belonging to the authenticated user (paginated). |
| `POST`   | `/api/tasks`               | Create a new task.                                              |
| `GET`    | `/api/tasks/{id}`          | View a single task (only if user owns or created it).           |
| `PUT`    | `/api/tasks/{id}`          | Update task fields.                                             |
| `DELETE` | `/api/tasks/{id}`          | Delete task.                                                    |
| `PATCH`  | `/api/tasks/{id}/complete` | Mark a task as completed.                                       |


1. Use the Postman collection from Laravel folder.
    `/laravel/postman/2025-10-20-progress-task.postman_collection.json`
2. Open the resuorces in the browser.
    http://127.0.0.1:8007/api                           ← We should see "API is running"
    http://127.0.0.1:8007/api/tasks
    http://127.0.0.1:8007/api/tasks/{id}
    http://127.0.0.1:8007/api/tasks/{id}/complete