<?php

namespace Drupal\Tests\linky\Functional\Update;

use Drupal\FunctionalTests\Update\UpdatePathTestBase;
use Drupal\linky\Entity\Linky;

/**
 * Tests that linky entities revisionable upgrade path works.
 *
 * @see linky_update_8101()
 * @see linky_post_update_make_linky_revisionable()
 * @seelinky_post_update_set_default_revisionable_data()
 * @see https://www.drupal.org/project/linky/issues/3052102
 *
 * @group linky
 * @group Update
 * @group legacy
 */
class LinkyRevisionableUpdateTest extends UpdatePathTestBase {

  /**
   * {@inheritdoc}
   */
  protected static $modules = ['linky', 'link', 'user', 'dynamic_entity_reference'];

  /**
   * {@inheritdoc}
   */
  public function setDatabaseDumpFiles() {
    $this->databaseDumpFiles = [
      DRUPAL_ROOT . '/core/modules/system/tests/fixtures/update/drupal-8.6.0.bare.testing.php.gz',
      __DIR__ . '/../../../fixtures/update/linky-convert-linky-entity-to-revisionable-3052102.php',
    ];
  }

  /**
   * Tests reviosnable upgrade path.
   */
  public function testRevisionableUpgradePath() {
    /* @var \Drupal\Core\Field\FieldStorageDefinitionInterface[] $storage_definitions */
    $storage_definitions = \Drupal::service('entity.last_installed_schema.repository')
      ->getLastInstalledFieldStorageDefinitions('linky');
    // Verify that ID column is signed.
    $schema = $storage_definitions['id']->getSchema();
    $this->assertFalse($schema['columns']['value']['unsigned'], 'ID field not unsigned.');
    // Verify entity is not already revisionable.
    $this->assertArrayNotHasKey('revision_id', $storage_definitions);
    $this->assertArrayNotHasKey('revision_default', $storage_definitions);
    $this->assertArrayNotHasKey('revision_uid', $storage_definitions);
    $this->assertArrayNotHasKey('revision_created', $storage_definitions);
    $this->assertArrayNotHasKey('revision_log', $storage_definitions);
    // Verify existing field are not revisionable.
    $this->assertFalse($storage_definitions['changed']->isRevisionable(), 'Changed field is not revisionable.');
    $this->assertFalse($storage_definitions['link']->isRevisionable(), 'link field is not revisionable.');
    $this->assertFalse($storage_definitions['langcode']->isRevisionable(), 'Langcode field is not revisionable.');
    // Run updates.
    $this->runUpdates();
    // Get the update field storage definitions.
    $storage_definitions = \Drupal::service('entity.last_installed_schema.repository')
      ->getLastInstalledFieldStorageDefinitions('linky');
    // Verify that ID column is unsigned.
    $schema = $storage_definitions['id']->getSchema();
    $this->assertTrue($schema['columns']['value']['unsigned'], 'ID field unsigned.');
    // Verify entity is revisionable.
    $this->assertArrayHasKey('revision_id', $storage_definitions);
    $this->assertArrayHasKey('revision_default', $storage_definitions);
    $this->assertArrayHasKey('revision_uid', $storage_definitions);
    $this->assertArrayHasKey('revision_created', $storage_definitions);
    $this->assertArrayHasKey('revision_log', $storage_definitions);
    // Verify existing field are revisionable.
    $this->assertTrue($storage_definitions['changed']->isRevisionable(), 'Changed field is revisionable.');
    $this->assertTrue($storage_definitions['link']->isRevisionable(), 'link field is revisionable.');
    $this->assertTrue($storage_definitions['langcode']->isRevisionable(), 'Langcode field is revisionable.');
    // Verify default values are copied over.
    /* @var Linky[] $linkies */
    $linkies = Linky::loadMultiple(NULL);
    foreach ($linkies as $linky) {
      $this->assertEquals($linky->user_id->target_id, $linky->revision_uid->target_id);
    }
  }

}
