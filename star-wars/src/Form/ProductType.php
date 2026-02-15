<?php

namespace App\Form;

use App\Entity\Product;
use App\Entity\Category;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductType extends AbstractType{


    public function buildForm(FormBuilderInterface $builder, array $options): void{

        $builder
            ->add('name', TextType::class)
            ->add('price', MoneyType::class)
            ->add('description', TextType::class)
            ->add('image', TextType::class)
            ->add('category',EnumType::class,[
                'class' => Category::class,
                'expanded' => false,
                ])
            ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}

