<?php

namespace Drupal\countries_field\Plugin\Field\FieldFormatter;

use Drupal\Core\Field\FormatterBase;
use Drupal\Core\Field\FieldItemListInterface;

/**
 * Plugin implementation of the 'country_default' formatter.
 *
 * @FieldFormatter(
 *   id = "country_default",
 *   label = @Translation("Default"),
 *   field_types = {
 *     "country"
 *   }
 * )
 */
class CountryDefaultFormatter extends FormatterBase {

  /**
   * {@inheritdoc}
   */
  public function viewElements(FieldItemListInterface $items, $langcode) {
    $elements = [];

    foreach ($items as $delta => $item) {
      $elements[$delta] = [
        '#theme' => 'country_formatter',
        '#country_name' => $item->country_name,
        
      ];
    }

    return $elements;
  }
}