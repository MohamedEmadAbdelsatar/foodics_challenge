# Foodics Challenge

## Installation

1. Clone the Repository
    ```
    git clone git@github.com:MohamedEmadAbdelsatar/foodics_challenge.git
    cd foodics_challenge
    ```
2. install project note*: php version is 8.3 and laravel version is 11.
    ```
   composer install
   ```
3. create .env and copy the content of .env.example into .env
4. run database migration command note*: 
    ```
   php artisan migrate
   ```
5. run database seeder
    ```
   php artisan db:seed
   ```
6. start the App
    ```
   php artisan serve
   ```
7. register a user, login then you can create your order.
I also shared a postman collection [here](https://documenter.getpostman.com/view/7845030/2sA3duHDwC)
8. to run the test cases
    ```
    php artisan test
    ```
## Database Schema design
![Database Diagram](/public/database.png)

Let's start with the User table it is only related to orders table (one to many), each order has products (many to many) with pivot quantity, the product table has ingredients (many to many) also with quantity in pivot table, the ingredient table related to units (one to many) and merchant (one to many).

## Order creation cycle

before making an order we should have a user,
1. `AuthController` is responsible to registering a new user also login.
2. use the token created to trigger the `api/orders` endpoint, the request should follow the format.
```
{
    "products": [
        {
            "product_id": 1,
            "quantity": 2
        }
    ]
}
```
3. after validation, request data is passed to `OrderService` which creates the order, pass the inputs to `IngredientService` to check the quantities and update the stock.
4. then attach the products to the order model.
5. during updating the stock `IngredientObserver` will be triggered it check if the `current_stock` is lower than `full_stock` and `is_merchant_notified` is false the Mail `NotifyMerchantLowStock` will be triggered and will notify the merchant.



