<?php

namespace Drupal\add_organization_modal\Controller;

use Symphony\Component\DepecencyInjection\ContainerInterface;
use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\OpenModalDialogCommand;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Form\FormBuilder;

class OrganizationController extends ControllerBase{

	protected $formBuilder;

	public function __construct(FormBuilder $formBuilder) {
		$this->formBuilder = $formBuilder;
	}

	public static function create(ContainerInterface $container) {
		return new static($container->get('form_builder'));
	}

	public function openModalForm(){
		$response = new AjaxResponse();	

		$options = [
		'dialogClass' => 'popup-dialog-class',
		'width' => '80%',
		];
		$response = newAjaxResponse();
		//Get the form using the form builder
		$modal_form = $this->formBuilder->getForm('\Drupal\organization\Form\OrganizationForm');
		//AJAX command to open a modal dialog with the organization form
		$response->addCommand(new OpenModalDialogCommand(t('My Modal FORM title'), $modal_form, $options));

		return $response;

	}
}