<?php

namespace App\Controller;

use App\Entity\Post;
use App\Repository\PostRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/lista', name: 'list')]
    public function list(PostRepository $repository, PaginatorInterface $paginator, Request $request): Response
    {
        $postsQuery = $repository->createQueryBuilder('p')
            ->getQuery();

        $posts = $paginator->paginate($postsQuery, $request->get('page',1),25);

        return $this->render('home/list.html.twig', [
            'posts' => $posts,
        ]);
    }

    #[Route('/delete/{post}', name: 'delete_post')]
    public function delete(PostRepository $repository, Post $post): Response
    {
        $repository->remove($post,true);

        $this->addFlash('info','Usunięto');

        return $this->redirectToRoute('list');
    }
}
