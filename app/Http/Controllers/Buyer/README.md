* notamos aqui que el modelo de Buyer implementa varios controladores y cada controlador tiene acciones relacionadas con rutas
  estos acciones o metodos lo que hacer returnan respuesta usando metodos del objeto mnodelo interactuando la db : com almacenar , obtener , obtener data basada en relacion etc ...  para

  BuyerController : hacer acciones simples
  BuyerTransactionController : hace Operaciones Complejas con el modelo Buyer : seccion 22 
  (php artisan make:controller Buyer/BuyerTransactionController -r -m Buyer) : crear controler + injeccion implicita del modelo + controller recourse
  