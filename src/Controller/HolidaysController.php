<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class HolidaysController extends AbstractController
{

    /**
     * @Route (path="/", methods={"GET"})
     */
    public function index()
    {
        return $this->render('publicHolidays/index.html.twig');
    }

}