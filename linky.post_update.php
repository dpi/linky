<?php

/**
 * Post update functions for Linky.
 */

use \Drupal\Core\Entity\Sql\SqlContentEntityStorageSchemaConverter;
use Drupal\Core\Field\BaseFieldDefinition;

/**
 * Update Linky to be revisionable.
 */
function linky_post_update_revisionable(&$sandbox) {
  $entityTypeId = 'linky';
  $definitionUpdateManager = \Drupal::entityDefinitionUpdateManager();

  // Add new fields.
  // Add revision field.
  $revisionField = BaseFieldDefinition::create('integer')
    ->setLabel(\t('Revision ID'))
    ->setReadOnly(TRUE)
    ->setSetting('unsigned', TRUE);
  $definitionUpdateManager->installFieldStorageDefinition('revision_id', $entityTypeId, 'linky', $revisionField);

  // Add revision created date field.
  // Cannot copy from other field because complaints of mismatched field types:
  // 'created' versus 'changed'.
  $revisionCreatedField = BaseFieldDefinition::create('created')
    ->setLabel(t('Revision create time'))
    ->setDescription(t('The time that the current revision was created.'))
    ->setRevisionable(TRUE);
  $definitionUpdateManager->installFieldStorageDefinition('revision_created', $entityTypeId, 'linky', $revisionCreatedField);

  // Add revision author field.
  $revisionUserField = BaseFieldDefinition::create('entity_reference')
    ->setLabel(t('Revision user'))
    ->setDescription(t('The user ID of the author of the current revision.'))
    ->setSetting('target_type', 'user')
    ->setRevisionable(TRUE)
    ->setInitialValueFromField('user_id');
  $definitionUpdateManager->installFieldStorageDefinition('revision_uid', $entityTypeId, 'linky', $revisionUserField);

  // Add revision log field.
  $revisionLogMessageField = BaseFieldDefinition::create('string_long')
    ->setLabel(t('Revision log message'))
    ->setDescription(t('Briefly describe the changes you have made.'))
    ->setRevisionable(TRUE)
    ->setDefaultValue('')
    ->setDisplayOptions('form', [
      'type' => 'string_textarea',
      'weight' => 25,
      'settings' => [
        'rows' => 4,
      ],
    ])
    ->setDisplayConfigurable('form', TRUE);
  $definitionUpdateManager->installFieldStorageDefinition('revision_log', $entityTypeId, 'linky', $revisionLogMessageField);
}

/**
 * Do the revision table creation and data migration.
 *
 * This in an isolated separate step because it may be executed many times with
 * sandbox.
 */
function linky_post_update_revisionable_data_migration(&$sandbox) {
  $schemaConverter = new SqlContentEntityStorageSchemaConverter(
    'linky',
    \Drupal::entityTypeManager(),
    \Drupal::entityDefinitionUpdateManager(),
    \Drupal::service('entity.last_installed_schema.repository'),
    \Drupal::keyValue('entity.storage_schema.sql'),
    \Drupal::database()
  );

  $schemaConverter->convertToRevisionable($sandbox, [
    'user_id',
    'link',
    'changed',
  ]);
}

/**
 * Copies value of changed to revision_created.
 *
 * Cannot simply use setInitialValueFromField when installing the field
 * because \Drupal\Core\Entity\Sql\SqlContentEntityStorageSchema::getSharedTableFieldSchema
 * complains about mismatched field types.Instead, simply copy value of
 * 'changed' column to 'revision_created'.
 */
function linky_post_update_revisionable_data_revision_date(&$sandbox) {
  $update = \Drupal::database()->update('linky_revision');
  $update->expression('revision_created', 'changed');
  $update->execute();
}
