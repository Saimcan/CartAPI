<?php

use App\Dto\Request\Model\CustomerModel;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\Constraints\Positive;
use Symfony\Component\Validator\Constraints\Required;

class CustomerType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('id', IntegerType::class, [
                'constraints' => [
                    new NotNull(),
                    new NotBlank(),
                    new Required(),
                    new Positive()
                ]
            ])
            ->add('name', TextType::class, [
                'constraints' => [
                    new NotNull(),
                    new NotBlank(),
                    new Required()
                ]
            ])->add('since', DateType::class, [
                'constraints' => [
                    new NotNull(),
                    new NotBlank(),
                    new Required(),
                    new \Symfony\Component\Validator\Constraints\DateTime()
                ],
                'widget' => 'single_text',
                'format' => 'yyyy-MM-dd'
            ])->add('revenue', TextType::class,[
                'constraints' => [
                    new NotNull(),
                    new NotBlank(),
                    new Required(),
                    new Positive()
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => CustomerModel::class
        ]);

    }
}
