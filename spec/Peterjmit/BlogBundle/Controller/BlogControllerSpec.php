<?php

namespace spec\Peterjmit\BlogBundle\Controller;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\HttpFoundation\Response;

class BlogControllerSpec extends ObjectBehavior
{
    /**
     * @param \Doctrine\ORM\EntityRepository $blogRepository
     * @param \Symfony\Bundle\FrameworkBundle\Templating\EngineInterface $templating
     */
    function let($blogRepository, $templating)
    {
        $this->beConstructedWith($blogRepository, $templating);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Peterjmit\BlogBundle\Controller\BlogController');
    }

    /**
     * @param \Doctrine\ORM\EntityRepository $blogRepository
     * @param \Symfony\Bundle\FrameworkBundle\Templating\EngineInterface $templating
     */
    function it_responds_to_index_action($blogRepository, $templating)
    {
        $response = new Response();

        $blogRepository->findAll()->willReturn(['An array', 'of blog', 'posts!']);

        $templating
            ->renderResponse(
                'PeterjmitBlogBundle:Blog:index.html.twig',
                ['posts' => ['An array', 'of blog', 'posts!']]
            )
            ->willReturn($response)
        ;

        $response = $this->indexAction();

        $response->shouldHaveType('Symfony\Component\HttpFoundation\Response');
    }

    /**
     * @param \Doctrine\ORM\EntityRepository $blogRepository
     * @param \Symfony\Bundle\FrameworkBundle\Templating\EngineInterface $templating
     */
    function it_shows_a_single_blog_post($blogRepository, $templating)
    {
        $response = new Response();

        $blogRepository->find(1)->willReturn('A blog post');

        $templating
            ->renderResponse(
                'PeterjmitBlogBundle:Blog:show.html.twig',
                Argument::withEntry('post', 'A blog post')
            )
            ->willReturn($response)
        ;

        $this->showAction(1)->shouldReturn($response);
    }

    /**
     * @param \Doctrine\ORM\EntityRepository $blogRepository
     */
    function it_throws_an_exception_if_a_blog_post_doesnt_exist($blogRepository)
    {
        $blogRepository->find(999)->willReturn(null);

        $this
            ->shouldThrow('Symfony\Component\HttpKernel\Exception\NotFoundHttpException')
            ->duringShowAction(999)
        ;
    }
}
