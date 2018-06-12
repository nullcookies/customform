<?php

namespace Drupal\custom_parser\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Class ParserForm.
 *
 * @package Drupal\custom_parser\Form
 */
class ParserForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'parser_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('custom_parser.parser.form');

    $form['additional_settings'] = [
      '#type' => 'fieldset',
      '#title' => t('Additional settings'),
    ];

    $form['additional_settings']['checkbox'] = [
      '#type' => 'checkbox',
      '#title' => t('Включить'),
      '#default_value' => $config->get('checkbox'),
      '#description' => t('Если нажат чекбокс, данные будут парсится'),
    ];


      $form['additional_settings']['site_select'] = [
          '#type' => 'select',
          '#title' => $this->t('Выберите сайт'),
          '#options' => [
              '1' => $this->t('http://bitcoin-zone.ru/feed/'),
          ],
          '#default_value' => $config->get('site_select') ? $config->get('site_select') : '1',
          '#required' => TRUE,
      ];


    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    parent::validateForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    parent::submitForm($form, $form_state);

      # Получаем объект очереди.
      $queue = \Drupal::queue('site_parser');

    $config = $this->config('custom_parser.parser.form');

      # Если не нажат checkbox, мы удаляем нашу очередь.
      if (empty($form_state->getValue('checkbox'))) {
          $queue->deleteQueue();
      }
      else {
          # Получаем данные по администратору.
          $query = \Drupal::database()->select('users_field_data', 'u')
              ->fields('u', array('uid', 'name'))
              ->condition('u.uid', '1', '=')
              ->condition('u.status', 1);
          $result = $query->execute();

          # Создаем нашу очередь.
          $queue->createQueue();

          foreach ($result as $row) {
              $queue->createItem([
                  'uid' => $row->uid,
                  'name' => $row->name,
              ]);

          }

      }


    $config->set('checkbox', $form_state->getValue('checkbox'))
      ->set('site_select', $form_state->getValue('site_select'))
      ->save();

  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return ['custom_parser.parser.form'];
  }
}
