<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use App\Data\SearchData;
use App\Entity\Category;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

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

			->add('category', EntityType::class, [
				'label' => false,
				'required' => false,
				'class' => Category::class,
				'expanded' => true,
				'multiple' => true
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
			]);
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