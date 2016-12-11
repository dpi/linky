<?php

namespace Drupal\Tests\linky\Functional;

use Drupal\Core\Link;
use Drupal\Core\Url;
use Drupal\KernelTests\KernelTestBase;
use Drupal\linky\Entity\Linky;

/**
 * Tests Linky entity functionality.
 *
 * @group linky
 * @coversDefaultClass \Drupal\linky\Entity\Linky
 */
class LinkyKernelTest extends KernelTestBase {

  /**
   * Modules to enable.
   *
   * @var array
   */
  public static $modules = ['linky', 'link', 'dynamic_entity_reference', 'user'];

  /**
   * {@inheritdoc}
   */
  protected function setUp() {
    parent::setUp();
    $this->installEntitySchema('user');
    $this->installEntitySchema('linky');
  }

  /**
   * Tests basic entity functions.
   *
   * @covers ::toUrl
   * @covers ::label
   * @covers ::toLink
   */
  public function testLinkyEntity() {
    $link = Linky::create([
      'link' => [
        'uri' => 'http://example.com',
        'title' => 'Example.com',
      ],
    ]);
    $link->save();
    $this->assertEquals('Example.com (http://example.com)', $link->label());
    $this->assertEquals(Url::fromUri('http://example.com'), $link->toUrl());
    $edit_url = Url::fromRoute('entity.linky.edit_form', ['linky' => $link->id()]);
    $edit_url
      ->setOption('entity_type', 'linky')
      ->setOption('entity', $link)
      ->setOption('language', $link->language());
    $this->assertEquals($edit_url, $link->toUrl('edit-form'));
    $this->assertEquals(new Link('Example.com', Url::fromUri('http://example.com')), $link->toLink());
    $this->assertEquals(new Link('Edit', $edit_url), $link->toLink('Edit', 'edit-form'));
  }

}

// Global constants hack.
if (!defined('DRUPAL_OPTIONAL')) {
  define('DRUPAL_DISABLED', 0);
  define('DRUPAL_OPTIONAL', 1);
  define('DRUPAL_REQUIRED', 2);
}
