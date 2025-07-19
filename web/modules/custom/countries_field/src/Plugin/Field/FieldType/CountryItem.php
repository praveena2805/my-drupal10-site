<?php

namespace Drupal\countries_field\Plugin\Field\FieldType;

use Drupal\Core\Field\FieldItemBase;
use Drupal\Core\Field\FieldStorageDefinitionInterface;
use Drupal\Core\TypedData\DataDefinition;

/**
 * Plugin implementation of the 'country' field type.
 *
 * @FieldType(
 *   id = "country",
 *   label = @Translation("Country"),
 *   description = @Translation("This field stores country information."),
 *   default_widget = "country_default",
 *   default_formatter = "country_default"
 * )
 */
class CountryItem extends FieldItemBase {

  /**
   * {@inheritdoc}
   */
  public static function schema(FieldStorageDefinitionInterface $field_definition) {
    return [
      'columns' => [
        'country_name' => [
          'type' => 'varchar',
          'length' => 255,
        ],
        'capital' => [
          'type' => 'varchar',
          'length' => 255,
        ],
        'currency' => [
          'type' => 'varchar',
          'length' => 64,
        ],
      ],
    ];
  }

  /**
   * {@inheritdoc}
   */
  public static function propertyDefinitions(FieldStorageDefinitionInterface $field_definition) {
    $properties['country_name'] = DataDefinition::create('string')
      ->setLabel(t('Country Name'));
    
    $properties['capital'] = DataDefinition::create('string')
      ->setLabel(t('Capital'));
    
    $properties['currency'] = DataDefinition::create('string')
      ->setLabel(t('Currency'));

    return $properties;
  }

  /**
   * {@inheritdoc}
   */
  public function isEmpty() {
    $value = $this->get('country_name')->getValue();
    return $value === NULL || $value === '';
  }
}