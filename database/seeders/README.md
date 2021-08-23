* classes seeder donde indico cuantas instancias de factories a crear en db 
* trucarte : limpiar db antes de crear - siempre evito tener basura en modo de desarollo 
 *basicamentos estos classes de seeders se llamarn desde DatabaseSeeder : es el archivo que sera ejecutado por comando de php artisan* 

   *los comados que nos van a interesar en este espacio  db:seed*


 # borra estructuras de tablas y alimentarlas con solo un comando :
  - php artisan migrate:refresh --seed
