 * implemenatcion de migraciones de nuestros modelos 
 * ex : model user :
   - la migracion basicamenta es la creacion de la tabla users 
   - Recordemos el nombre del model es singular mientars el nombre de la tabla en plural
   - por lo tanto nombre de la tabla generada sera users 
   - asi al ejecuta se creara la tbla con las restricciones que hemos indicado 

   ** Recuerda el orden en la que se estblezcan las migraciones es muy importante - puesto que laravel ejecuta las migraciones de manera secuencial
      por ej : un product  pertenece a user - pues el users tabla debe existir antes que la tabla products .

 * comandos : 
  - php artisan migrate  => instalar las shemas definidas es decir crear tablas
  - php artisan refresh  => actualizar las shemas en caso que haya cambiado algo en las shemas
  - php artisan reset    => eleminara progresivamente todas migraciones que haya ejecutado
  - php artisan rollback => simplemente retrocedera la ultima migracion ejecutada en caso de querere volver un poco atraz progresivamente

  - php artisan make:migration --help  => ayuda sobre el comando V:44
  - php artisan make:migration category_product_table --create=category_product    => generacion de la class migration de la tabla pivote 
    ::  nombre tabal pivote : usamos nombres de modelos en orden alfabetico y en singular y  separados por _ .
 *los comados que nos van a interesar en este espacio los que estan relacionados con migrate*
  - php artisan migrate:refresh --seed
