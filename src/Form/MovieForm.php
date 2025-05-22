<?php

namespace App\Form;

use App\Entity\Actor;
use App\Entity\Movie;
use Doctrine\DBAL\Types\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MovieForm extends AbstractType
{
  public function buildForm(FormBuilderInterface $builder, array $options): void
  {
    $builder
      ->add('apiId', TextType::class, [
        'label' => 'IMDb ID or TMDb ID',
        'attr' => ['placeholder' => 'for ex tt01111161'],
      ]);
  }

  public function configureOptions(OptionsResolver $resolver): void
  {
    $resolver->setDefaults([
      'data_class' => Movie::class,
    ]);
  }
}
