<?php

namespace AppBundle\Controller;

use AppBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Core\Security;

class UserController extends Controller
{
    /**
    * @Route("/connected", name="connected")
    */
    public function connectedAction(Security $security)
    {
        $user = $security->getUser();

        if ($user) {
            return new Response($user->getUsername());
        }
        else {
            return new Response("");
        }
    }



}
