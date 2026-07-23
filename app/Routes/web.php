<?php

// ======================================================
// CLIENTES
// ======================================================

$router->get('/ClienteAll', 'ClienteController@index');

$router->get('/clienteById', 'ClienteController@show');

$router->post('/Insert-cliente', 'ClienteController@store');

$router->put('/Update-cliente', 'ClienteController@update');

$router->put('/delete-cliente', 'ClienteController@delete');


// ======================================================
// COTIZACIONES
// ======================================================

$router->get('/CotizacionAll', 'CotizacionController@index');

$router->get('/CotizacionById', 'CotizacionController@show');

$router->post('/Insert-Cotizacion', 'CotizacionController@store');

$router->put('/Update-Cotizacion', 'CotizacionController@update');


// ======================================================
// DETALLE COTIZACIÓN
// ======================================================

$router->post('/Insert-Detalle', 'DetalleCotizacionController@store');

$router->get('/DetalleByCotizacion', 'DetalleCotizacionController@show');

$router->put('/Update-DetalleCotizacion', 'DetalleCotizacionController@update');

$router->delete('/Delete-DetalleCotizacion', 'DetalleCotizacionController@delete');


// ======================================================
// AUTENTICACIÓN
// ======================================================

$router->post('/Login', 'AuthController@login');

$router->get('/Session', 'AuthController@session');

$router->post('/Logout', 'AuthController@logout');


// ======================================================
// VISTAS DEL LOGIN (INICIO DE SESIÓN)
// ======================================================

$router->get('/', 'AuthController@viewLogin');

$router->get('/login', 'AuthController@viewLogin');

$router->post('/login', 'AuthController@login');

// ======================================================
// VISTAS DEL HOME (SECCIÓN PRINCIPAL)
// ======================================================


$router->get('/Home', 'HomeController@Home');




// ======================================================
// VISTAS DEL CLIENTE 
// ======================================================


$router->get('/Cliente', 'ClienteController@Cliente');




// ======================================================
// VISTAS DE COTIZACION
// ======================================================


$router->get('/Cotizaciones', 'CotizacionController@CotizacionView');

$router->get('/Add/NuevaCotizacion', 'CotizacionController@NuevaCotizacionView');


$router->get('/Edit/EditarCotizacion', 'CotizacionController@EditCotizacionView');


