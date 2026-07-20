<?php

class HomeController extends Controller
{

    public function Home()
    {
        $this->verificarAutenticacion();

        View::render('Home/Home');
    }

}