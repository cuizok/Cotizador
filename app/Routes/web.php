<?php
// SECCION DE CLIENTES
// ----------------------------------------------------------------------------------

$router->get('/Blackcore/Cotizador/public/index.php','ClienteController@index');

$router->get('/Blackcore/Cotizador/public/clienteById','ClienteController@show');

$router->post('/Blackcore/Cotizador/public/Insert-cliente','ClienteController@store');

$router->put('/Blackcore/Cotizador/public/Update-cliente','ClienteController@update');

$router->put('/Blackcore/Cotizador/public/delete-cliente','ClienteController@delete');

// SECCION DE COTIZACIONES 
// ----------------------------------------------------------------------------------

$router->get('/Blackcore/Cotizador/public/CotizacionAll','CotizacionController@index');

$router->get('/Blackcore/Cotizador/public/CotizacionById','CotizacionController@show');

$router->post('/Blackcore/Cotizador/public/Insert-Cotizacion','CotizacionController@store');

$router->put('/Blackcore/Cotizador/public/Update-Cotizacion','CotizacionController@update');

// SECCION DE DETALLES
// --------------------------------------------------------------------------------------

$router->post('/Blackcore/Cotizador/public/Insert-Detalle','DetalleCotizacionController@store');

$router->get('/Blackcore/Cotizador/public/DetalleByCotizacion','DetalleCotizacionController@show');

$router->put('/Blackcore/Cotizador/public/Update-DetalleCotizacion','DetalleCotizacionController@update');

$router->delete('/Blackcore/Cotizador/public/Delete-DetalleCotizacion','DetalleCotizacionController@delete');


// SECCION DE AUTENTICACIÓN Y LOGIN
// --------------------------------------------------------------------------------------


$router->post('/Blackcore/Cotizador/public/Login','AuthController@login');

$router->get('/Blackcore/Cotizador/public/Session','AuthController@session');

$router->post('/Blackcore/Cotizador/public/Logout','AuthController@logout');