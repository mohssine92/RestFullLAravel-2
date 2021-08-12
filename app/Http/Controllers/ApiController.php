<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Traits\ApiResponser;




/*  61: Generalizando las respuestas de la Api
 *  asi todos controladores va a extender de este controller base puesto que el extiende de Controller
 *  al interior de la ApiController estaran todos metodos relacionados con funcionalidad de la Api como tal .
*/
class ApiController extends Controller
{
   // use del Traits video 62 : usar un trait : en lugar de rescribir metodos :asi la ApiController tiene acceso a todos metodos de trait + los controladores que herendan de ApiController tienen acceso tambien a los metodos de tarit
    use ApiResponser;















}
