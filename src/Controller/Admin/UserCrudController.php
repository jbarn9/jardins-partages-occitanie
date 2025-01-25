<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Controller\Admin\Traits\ReadOnlyTraits;
use DateTimeImmutable;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Assets;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\Field;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TelephoneField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Validator\Constraints\PasswordStrength;

#[IsGranted('ROLE_ADMIN')]
class UserCrudController extends AbstractCrudController
{
    public function __construct(
        private readonly UserPasswordHasherInterface $userPasswordHasher
    ) {}

    public static function getEntityFqcn(): string
    {
        return User::class;
    }
    public function configureAssets(Assets $assets): Assets
    {
        return $assets
            ->addAssetMapperEntry('app')
            ->addCssFile('styles/admin.css')
            ->addCssFile('styles/form_admin.css')
            ->addHtmlContentToBody('<!-- generated at ' . time() . ' -->')
        ;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud

            // the labels used to refer to this entity in titles, buttons, etc.
            ->setEntityLabelInSingular('Utilisateur')
            ->setEntityLabelInPlural('Utilisateurs')
            ->setPageTitle('index', 'Listes des %entity_label_plural%')
            ->setPageTitle('detail', fn(User $user) => (string) $user)
            ->setPageTitle('edit', fn(User $user) => sprintf('Edition de "<b>%s</b>', $user->getFirstName() . ' ' . $user->getLastName() . '"'))
            ->setEntityPermission('ROLE_ADMIN')
            ->setFormThemes(['@EasyAdmin/crud/form_theme.html.twig', 'admin/posts/form.html.twig'])
        ;
    }
    // public function configureActions(Actions $actions): Actions
    // {
    //     return $actions
    //         ->disable('new');
    // }

    public function configureFields(string $pageName): iterable
    {
        $roles = ['ROLE_ADMIN', 'ROLE_EDITOR'];
        $passwordMeter = '  <div class="password-meter">
                                <div class="meter-section rounded me-2 weak"></div>
                                <div class="meter-section rounded me-2 medium"></div>
                                <div class="meter-section rounded me-2 strong"></div>
                                <div class="meter-section rounded very-strong"></div>
                            </div>
                            <div id="passwordHelp" class="form-text text-muted">
                                Utilisez 8 caractères ou plus avec une combinaison de lettres, de chiffres et de symboles.
                            </div>';
        $fields = [
            FormField::addPanel('Informations générales')
                ->setIcon('fa fa-user')->addCssClass('required'),
            IdField::new('id')->hideOnForm(),
            Field::new('login'),
            Field::new('firstname')->setLabel("Prénom"),
            Field::new('lastname')->setLabel('Nom'),
            ChoiceField::new('roles')
                ->setChoices(array_combine($roles, $roles))
                ->allowMultipleChoices()
                ->renderExpanded(),
            FormField::addPanel('Informations de contact')
                ->setIcon('fa fa-mobile')->addCssClass('required'),
            TelephoneField::new('mobile')->setLabel('Téléphone'),
            EmailField::new('email'),

            FormField::addPanel('Détails du compte')->setIcon('fa fa-lock')->setHelp('Si vous voulez que l\'utilisateur valide son compte par email, cochez la case ci-dessous'),
            BooleanField::new('isVerified')->setLabel('Compte vérifié ?')->renderAsSwitch(true)->onlyOnForms(),
        ];
        if ($pageName === Crud::PAGE_NEW) {
            $password = TextField::new('password')
                ->setFormType(RepeatedType::class)
                ->setFormTypeOptions([
                    'type' => PasswordType::class,
                    'first_options' => ['label' => 'Mot de passe'],
                    'second_options' => ['label' => 'Répéter le mot de passe'],
                    'mapped' => true,
                ])
                ->setRequired($pageName === Crud::PAGE_NEW)
                ->onlyWhenCreating();
        } else {
            $password = TextField::new('password')->setLabel("Mot de passe")->setFormTypeOptions(['mapped' => false, 'required' => false])->onlyWhenCreating();
        }

        $fields[] = $password;
        return $fields;
    }

    use ReadOnlyTraits;
}
