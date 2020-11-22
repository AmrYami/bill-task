# Challenge Description: https://github.com/Edfa3ly/take-home-coding-challenge/blob/master/back-end.md

solution description.

used:
JWTAUTH package.
composite design pattern.
solid principles.
unit testing for bill only.

please first you need to create 2 databases 1 for deploy set in .env another for testing and
 set in .env.example.
 please sure you have 2 files in project (.env, .env.example)
 
 then do these commands :
 1: composer install.
 2: php artisan migrate --seed.
 3: php artisan jwt:secret

note:
    i used controller to get request and return response and then send it to service, 
    service use classes as need then call repository to call database
     then return result.
     
    contorller call service 
            service use classes and call repository
            
all offers and tax in (app/BillClasses(BillClasses implement interface)) everyone has its class.
and we have directory app/Helpers for manage all currencies we need to use.

 user can set discount for any items but have to has type merchant check middleware merchant and already
 have to be auth.
 
 to test all in postman you have file in project called taskBill.postman_collection.json import it 
in postman to use requests.
 
 test in app/tests/Feature/Bill.php
 
 
 links API's:
 localhost/bill-task/public/api/products/buyItems // bill proccess you can add currency to change mount just add ?currency=EGY
 post json: {"items": [{
            			"id": 1,
            			"count": 2
            		},
            		{
            			"id": 4,
            			"count": 1
            		},
            		{
            			"id": 3,
            			"count": 1
            		}
            	]
            }
 header: [{"key":"Content-Type","value":"application/json","description":""}]
 
 
localhost/JWTWithCrud/public/api/products/4/createDiscount // add discount to any items just set id and account have to be merchant
post data: [{"key":"discount_percentage","value":"10","description":""}]
header: [{"key":"Authorization","value":"bearer TOKEN","description":""}]

localhost/bill-task/public/api/auth/login
post data: [{"key":"email","value":"amr@gmail.com","description":""},{"key":"password","value":"123456789","description":""}]
