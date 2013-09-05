<?php

namespace Peterjmit\BlogBundle\Controller;

use Doctrine\ORM\EntityRepository;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class BlogController
{
    private $blogRepository;
    private $templating;

    public function __construct(EntityRepository $blogRepository, EngineInterface $templating)
    {
        $this->blogRepository = $blogRepository;
        $this->templating = $templating;
    }

    public function indexAction()
    {
        $posts = $this->blogRepository->findAll();

        return $this->templating->renderResponse('PeterjmitBlogBundle:Blog:index.html.twig', array(
            'posts' => $posts
        ));
    }

    public function showAction($id)
    {
        $post = $this->blogRepository->find($id);

        if (!$post) {
            throw new NotFoundHttpException(sprintf('Blog post %s was not found', $id));
        }

        return $this->templating->renderResponse('PeterjmitBlogBundle:Blog:show.html.twig', array(
            'post' => $post
        ));
    }
}
