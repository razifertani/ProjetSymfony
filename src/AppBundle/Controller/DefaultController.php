<?php

namespace AppBundle\Controller;

use AppBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use AppBundle\Entity\Book;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Core\Security;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function home()
    {
        // replace this example code with whatever you need
        return $this->render('default/index.html.twig');
    }

    /**
    * @Route("/connected", name="connected")
    */
    public function connectedAction(Security $security)
    {
        $user = $security->getUser();
        return new Response($user);
    }


    /**
     * @Route("/book/add/{title}/{author}", name="book_add")
     */
    public function createAction($title,$author)
    {
        // you can fetch the EntityManager via $this->getDoctrine()
        // or you can add an argument to your action: createAction(EntityManagerInterface $entityManager)
        $entityManager = $this->getDoctrine()->getManager();

        $book = new Book();
        $book->setTitle($title);
        $book->setAuthor($author);

        // tells Doctrine you want to (eventually) save the Product (no queries yet)
        $entityManager->persist($book);

        // actually executes the queries (i.e. the INSERT query)
        $entityManager->flush();

        return new Response('Saved new product with id '.$book->getId());
    }

    /**
     * @Route("/user/{id}/{username}", name="user_edit")
     */
    public function update($id,$username)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $user = $entityManager->getRepository(User::class)->find($id);

        if (!$user) {
            throw $this->createNotFoundException(
                'No product found for id '.$id
            );
        }

        $user->setUsername($username);
        $entityManager->flush();

        return new Response('Success edit '.$user->getId());
    }

    /**
     * @Route("/list/{id}", name="book_list")
     */
    public function showAction($id)
    {
        $book = $this->getDoctrine()
            ->getRepository(Book::class)
            ->find($id);

        if (!$book) {
            throw $this->createNotFoundException(
                'No product found for id '.$id
            );
        }

        // ... do something, like pass the $product object into a template
        return new Response('Saved new product with id '.$id);
    }

    /**
     * @Route("/book/all", name="book_showAll")
     */
    public function serviceShowListAction(){
        $list=$this->getDoctrine()->getRepository(Book::class)->findAll();
        $data=["liste"=>[]];
        forEach($list as $key=>$value){

            array_push($data["liste"],[
                "id"=>$value->getId(),
                "title"=>$value->getTitle(),
                "author"=>$value->getAuthor(),

            ]);
        }
        header('Content-type: application/json');
        return  new Response(json_encode( $data ));
    }

}
