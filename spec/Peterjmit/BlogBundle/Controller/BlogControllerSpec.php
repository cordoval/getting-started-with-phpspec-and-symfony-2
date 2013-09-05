<?php

namespace spec\Peterjmit\BlogBundle\Controller;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

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
     * @param \Symfony\Component\HttpFoundation\Response $mockResponse
     */
    function it_responds_to_index_action($blogRepository, $templating, $mockResponse)
    {
        $blogRepository->findAll()->willReturn(['An array', 'of blog', 'posts!']);

        $templating
            ->renderResponse(
                'PeterjmitBlogBundle:Blog:index.html.twig',
                ['posts' => ['An array', 'of blog', 'posts!']]
            )
            ->willReturn($mockResponse)
        ;

        $response = $this->indexAction();

        $response->shouldHaveType('Symfony\Component\HttpFoundation\Response');
    }

    /**
     * @param \Doctrine\ORM\EntityRepository $blogRepository
     * @param \Symfony\Bundle\FrameworkBundle\Templating\EngineInterface $templating
     * @param \Symfony\Component\HttpFoundation\Response $response
     */
    function it_shows_a_single_blog_post($blogRepository, $templating, $response)
    {
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
