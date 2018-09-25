 **Technology**

- Laravel Framework 5.5

- Docker 18.06.1-ce

- PHP 7.0.0

- Data was persisted with MySql 5.6 
    - Connection details:
        - port: `3306`
        - MYSQL_DATABASE: `home24_store__db`
        - MYSQL_USER: `home24_store_db`
        - MYSQL_PASSWORD: `home24_store_db`

- Testing was done with PhpUnit 6.5.13

- Used TDD (Test-Driven Design) approach

 **Main Packages**

- This can be found in the composer.json in the root directory of the project

- PhpUnit 7.0 was used for testing , am more familiar with this than others like Codeception and Behat

- Dingo API - provides flexibility in api development with api versioning, content negotiation, rate limiting and exception handling

- Fractal -  provides a presentation and transformation layer for complex data output

- L5 Repository - used to abstract the data layer, making our application more flexible to maintain 

 **How to run**
- Clone for Github and Copy Environment Variables
```bash
git clone git@github.com:liman4u/home24_store_backend.git

cd home24_store_backend

```

- Docker should be installed before running the next command

- To start the application server and run tests using docker, run the following from root of application:
```bash
sh ./start.sh
```
- Tests can also be run separately by running[from the project's root folder] "composer test" when the docker container is up and running

- In case the start.sh does not seem to be runnable, use 
```bash
chmod 400 start.sh
```

- To run project without docker , follow these steps(Assumed composer is already installed);
```bash
composer install
cp .env.example .env  [Change database connections in .env]
php artisan migrate --seed
php -S localhost:8000 -t ./public
composer test
```


 **Features**

The API  conformed to REST practices and  provide the following functionality:

```
    entities： user, product
    
    post   /api/v1/token                                 create token
    post   /api/v1/register                              register a new user
    get   /api/v1/account                                get user account
    get    /api/v1/refresh                               refresh token
    delete /api/v1/logout                                logout
    
    post   /api/v1/products                              create a product
    get   /api/v1/products                               get products
    get   /api/v1/products?limit=5                       get products with pagination
    get   /api/v1/products?search=test&filter=id;name    get products with searches and filters
    get    /api/v1/products/1                            get product
    put  /api/v1/products/1                              update  a product
    delete /api/v1/products/1                            delete a product
```

 **Endpoints**

- The postman documentation link is at https://documenter.getpostman.com/view/3189851/RWaLvSng

- This application conform to the specified endpoint structure given and return the HTTP status codes appropriate to each operation.  


 **Environment Variables**

- These are found in .env of the root directory of the project

- For production deployments , DEBUG and API_DEBUG should be switched to 'false' and APP_ENV changed to 'production'


 **Data Migration**

- This is found in database/migrations/ in the root directory of the project


 **Routes**

- This can be found in routes/api.php in the root directory of the project