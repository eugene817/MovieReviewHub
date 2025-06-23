<?php // src/Form/MovieType.php
namespace App\Form;

use App\Entity\Movie;
use App\Entity\Actor;
use App\Entity\Director;
use App\Entity\Genre;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MovieType extends AbstractType
{
    public function buildForm(
        FormBuilderInterface $builder,
        array $options
    ): void {
        $builder
            ->add("title", TextType::class, [
                "label" => "Name",
            ])
            ->add("genres", EntityType::class, [
                "class" => Genre::class,
                "choice_label" => "name",
                "multiple" => true,
                "expanded" => false,
                "label" => "Genre",
            ])
            ->add("directors", EntityType::class, [
                "class" => Director::class,
                "choice_label" => "name",
                "multiple" => true,
                "expanded" => false,
                "label" => "Director",
            ])
            ->add("actors", EntityType::class, [
                "class" => Actor::class,
                "choice_label" => "name",
                "multiple" => true,
                "expanded" => false,
                "label" => "Actor",
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            "data_class" => Movie::class,
        ]);
    }
}
