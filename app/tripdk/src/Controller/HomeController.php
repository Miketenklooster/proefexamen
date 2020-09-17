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

        $gall = [];

        foreach ($gallery as $gal){
            $image_name = strval($gal->getImage());
            rewind($gal->getImage());

            $gall[] =
                [
                    'image_name' => $image_name,
                    'image'      => "data:image/png;base64," . base64_encode(stream_get_contents($gal->getImage())),
                    'info'       => $gal->getInfo(),
                    'category'   => $gal->getCategory(),
                ];
        }

        return $this->render('home/index.html.twig', [
            'controller_name'   => 'HomeController',
            'gallery'           => $gall,
        ]);
    }


}
