<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use App\Data\SearchData;

use Symfony\Component\Form\Extension\Core\Type\TextType;

class SearchForm extends AbstractType
{


	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder
			->add('q', TextType::class, [
				'label' => false,
				'required' => false,
				'attr' => [
					'placeholder' => 'Search'
				]
			])

			->add('categories', EntityType::class, [
				'label' => false,
				'required' => false,
				'class' => Category::class,
				'expanded' => true,
				'mutiple' => true
			])

			->add('min', NumberType::class, [
				'label' => false,
				'required' => false,
				'attr' => [
					'placeholder' => 'Min price']
			])

			->add('max', NumberType::class, [
				'label' => false,
				'required' => false,
				'attr' => [
					'placeholder' => 'Max price']
			])

			>add('max', CheckboxType::class, [
				'label' => 'Promo',
				'required' => false,
			])
		;
	}

	public function configureOptions(OptionsResolver $resolver)
	{
		$resolver->setDefaults([
			'data_class' => SearchData::class,
			'method' => 'GET',
			'csrf_protection' => false
		]);
	}

	public function getBlockPrefix()
	{
		return '';
	}
}