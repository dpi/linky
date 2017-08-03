<?php

namespace Drupal\Tests\linky\Functional;

use Drupal\filter\Entity\FilterFormat;
use Drupal\linky\Entity\Linky;
use Drupal\Tests\token\Kernel\KernelTestBase;

/**
 * Tests linky filter.
 *
 * @group linky
 */
class DirectLinkFilterTest extends KernelTestBase {

  /**
   * {@inheritdoc}
   */
  public static $modules = [
    'linky',
    'filter',
    'link',
    'dynamic_entity_reference',
  ];

  /**
   * {@inheritdoc}
   */
  protected function setUp() {
    parent::setUp();
    $this->installEntitySchema('linky');
    $this->filter = FilterFormat::create([
      'format' => 'test_format',
      'name' => $this->randomMachineName(),
    ]);
    $this->filter->setFilterConfig('linky_direct_link_filter', ['status' => 1]);
    $this->filter->save();
  }

  /**
   * Test the product filter.
   *
   * @dataProvider providerLinkyFilter
   */
  public function testLinkyFilter($uri, $expected) {
    $link = Linky::create([
      'link' => [
        'uri' => $uri,
        'title' => 'link title',
      ],
    ]);
    $link->save();
    $content = '<a href="' . $link->toUrl()->toString() . '">A link</a>';
    $filtered_markup = check_markup($content, 'test_format');
    $this->assertEquals(trim($expected), trim($filtered_markup));
  }

  /**
   * Test cases for the linky filter.
   */
  public function providerLinkyFilter() {
    return [
      'external' => ['http://example.com', '<a href="http://example.com">A link</a>'],
      'internal' => ['internal:/node', '<a href="/node">A link</a>'],
    ];
  }

}
