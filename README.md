# Pusher


## Installation

Create a `deployment.json` that looks something like the following:

```json
{
    "application": {
        "name": "MyProject"
    },
    "repository": {
        "url": "git@github.com:Indatus/pusher.git",
        "username": "indatus"
    },
    "environments" : {
        "dev": {
            "branch": "develop",
            "user": "deploy",
            "servers": [
                "server-alias"
            ]
        }
    },
    "permissions": {
        "access": 755,
        "user": "deploy",
        "group": "www-data"
    }
}
``