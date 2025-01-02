<?php

use Illuminate\Support\Facades\Route;

/*
    Habia un Error que cuando estabamos en una pagina que hacia la peticion al servidor como
    la de Crear o Editar, al momento de recargar esa pagina nos salia 404 NOT FOUND

    Eso pasa porque hace la peticion a Laravel y en este archivo verifica la ruta
    pero no existe con el parametro de Editar o Crear por eso lanza los 404

    En este caso los creamos con argumentos porque el de Editar esta para cada uno de los registros
    que existan y es opcional con "?", lo nombres son como se nos de la gana
        {n1?} -> Para el apartado de la pagina "Save" o "Category"
        {n2?} -> Seria el argumento que puede recibirse como el ID del registro en la pagina de Editar
            Podemos poner tanto como requiramos si tenemos una URL mas compleja
            Asi no tenemos que crear un ROUTE para la pagina de Save y otra para Editar y asi
            Esta misma nos sirve para todas las demas paginas
            Realmente le damos estos nombres Randoms porque no vamos a implementar una logica con ellos
*/
Route::get('/{n1?}/{n2?}/{n3?}', function () {
    return view('vue');
});