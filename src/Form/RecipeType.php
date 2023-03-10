<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Ingredient;
use App\Entity\Recipe;
use App\Entity\RecipeStep;
use App\Repository\IngredientRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Security;


class RecipeType extends AbstractType
{
    private Security $security;
    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        //this code allow to set the first step of recipe
        $recipeStepsValues = new ArrayCollection();
        $recipeStepsValue = new RecipeStep();
        $recipeStepsValues->add($recipeStepsValue);

        $builder
            ->add('name')
            ->add('descriptions')
            ->add('images', FileType::class, [
                'label' => false,
                'multiple' => true,
                'mapped' => false,
                'required' => false
            ])
            ->add('category', EntityType::class, [
                'class' => Category::class,
                'choice_label' => 'name',
                'multiple' => true,
                'autocomplete' => true
            ])
            ->add('ingredients', EntityType::class, [
                'class' => Ingredient::class,
                'choice_label' => 'name',
                'multiple' => true,
                'expanded' => false,
                'query_builder' => function (IngredientRepository $ingredientRepository) {
                    return $ingredientRepository->createQueryBuilder('r')
                        ->orderBy('r.name', 'ASC');
                },
                'autocomplete' => true
            ])
           ->add('creationTime', ChoiceType::class, [
                'choices' => [
                    '5 minutes' => 5,
                    '10 minutes' => 10,
                        '20 minutes' => 20,
                        '30 minutes' => 30,
                        '1 heure' => 60,
                        '1 heure 30 minutes' => 90,
                        '2 heures' => 120
                    ]
                ])
                ->add('numberOfPersons', ChoiceType::class, [
                    'choices' => [
                        '1 personne' => 1,
                        '2 personnes' => 2,
                        '3 personnes' => 3,
                        '4 personnes' => 4,
                        '5 personnes' => 5,
                        '6 personnes' => 6,
                        '7 personnes' => 7,
                        '8 personnes' => 8,
                        '9 personnes' => 9,
                        '10 personnes' => 10
                    ]
                ])
                ->add('recipeStep', CollectionType::class, [
                    'entry_type' => RecipeStepValueType::class,
                    'data' => $recipeStepsValues,
                    'allow_add' => true,
                    'allow_delete' => true,
                ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Recipe::class,
        ]);
    }
}
