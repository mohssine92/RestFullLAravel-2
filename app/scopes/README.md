* los scopes son clases normales de php
* v 74 : global Scope : basicamente es una consulta que podemos ejecutar de manera global en un modelo cada vez se realizen consultas sobre el mismo 
* de este metodo de la implementacion de scopes globalmente podemos estar usando la injeccion implementada de nuestros modelos en nuestros params de nuestros funciones : controladores 
  puesto que ya el constructor de los modelos indicados tienen configurada la restriccion(gracos a scopes) antes de cualquier consulta

* como es el caso de injectar modelos de seller y buyer que heredan de user - el scope ser ejecutado en el constructor de modelos de buyer y seller y filra los tipos de entidades que debe returnar
  asi podemos injectar los modelos de manera implicitas en nuestros funciones de controladores . y obtebtenemos solo los seller o buyer - no todos users
