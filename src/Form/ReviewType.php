<?php // src/Form/ReviewType.php
namespace App\Form;

use App\Entity\Review;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ReviewType extends AbstractType
{
    public function buildForm(
        FormBuilderInterface $builder,
        array $options
    ): void {
        $builder
            ->add("rating", IntegerType::class, [
                "label" => "Rating (1–10)",
                "attr" => ["min" => 1, "max" => 10],
            ])
            ->add("content", TextareaType::class, [
                "label" => "Your review",
                "required" => false,
                "attr" => ["rows" => 4],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            "data_class" => Review::class,
        ]);
    }
}
