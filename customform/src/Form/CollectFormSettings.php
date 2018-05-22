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
        // Загружаем наши конфиги.
        $config = $this->config('customform.collect_form.settings');

        $form['default_hubspot_key'] = array(
            '#type' => 'textfield',
            '#title' => $this->t('Default hubspot apikey'),
            '#default_value' => $config->get('hubspot_key'),
        );
        // Субмит наследуем от ConfigFormBase
        return parent::buildForm($form, $form_state);
    }


    /**
     * {@inheritdoc}
     */
    public function submitForm(array &$form, FormStateInterface $form_state) {
        $values = $form_state->getValues();
        // Записываем значения в наш конфиг файл и сохраняем.
        $this->config('customform.collect_form.settings')
            ->set('hubspot_key', $values['default_hubspot_key'])
            ->save();
    }
}
