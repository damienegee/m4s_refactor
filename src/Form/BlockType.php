<?php

namespace App\Form;

use App\Entity\User;
use DateTime;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Validator\Constraints\File;

class BlockType extends AbstractType
{
	private $container;
	private TokenStorageInterface $tokenStorage;

	public function __construct(ContainerInterface $container, TokenStorageInterface $tokenStorage)
	{
		$this->container = $container;
		$this->tokenStorage = $tokenStorage;
	}

	public function buildForm(FormBuilderInterface $builder, array $options): void
	{
		$date = new DateTime();

		/** @var User $user */
		$user = $this->tokenStorage->getToken()->getUser();
		if ($user->hasRole('ROLE_SCHOOLADMIN') || $user->hasRole('ROLE_ADMIN')) {
			$disabled = false;
		} else {
			$disabled = true;
		}

		$builder
			->add('school_logo', FileType::class, array(
			'label' => 'Upload Logo',
			'attr' => array(
				'disabled' => $disabled
			),
			'constraints' => [
				new File([
					'maxSize' => '10000k',
					'mimeTypes' => [
						'image/jpeg',
						'image/gif',
						'image/png',
						'image/jpg',
					],
					'mimeTypesMessage' => 'Please upload a valid image'
				])
			],
			))
			->add('course_label', TextType::class, array(
				'label' => 'Extra info toestel',
				"required" => false,
				'attr' => array(
					'disabled' => $disabled
				)
			))
			->add('created', DateTimeType::class, array(
				'attr' => array(
					'value' => $date->format('d-m-Y H:M:S'),
					'hidden' => true
				),
				"label_attr" => array(
					'hidden' => true
				)
			))
			->add('submit', SubmitType::class, array(
				'label' => 'Submit',
				'attr' => array(
					'class' => "btn btn-primary",
					'disabled' => $disabled
				)
			))
			->add('checkExtraInfo', CheckboxType::class, array(
				'label' => 'Extra info toestel',
				'required' => false,
				'attr' => array(
					'disabled' => $disabled,
					'id' => 'idExtraInfo',
					'name' => 'nameExtraInfo'
				)
			));
			/*->add('checkSchoolInfo', CheckboxType::class, array(
				'label' => 'Schoolinfo shop',
				'required' => false,
				'attr' => array(
					'disabled' => $disabled,
					'id' => 'idSchoolInfo',
					'name' => 'nameSchoolInfo'
				)
			));*/

		// build checkbox except if it's for current forecast
		foreach ($options['forecasts'] as $fc) {
			if ($options['webshop_id'] != $fc['id']) {
				$builder
					->add('' . $fc['id'], CheckboxType::class, array(
						'label' => $fc['id'],
						'required' => false,
						'attr' => array(
							'disabled' => $disabled
						)
					));
			}
		}
		foreach ($this->localeList() as $lang) {
			$builder
				->add('title_' . $lang, TextType::class, array(
				'label' => 'Titel ' . strtoupper($lang),
				'empty_data' => '',
				"required" => false,
				'attr' => array(
					'readonly' => $disabled
				)
			))
				->add('description_' . $lang, CKEditorType::class, array(
					"label" => "Description " . $lang,
					'empty_data' => '',
					'attr' => array(
						'readonly' => $disabled
					)
				));
		}

		$builder->get('school_logo')->setRequired(false);
	}

	public function configureOptions(OptionsResolver $resolver): void
	{
		$resolver->setDefaults([
			'forecasts' => null,
			'webshop_id' => null
			//'data_class' => ReleaseNotes::class,
		]);
	}

	private function localeList(): array
	{
		$ret = array();
		$array = explode(',', $this->container->getParameter('app.enabledlang'));
		foreach ($array as $lang) {
			$ret[$lang] = $lang;
		}
		return array_reverse($ret);
	}
}
