<?php

namespace spec\Peterjmit\BlogBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityRepository;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class BlogControllerSpec extends ObjectBehavior
{
    function let(
        EntityRepository $blogRepository,
        EngineInterface $templating
    ) {
        $this->beConstructedWith($blogRepository, $templating);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Peterjmit\BlogBundle\Controller\BlogController');
    }

    function it_should_respond_to_index_action(
        EntityRepository $blogRepository,
        EngineInterface $templating,
        Response $mockResponse
    ) {
        $blogRepository->findAll()->willReturn(array('An array', 'of blog', 'posts!'));

        $templating
            ->renderResponse(
                'PeterjmitBlogBundle:Blog:index.html.twig',
                array('posts' => array('An array', 'of blog', 'posts!'))
            )
            ->willReturn($mockResponse)
        ;

        $response = $this->indexAction();

        $response->shouldHaveType('Symfony\Component\HttpFoundation\Response');
    }

    function it_shows_a_single_blog_post(
        EntityRepository $blogRepository,
        EngineInterface $templating,
        Response $response
    ) {
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

    function it_throws_an_exception_if_a_blog_post_doesnt_exist(EntityRepository $blogRepository)
    {
        $blogRepository->find(999)->willReturn(null);

        $this
            ->shouldThrow('Symfony\Component\HttpKernel\Exception\NotFoundHttpException')
            ->duringShowAction(999)
        ;
    }
}
