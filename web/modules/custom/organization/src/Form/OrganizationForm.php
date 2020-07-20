<?php

namespace Drupal\organization\Form;

use Drupal\Core\Database;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Form\ContentEntityForm;
use Drupal\Core\Language\Language;
use Drupal\taxonomy\Entity\Term;

/**
 * Implements an organization form.
 * This form allows publi users to add new organization details for the ERI team to review.
 */
class OrganizationForm extends FormBase {
  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
     $form ['organisation-details'] = [
       '#type' => 'fieldset',
       '#title' => $this->t('<b>Contact details (for UNEP officials to communicate with your organization)</b>'),
       'collapsible' => FALSE,
       'collapsed' => FALSE,
      ];
    
       $form ['organisation-details']['selected_langcode'] ['fieldset'] = [
      '#type' => 'language_select',
      '#title' => $this->t('Language'),
     ];

    $form['organisation-details']['organization_title'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Name of Organization'),
      '#required' => TRUE,
    ];

    $form['organisation-details']['body'] = [
      '#type' => 'textarea',
      '#title' => $this->t('Description of Organization'),
      '#required' => TRUE,
    ];
    /* Get terms from vocabulary */
      $tag_terms = \Drupal::entityManager()->getStorage('taxonomy_term')->loadTree('regions');
      $tags = array();
      foreach ($tag_terms as $tag_term) {
        $tags[$tag_term->tid] = $tag_term->name;
    }
    $form['organisation-details']['field_region'] = [
      '#type' => 'select',
      '#title' => $this->t('Region'),
      '#options' => $tags,
      '#empty_option' => t('-- Pick one or more UN Environment regions --'),
      '#required' => TRUE,
    ];
    /* Get terms from vocabulary */
    $tag_terms = \Drupal::entityManager()->getStorage('taxonomy_term')->loadTree('services_provided');
    $tags = array();
    foreach ($tag_terms as $tag_term) {
        $tags[$tag_term->tid] = $tag_term->name;
    }
      $form['organisation-details']['field_type_of_legal_services_pro'] = [
      '#type' => 'select',
      '#title' => $this->t('Type of legal services provided'),
      '#options' => $tags,
      '#empty_option' => t('-- Choose legal services provided --'),
    ];
    /* Get terms from vocabulary */
      $tag_terms = \Drupal::entityManager()->getStorage('taxonomy_term')->loadTree('organization_type');
      $tags = array();
      foreach ($tag_terms as $tag_term) {
        $tags[$tag_term->tid] = $tag_term->name;
    }
      $form['organisation-details']['field_type_of_organization'] = [
      '#type' => 'select',
      '#title' => $this->t('Type of organization'),
      '#options' => $tags,
      '#empty_option' => t('-- Pick a type of organization --'),
      '#required' => TRUE,
    ];
     /* Get countries from vocabulary */
      $tag_terms = \Drupal::entityManager()->getStorage('taxonomy_term')->loadTree('cit_countries_information');
      $tags = array();
      foreach ($tag_terms as $tag_term) {
        $tags[$tag_term->tid] = $tag_term->name;
    }
       $form['organisation-details']['field_country'] = [
      '#type' => 'select',
      '#title' => $this->t('Country'),
      '#options' => $tags,
      '#empty_option' => t('-- Pick a Country --'),
      '#required' => TRUE,
      
    ];
        $form['organisation-details']['field_e_mail_address'] = [
      '#type' => 'email',
      '#title' => $this->t('E-mail address'),
      '#description' => $this->t('E-mail address, #type = email'),

    ];
       $form['organisation-details']['field_telephone'] = [
      '#type' => 'textarea',
      '#title' => $this->t('Phone Number'),
    ];
      $form['organisation-details']['field_website_name'] = [
      '#type' => 'url',
      '#title' => $this->t('Website URL'),
    ];
      $form ['contact-details'] = [
       '#type' => 'fieldset',
       '#title' => $this->t('<b>Contact details (for environmental defenders seeking legal assistance)</b>'),
       'collapsible' => FALSE,
       'collapsed' => FALSE,
      ];

      $form['contact-details']['field_contact_name'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Your name:'),
    ];
      $form['contact-details']['field_contact_email'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Your email'),
    ];

      $form['actions']['#type'] = 'actions';
    $form['actions']['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('SUBMIT ORGANIZATION INFO'),
      '#button_type' => 'primary',
    ];
    return $form;
  }
   /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'organization_form';
  }

   /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    if (strlen($form_state->getValue('field_telephone')) < 2) {
      //Set an error for the form element with a key of "phone_number"
      $form_state->setErrorByName('field_telephone', $this->t('The phone number is too short. Please enter a full phone number.'));
    }
  }

  /**
   * {@inheritdoc}
   */
 // public function submitForm(array &$form, FormStateInterface $form_state) {
 //   $this->messenger()->addStatus($this->t('The organization @organization has been added. This information will be reviewed and we will get back to you', ['@organization' => $form_state->getValue('organization_title')]));
 // }

   /**
   * {@inheritdoc}
   */
   public function submitForm(array &$form, FormStateInterface $form_state){
      //Gather the current user so the new record has ownership.
        $account = $this->currentUser();
       // Save the submitted entry.
       $newOrganizationNode = \Drupal::entityTypeManager()->getStorage('node')->create([
          'type' => 'organization',
          'title' => $form_state->getValue('organization_title'),
          'body' => $form_state->getValue('body'),
          'field_country' => $form_state->getValue('field_country'),
          'field_region' => $form_state->getValue('field_region'),
          'field_type_of_legal_services_pro' => $form_state->getValue('field_type_of_legal_services_pro'),
          'field_type_of_organization' => $form_state->getValue('field_type_of_organization'),
          'E-mail address' => $form_state->getValue('field_e_mail_address'),
          'field_website_name' => $form_state->getValue('field_website_name'),
          'field_telephone '  => $form_state->getValue('field_telephone'),
       ]);
       $newOrganizationNode->setPublished(false);
       $newOrganizationNode->save();
       drupal_set_message('The organization has been submitted for review. Thank you!');
  }
}