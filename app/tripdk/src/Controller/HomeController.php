<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Gallery;

class HomeController extends AbstractController
{
    private $repository;

    public function __construct()
    {
//        $this->repository = $this->getDoctrine()->getRepository(Gallery::class);
        $this->repository = "test";
    }

    /**
     * @Route("/", name="home")
     */
    public function index()
    {
        $gallery = $this->getDoctrine()
            ->getRepository(Gallery::class)
            ->findAll();

//        $gal = new Gallery();
        $gall_arr = [];

        foreach ($gallery as $gal){
            rewind($gal->getImage());
            $gall_arr[] .= "data:image/png;base64," . base64_encode(stream_get_contents($gal->getImage()));
        }

        return $this->render('home/index.html.twig', [
            'controller_name'   => 'HomeController',
            'gallery'           => $gallery,
            'images'             => $gall_arr,
        ]);
    }


}
