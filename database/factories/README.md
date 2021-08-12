*  una vez tenemos las migraciones creadas ya tenemos estructura donde insertar datos Ahora bien :

*  factories son basicamentes funciones que nos permite crear instancias de diferentes modelos : diferentes recursos por medio de datos falsos 
  - por defecto en laravel los factories hacen uso de una dependencia llamada faker : libreria hecha para php que facilite creacion de cualquier tipo de datos falsos .
   : asi entonces hacemos uso de faker para genarar la cantidad necesaria de datos .

   - asi digamos asi vamos a crear un factory para modelo user

   ** una vez los factories creados , llega el momento de insersacion en db , usamando archivo db seeders : usamos las llamadas de factorties en Orden logico 
   