<?php

namespace App\Controller\Admin;

use App\Entity\Posts;
use App\Repository\UserRepository;
use Doctrine\ORM\QueryBuilder;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FieldCollection;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FilterCollection;
use EasyCorp\Bundle\EasyAdminBundle\Config\Assets;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Dto\EntityDto;
use EasyCorp\Bundle\EasyAdminBundle\Dto\SearchDto;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\Field;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Field\SlugField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Bundle\SecurityBundle\Security;

class PostsCrudController extends AbstractCrudController
{

    private Security $security;
    private UserRepository $userRepository;
    public function __construct(Security $security, UserRepository $userRepository)
    {
        $this->security = $security;
        $this->userRepository = $userRepository;
    }
    public static function getEntityFqcn(): string
    {
        return Posts::class;
    }

    public function createEntity(string $entityFqcn)
    {
        $post = new Posts();
        $post->setPostedAt(new \DateTimeImmutable());
        return $post;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            // the labels used to refer to this entity in titles, buttons, etc.
            ->setEntityLabelInSingular('Article')
            ->setEntityLabelInPlural('Articles')
            ->setPageTitle('index', 'Listes des %entity_label_plural%')
            ->setPageTitle('detail', fn(Posts $post) => (string) $post)
            ->setPageTitle('edit', fn(Posts $post) => sprintf('Edition de "<b>%s</b>', $post->getSlug() . '"'))
            ->setFormThemes(['@EasyAdmin/crud/form_theme.html.twig', 'admin/posts/form.html.twig'])
        ;
    }

    public function createIndexQueryBuilder(SearchDto $searchDto, EntityDto $entityDto, FieldCollection $fields, FilterCollection $filters): QueryBuilder
    {
        $qb = parent::createIndexQueryBuilder($searchDto, $entityDto, $fields, $filters);

        // Vérifiez si l'utilisateur a le rôle 'ROLE_EDITOR'
        if ($this->security->isGranted('ROLE_EDITOR')) {
            $user = $this->security->getUser();
            // Filtrer les articles par l'utilisateur connecté
            $qb->andWhere('entity.user = :user')
                ->setParameter('user', $user);
        }

        return $qb;
    }
    public function configureAssets(Assets $assets): Assets
    {
        return $assets
            ->addAssetMapperEntry('app')
            ->addCssFile('styles/admin.css')
            ->addCssFile('styles/form_admin.css')
        ;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            FormField::addColumn(10),
            TextField::new('title', 'titre'),
            DateField::new('postedAt')->setFormat('short')->setDisabled(true),
            TextareaField::new('content', 'Contenu')->hideOnIndex()->setFormTypeOptions([
                'block_name' => 'content', // Utiliser le bloc personnalisé
            ], ['attr' => ['class' => 'tinymce', 'id' => 'Posts_content']]),
            // TextareaField::new('texteditor')->setLabel('Texteditor')->hideOnIndex()->setFormTypeOptions(['attr' => ['class' => 'tinymce', 'id' => 'Posts_content'], 'mapped' => false]),
            FormField::addColumn(2),
            SlugField::new('slug')->setTargetFieldName(['title', 'postedAt'])->setFormTypeOption('attr', ['readonly' => true])->setUnlockConfirmationMessage(
                'Il est recommandé d\'utiliser les slugs automatiques, mais vous pouvez les personnaliser'
            ),
            AssociationField::new('user', 'Auteur')
                ->setFormTypeOption('query_builder', function (UserRepository $userRepository) {
                    // Vérifiez si l'utilisateur a le rôle 'ROLE_EDITOR'
                    if ($this->security->isGranted('ROLE_EDITOR')) {
                        $user = $this->userRepository->findOneBy(['email' => $this->security->getUser()->getUserIdentifier()]);
                        // Filtrer pour n'afficher que l'utilisateur connecté
                        return $userRepository->createQueryBuilder('u')
                            ->where('u.id = :userId')
                            ->setParameter('userId', $user->getId());
                    }
                    // Si l'utilisateur n'est pas éditeur, retourner tous les utilisateurs
                    return $userRepository->createQueryBuilder('u');
                }),

            Field::new('status', 'En ligne ?'),
            AssociationField::new('categories', 'Catégories')->setFormTypeOption('choice_label', 'name')
                ->setFormTypeOption('by_reference', false),
            AssociationField::new('tab', 'Onglets')->setFormTypeOption('choice_label', 'label'),
            AssociationField::new('keywords', 'Mots clés')->setFormTypeOption('choice_label', 'name'),
        ];
    }
}
