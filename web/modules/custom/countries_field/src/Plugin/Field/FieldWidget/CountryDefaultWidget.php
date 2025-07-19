<?php

namespace Drupal\countries_field\Plugin\Field\FieldWidget;

use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\WidgetBase;
use Drupal\Core\Form\FormStateInterface;
use GuzzleHttp\Client;

/**
 * Plugin implementation of the 'country_default' widget.
 *
 * @FieldWidget(
 *   id = "country_default",
 *   label = @Translation("Country select"),
 *   field_types = {
 *     "country"
 *   }
 * )
 */
class CountryDefaultWidget extends WidgetBase {

  /**
   * {@inheritdoc}
   */
  public function formElement(FieldItemListInterface $items, $delta, array $element, array &$form, FormStateInterface $form_state) {
    $client = \Drupal::httpClient();
    $countries = [];
    
    try {
      $response = $client->get('https://api.sampleapis.com/countries/countries');
      $data = json_decode($response->getBody(), TRUE);
      
      foreach ($data as $country) {
        $countries[$country['name']] = $country['name'];
      }
    }
    catch (\Exception $e) {
      \Drupal::logger('countries_field')->error('Failed to fetch countries: @error', ['@error' => $e->getMessage()]);
      $countries = [];
    }

    $element['country_name'] = [
      '#type' => 'select',
      '#title' => $this->t('Country'),
      '#options' => $countries,
      '#empty_option' => $this->t('- Select a country -'),
      '#default_value' => isset($items[$delta]->country_name) ? $items[$delta]->country_name : NULL,
      '#ajax' => [
        'callback' => '::updateCountryData',
        'wrapper' => 'country-data-wrapper-' . $delta,
      ],
    ];

    $element['capital'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Capital'),
      '#default_value' => isset($items[$delta]->capital) ? $items[$delta]->capital : '',
      '#attributes' => ['readonly' => 'readonly'],
    ];

    $element['currency'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Currency'),
      '#default_value' => isset($items[$delta]->currency) ? $items[$delta]->currency : '',
      '#attributes' => ['readonly' => 'readonly'],
    ];

    $element['#prefix'] = '<div id="country-data-wrapper-' . $delta . '">';
    $element['#suffix'] = '</div>';

    return $element;
  }

  /**
   * Ajax callback to update country data.
   */
  public function updateCountryData(array &$form, FormStateInterface $form_state) {
    $trigger = $form_state->getTriggeringElement();
    $delta = $trigger['#parents'][1];
    $country_name = $form_state->getValue($trigger['#parents']);

    $client = \Drupal::httpClient();
    
    try {
      $response = $client->get('https://api.sampleapis.com/countries/countries');
      $data = json_decode($response->getBody(), TRUE);
      
      foreach ($data as $country) {
        if ($country['name'] === $country_name) {
          $element = $form['field_country'][$delta];
          $element['capital']['#value'] = $country['capital'] ?? '';
          $element['currency']['#value'] = $country['currency'] ?? '';
          return $element;
        }
      }
    }
    catch (\Exception $e) {
      \Drupal::logger('countries_field')->error('Failed to fetch country data: @error', ['@error' => $e->getMessage()]);
    }

    return $form['field_country'][$delta];
  }
}