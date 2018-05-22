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
          $form_state->setErrorByName('email', $this->t(($email) ?  $email.' - Wrong' : 'Wrong'));
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
      var_dump($this->requestHubspot($form_state));
      die();
    drupal_set_message($this->t('@email - OK', array(
      '@email' => $form_state->getValue('email'),
    )));
  }

  public function requestHubspot($form_state)
  {
      $hubspotutk = $_COOKIE['hubspotutk'];

      $ip_addr = $_SERVER['REMOTE_ADDR'];

      $hs_context = array(

          'hutk' => $hubspotutk,

          'ipAddress' => $ip_addr,

          'pageUrl' => 'http://www.example.com/form-page',

          'pageName' => 'Example Title'

      );

      $hs_context_json = json_encode($hs_context);


      $str_post = "firstname=" . urlencode($form_state->getValue('name'))

          . "&lastname=" . urlencode($form_state->getValue('surname'))

          . "&email=" . urlencode($form_state->getValue('email'))

          . "&hs_context=" . urlencode($hs_context_json);

//replace the values in this URL with your portal ID and your form GUID
      $endpoint = 'https://forms.hubspot.com/uploads/form/v2/{portalId}/{formGuid}';

      $ch = @curl_init();
      @curl_setopt($ch, CURLOPT_POST, true);
      @curl_setopt($ch, CURLOPT_POSTFIELDS, $str_post);
      @curl_setopt($ch, CURLOPT_URL, $endpoint);
      @curl_setopt($ch, CURLOPT_HTTPHEADER, array(
          'Content-Type: application/x-www-form-urlencoded'
      ));
      @curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      $response    = @curl_exec($ch); //Log the response from HubSpot as needed.
      $status_code = @curl_getinfo($ch, CURLINFO_HTTP_CODE); //Log the response status code
      @curl_close($ch);


      return $status_code . " " . $response;
  }

}
