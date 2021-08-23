<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\ApiController;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class ProductBuyerTransactionController extends ApiController
{

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Product $product, User $buyer)
    {

        $rules = [
            'quantity' => 'required|integer|min:1',
        ]; // la cantida del object Product a conmprar debe sera 1  como minimo

        $this->validate($request, $rules);



        if ( $buyer->id == $product->seller_id ) {
            return $this->errorResponse('El comprador debe ser diferente al vendedor', 409);
        } // no permito un vendedor compra su producto

       // if (!$buyer->esVerificado()) {
       //     return $this->errorResponse('El comprador debe ser un usuario verificado', 409);
       // } // verificar si comprador es user verificad

       // if (!$product->seller->esVerificado()) {
       //   return $this->errorResponse('El vendedor debe ser un usuario verificado', 409);
       // }

        // verificar el vendedor si es user verificado - tener un cuenta no debe permitir a un user subida de un producto a la vente hasta que se verifique el usuari


        if (!$product->estaDisponible()) {
            return $this->errorResponse('El producto para esta transacción no está disponible', 409);
        }

        if ($product->quantity < $request->quantity) {
           return $this->errorResponse('El producto no tiene la cantidad disponible requerida para esta transacción', 409);
        }


        /* cabe la posiblidad que se vayan a generar multiples transacciones de manera simultanea para un mismo producto , debemos asegurar la disponiblidad de cada producto por cada transaccion
         * en caso contrario returnar err correspondiente
         */

        return DB::transaction(function () use ($request, $product, $buyer) { // 101 : min 6 informacion sobre transacciones de la base de datos Importante

            $product->quantity -= $request->quantity; // reducir cuantidad a comprar (compras consegutivas de nivel modial) - mantener cantida acrualzada instantaneamente - se falla algo aqui la transaccion nunca se creara
            $product->save();

            $transaction = Transaction::create([ // crear - efectuar la transaccion por haberla reducido en la db la cantida a comprar del product
                'quantity' => $request->quantity,
                'buyer_id' => $buyer->id,
                'product_id' => $product->id,
            ]);/* si algo falla al momento de crear la transaccion por algun extrana razon pues lo que se haya hecho con la cantidad del producto no tendra efecto , puesto que la transaccion de la base de datos imediatamente
                  va a revertir todos los cambios que hayamos realizado  */

            return $this->showOne($transaction, 201);

        });
         /* transacciones de la base de datos : basicamente son operaciones que se realizan completas de una sola vez , una por una , en caso de falla todo se regresa a su estado normal
            en caso de exito nada fallara , pero es importante tener muy en cuenta esto es principalmente para aegurarnos de que estas transacciones estan construyendo una a una utulizando por supuesto
            transacciones de la base de datos
         */


    }



}
