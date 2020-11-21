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
 
 to test all in postman you have file in project called taskBill.postman_collection.json import it in
 postman to use requests.
