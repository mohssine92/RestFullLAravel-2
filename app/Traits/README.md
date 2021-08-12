* los taraits dentro de laravel se puede crear en cualquier ubicacion , pero es recomendabledentro de app/ 

* estos metodos que estandarizan la salida de mi respuesta de api , la hemos implementado en un trait y no dentro de un controoller base porque trait me permite usar sus metodos en diferentes clases de mi app como es el caso de handler class
  - que encarga de excepciones y comportamiento redereccion en este caso es del err de validation 
