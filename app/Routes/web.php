<?php

$router->get('/Blackcore/Trainig/public/index.php','ClienteController@index');

$router->get('/Blackcore/Trainig/public/cliente','ClienteController@show');

$router->post('/Blackcore/Trainig/public/cliente','ClienteController@store');

$router->put('/Blackcore/Trainig/public/cliente','ClienteController@update');

$router->put('/Blackcore/Trainig/public/cliente','ClienteController@delete');


$router->get('/Blackcore/Trainig/public/cotizaciones','CotizacionController@index');

$router->post('/Blackcore/Trainig/public/cotizacion','CotizacionController@store');

$router->get('/Blackcore/Trainig/public/cotizacion','CotizacionController@show');