<?php
/**
 * @file
 * Contains \Drupal\helloworld\Form\CollectPhone.
 *
 * В комментарии выше указываем, что содержится в данном файле.
 */

namespace Drupal\customform\Form;

// Указываем что нам потребуется FormBase, от которого мы будем наследоваться
// а также FormStateInterface который позволит работать с данными.
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Объявляем нашу форму, наследуясь от FormBase.
 * Название класса строго должно соответствовать названию файла.
 */
class CollectForm extends FormBase {

  /**
   * То что ниже - это аннотация. Аннотации пишутся в комментариях и в них
   * объявляются различные данные. В данном случае указано, что документацию
   * к данному методу надо взять из комментария к самому классу.
   *
   * А в самом методе мы возвращаем название нашей формы в виде строки.
   *
   * {@inheritdoc}.
   */
  public function getFormId() {
    return 'collect_form';
  }

  /**
   * Создание нашей формы.
   *
   * {@inheritdoc}.
   */
  public function buildForm(array $form, FormStateInterface $form_state) {

      $form['name'] = array(
          '#type' => 'textfield',
          '#title' => ('Your name')
      );

      $form['surname'] = array(
          '#type' => 'textfield',
          '#title' => ('Your surname')
      );

      $form['subject'] = array(
          '#type' => 'textfield',
          '#title' => ('Subject')
      );

      $form['message'] = array(
          //'#type' => 'textarea',
          //'#title' => ('Message')
          '#type' => 'text_format',
          '#title' => ('Message'),
          '#format' => 'full_html',
          '#default_value' => '<p>text(formatted, long)???</p>',
      );

      $form['email'] = array(
          '#type' => 'email',
          '#title' => ('E-mail')
      );

    // Предоставляет обёртку для одного или более Action элементов.
    $form['actions']['#type'] = 'actions';
    // Добавляем нашу кнопку для отправки.
    $form['actions']['submit'] = array(
      '#type' => 'submit',
      '#value' => $this->t('Form submit'),
      '#button_type' => 'primary',
    );
    return $form;
  }

  /**
   * Валидация отправленых данных в форме.
   *
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
      $email = $form_state->getValue('email');
      if (filter_var($email, FILTER_VALIDATE_EMAIL)) {

      }else{
          $form_state->setErrorByName('email', $this->t($email.' - Wrong'));
      }


  }

  /**
   * Отправка формы.
   *
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    // Мы ничего не хотим делать с данными, просто выведем их в системном
    // сообщении.
    drupal_set_message($this->t('@email - OK', array(
      '@email' => $form_state->getValue('email'),
    )));
  }

}
