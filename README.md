# Backend code test

Get started by initialising the project as you would normally.

There are migrations to be run, and also a database seeder. Make sure you run those.

There is an artisan command that has been created to make a user for you to test with (CreateUserCommand). This creates
a user with the credentials: `test@test.com` / `password`

## There are a few tasks that we would like you to complete.

1. Create an endpoint to create an organisation. A few files will need to be completed for this to work.
    - Criteria for completion:
        - Request must be validated.
        - A user must be logged in to complete the request.
        - Organisations should be created with trial period of 30 days.
        - An email should be triggered upon organisation creation that send the logged in user a confirmation. (just a
          plaintext email with the details is perfect for this test.)
        - The JSON response should include the user inside of the organisation. Half of this has been completed, you
          will need to create a transformer for the User and include the data that you believe to be relevant.

2. Fix the code that outputs a collection of organisations
    - Use the transformer to return the data (hint: transformCollection)
    - The endpoint should be able to take a http query param of filter, which can be `subbed`, `trial` or `all`. If not
      included, `all` is the default case.
    - Abstract the controller logic into the service

## Global acceptance criteria

- Code must adhere to PSR12 standards.
- All datetimes returned must be in unix timestamps.
- Code should include docblocks.

## Packages installed.

- Laravel Passport
- Fractal (for transformers)

---

## MY COMMENTS:

- php artisan --version //6.9.0
- php artisan migrate
- php artisan db:seed
- php artisan user:create
- php artisan passport:install
- php artisan key:generate
- php artisan config:clear

---

- Method POST for Authorize (get key Bearer)
    - http://127.0.0.1:8000/api/login
    - user = test@test.com
    - password = password


- Method POST filtered list of Organizations + Bearer Key
    - http://127.0.0.1:8000/api/organisation/list-all
    - filter[trial] = 1
    - filter[subbed] = 1
    - filter[all] = 1


- Method POST for create Organisation + Bearer Key
    - http://127.0.0.1:8000/api/organisation/store
    - name = xxxxx

TODOS
---

- AuthController.php
    - TODO add actions : register, logout
    - TODO fix App/User model
- OrganisationController.php
    - TODO: add Organisation list pagination
- Kernel.php
    - TODO: if need - add CORS middleware
- Organisation.php
    - TODO : add action/cron that will update Subscribed status
- Middleware Authenticate.php
    - TODO login router when not authorized


E-mail Notification


