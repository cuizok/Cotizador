<?php

require_once __DIR__.'/../../core/View.php';
class HomeController
{

public function Home()
{
    View::render('Home/Home');
}
}