Test Symfony Nodriza
====================

## Test

1. Run/build docker
    ```
    docker-compose up
    ```

2. Connect to docker instance

3. Run doctrine migration
    ```
    php bin/console doctrine:migrations:migrate
    ```

## Documentation

You can find "collections" for postman and swagger to test the API:
```
collection/postman.json
collection/swagger.yaml
```

I have maintained a GET endpoint without parameters (Planet List in DB) of the list of planets in the database in addition to the one that obtains the data from https://swapi.dev/.