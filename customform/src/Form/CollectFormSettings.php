<?php

/**
 * @file
 * Contains \Drupal\helloworld\Form\CollectPhoneSettings.
 */

namespace Drupal\customform\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Defines a form that configures forms module settings.
 */
class CollectFormSettings extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'collect_form_settings';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    // Возвращает названия конфиг файла.
    // Значения будут храниться в файле:
    return [
      'customform.collect_form.settings',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {

    // Субмит наследуем от ConfigFormBase
    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {

  }
}
