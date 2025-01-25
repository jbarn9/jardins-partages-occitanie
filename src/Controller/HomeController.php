<?php

namespace App\Controller;

use App\Repository\PagesRepository;
use App\Repository\PostsRepository;
use App\Repository\TabsRepository;
use App\Service\HeaderService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

class HomeController extends AbstractController
{
    private $pageTitle = 'Accueil | Réseau des Semeurs de Jardins';
    public function __construct(
        private HeaderService $headerService,
        private PostsRepository $postsRepository,
        private TabsRepository $tabsRepository,
        private PagesRepository $pagesRepository,
        private SluggerInterface $slugger
    ) {}

    #[Route('/{slug?}', name: 'app_home', methods: ['GET'])]
    public function index(?string $slug, ?int $id): Response
    {
        if ($slug == null) {
            $slug = $this->tabsRepository->findOneBy(['id' => 171])->getSlug();
        }
        $order = 'DESC';
        // get posts
        try {
            $posts = $this->postsRepository->createQueryBuilder('p')
                ->addSelect('t')
                ->innerJoin('p.tab', 't')
                ->andWhere('t.slug = :slug')
                ->setParameter('slug', $slug)
                ->andWhere('p.status = 1')
                ->orderBy('p.posted_at', $order);
            $query = $posts->getQuery();
            $posts = $query->execute();
        } catch (\Exception $e) {
            $posts = [];
        }
        // get pages
        try {
            $pages = $this->pagesRepository->createQueryBuilder('p')
                ->addSelect('t')
                ->leftJoin('p.tabs_page', 't')
                ->andWhere('p.home = 1');
            $query = $pages->getQuery();
            $pages = $query->execute();
        } catch (\Exception $e) {
            $pages = [];
        };
        $select = false;
        $eventCalendar = false;
        if (count($posts) > 0) {
            $select = true;
        }
        if ($slug == 'coming') {
            $eventCalendar = true;
        }

        return $this->render('home/index.html.twig', [
            'pageTitle' => $this->pageTitle,
            'posts' => $posts,
            'pages' => $pages,
            'slug' => $slug,
            'select' => $select,
            'eventCalendar' => $eventCalendar,
            'order' => $order
        ]);
    }

    #[Route('/{slug?}/order/{order}', name: 'app_home_order', methods: ['POST'])]
    public function order(?string $slug, ?string $order): Response
    {
        if ($slug == null) {
            $slug = $this->tabsRepository->findOneBy(['id' => 171])->getSlug();
        }

        if ($order == 'ASC' || $order == 'DESC') {
            try {
                $posts = $this->postsRepository->createQueryBuilder('p')
                    ->addSelect('t')
                    ->innerJoin('p.tab', 't')
                    ->andWhere('t.slug = :slug')
                    ->setParameter('slug', $slug)
                    ->andWhere('p.status = 1')
                    ->orderBy('p.posted_at', $order);
                $query = $posts->getQuery();
                $posts = $query->execute();
            } catch (\Exception $e) {
                $posts = [];
            }
        } else {
            $order = 'DESC';
        }
        $select = true;
        $eventCalendar = false;
        return $this->render('home/postslist.html.twig', [
            'posts' => $posts,
            'slug' => $slug,
            'order' => $order,
            'select' => $select,
            'eventCalendar' => $eventCalendar
        ]);
    }

    #[Route('/{slug}/{id}', name: 'app_home_post', methods: ['GET'])]
    public function post(?string $slug, ?string $id): Response
    {
        try {
            $post = $this->postsRepository->findBy(['id' => $id]);
        } catch (\Exception $e) {
            $post = [];
        }
        $select = false;
        $eventCalendar = false;
        $backToList = true;
        try {
            $pages = $this->pagesRepository->createQueryBuilder('p')
                ->addSelect('t')
                ->leftJoin('p.tabs_page', 't')
                ->andWhere('p.home = 1');
            $query = $pages->getQuery();
            $pages = $query->execute();
        } catch (\Exception $e) {
            $pages = [];
        };
        return $this->render('home/index.html.twig', [
            'pageTitle' => $this->pageTitle,
            'posts' => $post,
            'pages' => $pages,
            'slug' => $slug,
            'select' => $select,
            'eventCalendar' => $eventCalendar,
            'backToList' => $backToList
        ]);
    }
}
