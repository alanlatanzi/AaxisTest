Basic Technical Test PHP Symfony Developer

INSTRUCTIONS

1. Install PostgreSQL if you don't have it (https://www.postgresql.org/download).
2. Install Composer if you don't have it (https://getcomposer.org/download/).
3. Install Symfony CLI if you don't have it (https://symfony.com/download).
4. Clone the repository.
5. Move to the project folder.
6. Open the terminal.
7. Run the command 'composer install' to install the dependencies.
8. Define the connection parameters to the database in the .env file.
9. Run the command 'php bin/console doctrine:database:create' to create the database.
10. Run the command 'php bin/console doctrine:migrations:migrate' to create the tables.
11. Run the command 'symfony server:start' to start the server.
12. Use an HTTP request client like Postman to make requests to the API.

-----------------------------------------------------------------------------------------

REQUESTS TO TEST

1. Create a user
    - POST http://127.0.0.1:8000/api/register
    - Body: 
        {
            "username": "testing_user",
            "password": "testing_password"
        }

2. Login
    - POST http://127.0.0.1:8000/api/login
    - Body: 
        {
            "username": "testing_user",
            "password": "testing_password"
        }

3. Copy the token returned by the login request

4. Create products
    - POST http://127.0.0.1:8000/api/product/load
    - Headers: 
        Authorization: Bearer {token}
    - Body: 
        [
            {
                "sku":"123",
                "product_name":"test_product_1",
                "description":"test_description_1"
            }
            ,
            {
                "sku":"456",
                "product_name":"test_product_2",
                "description":"test_description_2"
            },
            {
                "sku":"789",
                "product_name":"test_product_3",
                "description":"test_description_3"
            }
        ]

5. Update products
    - PUT http://127.0.0.1:8000/api/product/update
    - Headers: 
        Authorization: Bearer {token}
    - Body:
        [
            {
                "sku":"123",
                "product_name":"test_product_1_updated",
                "description":"test_description_1_updated"
            }
            ,
            {
                "sku":"4567",
                "product_name":"test_product_2",
                "description":"test_description_2"
            },
            {
                "sku":"8910",
                "product_name":"test_product_3",
                "description":"test_description_3"
            }
        ]

6. List products
    - GET http://127.0.0.1:8000/api/products
    - Headers: 
        Authorization: Bearer {token}

7. Logout
    - POST http://127.0.0.1:8000/api/logout
    - Headers: 
        Authorization: Bearer {token}


Developed by: Alan Latanzi