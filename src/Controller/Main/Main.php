<?php
/**
 * Created by PhpStorm.
 * User: Marcin Nehrebecki
 */

namespace App\Controller\Main;

use App\Controller\AbstractBase;
use Symfony\Component\Routing\Annotation\Route;

class Main extends AbstractBase
{
    /**
     * @Route("/", name="main")
     */
    public function main()
    {

        return $this->render('base.html.twig');
    }
}