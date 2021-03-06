<?php
/**
 * RegisterType
 */
namespace Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;
use Validator\Constraints as CustomAssert;

/**
 * Class RegisterType
 * @package Form
 */
class RegisterType extends AbstractType
{
    /**
     *{@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $required = in_array('admin-edit', $options['validation_groups']) ? false : true;
        $builder->add(
            'login',
            TextType::class,
            [
                'label' => 'label.login',
                'required' => true,
                'constraints' => [
                    new Assert\NotBlank(
                        ['groups' => ['register-default', 'admin-edit']]
                    ),
                    new Assert\Length(
                        [
                            'groups' => ['register-default'],
                            'min' => 3,
                            'max' => 20,
                        ]
                    ),
                    new CustomAssert\UniqueUser(
                        [
                            'groups' => ['register-default', 'admin-edit'],
                            'repository' => isset($options['user_repository']) ? $options['user_repository'] : null,
                            'userId' => isset($options['userId']) ? $options['userId'] : null,
                        ]
                    ),
                ],
            ]
        );
        $builder->add(
            'email',
            EmailType::class,
            [
                'label' => 'label.email',
                'required' => true,
                'constraints' => [
                    new CustomAssert\UniqueEmail(
                        [
                            'groups' => ['register-default', 'admin-edit'],
                            'repository' => isset($options['user_repository']) ? $options['user_repository'] : null,
                            'userId' => isset($options['userId']) ? $options['userId'] : null,
                        ]
                    ),
                ],
            ]
        );
        $builder->add(
            'password',
            RepeatedType::class,
            [
                'type' => PasswordType::class,
                'required' => $required,
                'first_options' => [
                    'label' => 'label.password',
                    'constraints' => [
                        new Assert\NotBlank(
                            ['groups' => ['register-default']]
                        ),
                        new Assert\Length(
                            [
                                'groups' => ['register-default'],
                                'min' => 3,
                                'max' => 50,
                            ]
                        ),
                    ],
                ],
                'second_options' => [
                    'label' => 'label.repeat',
                ],
            ]
        );
        $builder->add(
            'role_id',
            ChoiceType::class,
            [
                'label' => 'label.role',
                'required' => true,
                'constraints' => [
                    new Assert\NotBlank(
                        ['groups' => ['admin-edit']]
                    ),
                    new CustomAssert\AdminRole(
                        [
                            'groups' => ['admin-edit'],
                            'userId' => isset($options['userId']) ? $options['userId'] : null,
                        ]
                    ),
                ],
                'choices' => [
                    'ROLE_ADMIN' => 1,
                    'ROLE_USER' => 2,
                ],
            ]
        );
        $builder->add(
            'current',
            PasswordType::class,
            [
                'label' => 'label.current',
                'required' => true,
                'mapped' => false,
            ]
        );
        $builder->add(
            'name',
            TextType::class,
            [
                'label' => 'label.name',
                'required' => true,
                'constraints' => [
                    new Assert\NotBlank(
                        ['groups' => ['register-default', 'admin-edit']]
                    ),
                    new Assert\Length(
                        [
                            'groups' => ['register-default'],
                            'min' => 3,
                            'max' => 45,
                        ]
                    ),
                ],
            ]
        );
        $builder->add(
            'surname',
            TextType::class,
            [
                'label' => 'label.surname',
                'required' => true,
                'constraints' => [
                    new Assert\NotBlank(
                        ['groups' => ['register-default', 'admin-edit']]
                    ),
                    new Assert\Length(
                        [
                            'groups' => ['register-default'],
                            'min' => 3,
                            'max' => 45,
                        ]
                    ),
                ],
            ]
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'register_type';
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'validation_groups' => 'register-default',
                'user_repository' => null,
                'userId' => null,
            ]
        );
    }
}
