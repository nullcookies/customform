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

      $form['lastname'] = array(
          '#type' => 'textfield',
          '#title' => ('Your lastname')
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
          $form_state->setErrorByName('email', $this->t(($email) ?  $email.' - Wrong' : 'Wrong'));
      }


  }

  /**
   * Отправка формы.
   *
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {

      $response = ($this->hubspotRequest($form_state));
      if(!empty($response['Error'])){
          drupal_set_message($this->t($response['Data']),'error');
      }else{
          drupal_set_message($this->t('@email - OK', array(
              '@email' => $form_state->getValue('email'),
          )));


          \Drupal::logger('customform_send')->notice( $form_state->getValue('email').' почта отправлена.',
              array(

              ));


      }

  }

    /**
     * Create a new contact
     *
     * Docs: https://developers.hubspot.com/docs/methods/contacts/create_contact#tab-4
     *
     * @param object $form_state
     */
    public function hubspotRequest($form_state) {
        $config = \Drupal::config('customform.collect_form.settings');

        $apikey =($config->get('hubspot_key'))? $config->get('hubspot_key') : 'ab816df0-f023-4bcb-9418-edda545c9489';
        $arr = [
            'properties' => [
                [
                    'property' => 'email',
                    'value' => $form_state->getValue('email')
                ],
                [
                    'property' => 'firstname',
                    'value' => $form_state->getValue('name')
                ],
                [
                    'property' => 'lastname',
                    'value' => $form_state->getValue('lastname')
                ]
            ]
        ];
        $post_json = json_encode($arr);
        $endpoint = 'https://api.hubapi.com/contacts/v1/contact?hapikey=' . $apikey;
        $ch = @curl_init();
        @curl_setopt($ch, CURLOPT_POST, true);
        @curl_setopt($ch, CURLOPT_POSTFIELDS, $post_json);
        @curl_setopt($ch, CURLOPT_URL, $endpoint);
        @curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        @curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = @curl_exec($ch);
        $status_code = @curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curl_errors = curl_error($ch);
        @curl_close($ch);
        $response = json_decode($response);
        return array('Data' => (!empty($response->message)) ? $response->message : '', 'Error' => (!empty($response->status) and $response->status == ('error')) ? $response->status : '',
            'HTTPCode' => $status_code);
    }

}
