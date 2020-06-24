<?php
// @codingStandardsIgnoreFile

/**
 * @file
 * Contains database additions for testing linky revisionable update path.
 *
 * @depends core/modules/system/tests/fixtures/update/drupal-8.6.0.bare.testing.php.gz
 */

use Drupal\Core\Database\Database;

$connection = Database::getConnection();

// Install linky schema.
$connection->schema()->createTable('linky', array(
  'fields' => array(
    'id' => array(
      'type' => 'serial',
      'not null' => TRUE,
      'size' => 'normal',
    ),
    'uuid' => array(
      'type' => 'varchar_ascii',
      'not null' => TRUE,
      'length' => '128',
    ),
    'langcode' => array(
      'type' => 'varchar_ascii',
      'not null' => TRUE,
      'length' => '12',
    ),
    'user_id' => array(
      'type' => 'int',
      'not null' => TRUE,
      'size' => 'normal',
      'unsigned' => TRUE,
    ),
    'link__uri' => array(
      'type' => 'varchar',
      'not null' => FALSE,
      'length' => '2048',
    ),
    'link__title' => array(
      'type' => 'varchar',
      'not null' => FALSE,
      'length' => '255',
    ),
    'link__options' => array(
      'type' => 'blob',
      'not null' => FALSE,
      'size' => 'big',
    ),
    'created' => array(
      'type' => 'int',
      'not null' => FALSE,
      'size' => 'normal',
    ),
    'changed' => array(
      'type' => 'int',
      'not null' => FALSE,
      'size' => 'normal',
    ),
    'checked' => array(
      'type' => 'int',
      'not null' => FALSE,
      'size' => 'normal',
    ),
  ),
  'primary key' => array(
    'id',
  ),
  'unique keys' => array(
    'linky_field__uuid__value' => array(
      'uuid',
    ),
  ),
  'indexes' => array(
    'linky_field__user_id__target_id' => array(
      'user_id',
    ),
    'linky_field__link__uri' => array(
      array(
        'link__uri',
        '30',
      ),
    ),
  ),
  'mysql_character_set' => 'utf8mb4',
));

// Create linky entities.
$connection->insert('linky')
->fields(array(
  'id',
  'uuid',
  'langcode',
  'user_id',
  'link__uri',
  'link__title',
  'link__options',
  'created',
  'changed',
  'checked',
))
->values(array(
  'id' => '1',
  'uuid' => '2de39916-bdfe-4c19-a669-33c4569040e4',
  'langcode' => 'en',
  'user_id' => '0',
  'link__uri' => 'http://example.com',
  'link__title' => 'Example.com',
  'link__options' => 'a:0:{}',
  'created' => '1558532788',
  'changed' => '1558532958',
  'checked' => '0',
))
->values(array(
  'id' => '2',
  'uuid' => '687ca79e-6fc3-42ea-860d-61de80ccf223',
  'langcode' => 'en',
  'user_id' => '1',
  'link__uri' => 'internal:/user/1',
  'link__title' => 'Admin',
  'link__options' => 'a:0:{}',
  'created' => '1558532888',
  'changed' => '1558532968',
  'checked' => '0',
))
->values(array(
  'id' => '3',
  'uuid' => '14892d6f-25ae-4984-a088-a21be6c0a47b',
  'langcode' => 'en',
  'user_id' => '0',
  'link__uri' => 'http://hello.world/kapoww',
  'link__title' => 'Hello World!!!',
  'link__options' => 'a:2:{s:5:"query";a:1:{s:3:"foo";s:3:"bar";}s:8:"fragment";s:3:"baz";}',
  'created' => '1558532908',
  'changed' => '1558532978',
  'checked' => '0',
))
->execute();

// Install linky module, enttity type and fields with all the dependencies.
$connection->insert('key_value')
->fields(array(
  'collection',
  'name',
  'value',
))
->values(array(
  'collection' => 'entity.definitions.installed',
  'name' => 'linky.entity_type',
  'value' => 'O:36:"Drupal\Core\Entity\ContentEntityType":42:{s:25:" * revision_metadata_keys";a:1:{s:16:"revision_default";s:16:"revision_default";}s:31:" * requiredRevisionMetadataKeys";a:1:{s:16:"revision_default";s:16:"revision_default";}s:15:" * static_cache";b:1;s:15:" * render_cache";b:1;s:19:" * persistent_cache";b:1;s:14:" * entity_keys";a:10:{s:2:"id";s:2:"id";s:5:"label";s:11:"link__title";s:4:"uuid";s:4:"uuid";s:3:"uid";s:7:"user_id";s:8:"langcode";s:8:"langcode";s:6:"status";s:6:"status";s:8:"revision";s:0:"";s:6:"bundle";s:0:"";s:16:"default_langcode";s:16:"default_langcode";s:29:"revision_translation_affected";s:29:"revision_translation_affected";}s:5:" * id";s:5:"linky";s:16:" * originalClass";s:25:"Drupal\linky\Entity\Linky";s:11:" * handlers";a:7:{s:12:"view_builder";s:35:"Drupal\linky\LinkyEntityViewBuilder";s:12:"list_builder";s:29:"Drupal\linky\LinkyListBuilder";s:10:"views_data";s:34:"Drupal\linky\Entity\LinkyViewsData";s:4:"form";a:4:{s:7:"default";s:27:"Drupal\linky\Form\LinkyForm";s:3:"add";s:27:"Drupal\linky\Form\LinkyForm";s:4:"edit";s:27:"Drupal\linky\Form\LinkyForm";s:6:"delete";s:42:"Drupal\Core\Entity\ContentEntityDeleteForm";}s:6:"access";s:38:"Drupal\linky\LinkyAccessControlHandler";s:14:"route_provider";a:1:{s:4:"html";s:35:"Drupal\linky\LinkyHtmlRouteProvider";}s:7:"storage";s:46:"Drupal\Core\Entity\Sql\SqlContentEntityStorage";}s:19:" * admin_permission";s:25:"administer linky entities";s:25:" * permission_granularity";s:11:"entity_type";s:8:" * links";a:5:{s:9:"canonical";s:28:"/admin/content/linky/{linky}";s:8:"add-form";s:24:"/admin/content/linky/add";s:9:"edit-form";s:33:"/admin/content/linky/{linky}/edit";s:11:"delete-form";s:35:"/admin/content/linky/{linky}/delete";s:10:"collection";s:20:"/admin/content/linky";}s:17:" * label_callback";N;s:21:" * bundle_entity_type";N;s:12:" * bundle_of";N;s:15:" * bundle_label";N;s:13:" * base_table";s:5:"linky";s:22:" * revision_data_table";N;s:17:" * revision_table";N;s:13:" * data_table";N;s:11:" * internal";b:0;s:15:" * translatable";b:0;s:19:" * show_revision_ui";b:0;s:8:" * label";O:48:"Drupal\Core\StringTranslation\TranslatableMarkup":3:{s:9:" * string";s:12:"Managed Link";s:12:" * arguments";a:0:{}s:10:" * options";a:0:{}}s:19:" * label_collection";s:0:"";s:17:" * label_singular";s:0:"";s:15:" * label_plural";s:0:"";s:14:" * label_count";a:0:{}s:15:" * uri_callback";N;s:8:" * group";s:7:"content";s:14:" * group_label";O:48:"Drupal\Core\StringTranslation\TranslatableMarkup":3:{s:9:" * string";s:7:"Content";s:12:" * arguments";a:0:{}s:10:" * options";a:1:{s:7:"context";s:17:"Entity type group";}}s:22:" * field_ui_base_route";s:18:"entity.linky.admin";s:26:" * common_reference_target";b:0;s:22:" * list_cache_contexts";a:0:{}s:18:" * list_cache_tags";a:1:{i:0;s:10:"linky_list";}s:14:" * constraints";a:2:{s:13:"EntityChanged";N;s:26:"EntityUntranslatableFields";N;}s:13:" * additional";a:0:{}s:8:" * class";s:25:"Drupal\linky\Entity\Linky";s:11:" * provider";s:5:"linky";s:14:" * _serviceIds";a:0:{}s:18:" * _entityStorages";a:0:{}s:20:" * stringTranslation";N;}',
))
->values(array(
  'collection' => 'entity.definitions.installed',
  'name' => 'linky.field_storage_definitions',
  'value' => 'a:8:{s:2:"id";O:37:"Drupal\Core\Field\BaseFieldDefinition":5:{s:7:" * type";s:7:"integer";s:9:" * schema";a:4:{s:7:"columns";a:1:{s:5:"value";a:3:{s:4:"type";s:3:"int";s:8:"unsigned";b:0;s:4:"size";s:6:"normal";}}s:11:"unique keys";a:0:{}s:7:"indexes";a:0:{}s:12:"foreign keys";a:0:{}}s:10:" * indexes";a:0:{}s:17:" * itemDefinition";O:51:"Drupal\Core\Field\TypedData\FieldItemDataDefinition":2:{s:18:" * fieldDefinition";r:2;s:13:" * definition";a:2:{s:4:"type";s:18:"field_item:integer";s:8:"settings";a:6:{s:8:"unsigned";b:0;s:4:"size";s:6:"normal";s:3:"min";s:0:"";s:3:"max";s:0:"";s:6:"prefix";s:0:"";s:6:"suffix";s:0:"";}}}s:13:" * definition";a:8:{s:5:"label";O:48:"Drupal\Core\StringTranslation\TranslatableMarkup":3:{s:9:" * string";s:2:"ID";s:12:" * arguments";a:0:{}s:10:" * options";a:0:{}}s:11:"description";O:48:"Drupal\Core\StringTranslation\TranslatableMarkup":3:{s:9:" * string";s:34:"The ID of the Managed Link entity.";s:12:" * arguments";a:0:{}s:10:" * options";a:0:{}}s:9:"read-only";b:1;s:8:"provider";s:5:"linky";s:10:"field_name";s:2:"id";s:11:"entity_type";s:5:"linky";s:6:"bundle";N;s:13:"initial_value";N;}}s:4:"uuid";O:37:"Drupal\Core\Field\BaseFieldDefinition":5:{s:7:" * type";s:4:"uuid";s:9:" * schema";a:4:{s:7:"columns";a:1:{s:5:"value";a:3:{s:4:"type";s:13:"varchar_ascii";s:6:"length";i:128;s:6:"binary";b:0;}}s:11:"unique keys";a:1:{s:5:"value";a:1:{i:0;s:5:"value";}}s:7:"indexes";a:0:{}s:12:"foreign keys";a:0:{}}s:10:" * indexes";a:0:{}s:17:" * itemDefinition";O:51:"Drupal\Core\Field\TypedData\FieldItemDataDefinition":2:{s:18:" * fieldDefinition";r:40;s:13:" * definition";a:2:{s:4:"type";s:15:"field_item:uuid";s:8:"settings";a:3:{s:10:"max_length";i:128;s:8:"is_ascii";b:1;s:14:"case_sensitive";b:0;}}}s:13:" * definition";a:8:{s:5:"label";O:48:"Drupal\Core\StringTranslation\TranslatableMarkup":3:{s:9:" * string";s:4:"UUID";s:12:" * arguments";a:0:{}s:10:" * options";a:0:{}}s:11:"description";O:48:"Drupal\Core\StringTranslation\TranslatableMarkup":3:{s:9:" * string";s:36:"The UUID of the Managed Link entity.";s:12:" * arguments";a:0:{}s:10:" * options";a:0:{}}s:9:"read-only";b:1;s:8:"provider";s:5:"linky";s:10:"field_name";s:4:"uuid";s:11:"entity_type";s:5:"linky";s:6:"bundle";N;s:13:"initial_value";N;}}s:7:"user_id";O:37:"Drupal\Core\Field\BaseFieldDefinition":5:{s:7:" * type";s:16:"entity_reference";s:9:" * schema";a:4:{s:7:"columns";a:1:{s:9:"target_id";a:3:{s:11:"description";s:28:"The ID of the target entity.";s:4:"type";s:3:"int";s:8:"unsigned";b:1;}}s:7:"indexes";a:1:{s:9:"target_id";a:1:{i:0;s:9:"target_id";}}s:11:"unique keys";a:0:{}s:12:"foreign keys";a:0:{}}s:10:" * indexes";a:0:{}s:17:" * itemDefinition";O:51:"Drupal\Core\Field\TypedData\FieldItemDataDefinition":2:{s:18:" * fieldDefinition";r:77;s:13:" * definition";a:2:{s:4:"type";s:27:"field_item:entity_reference";s:8:"settings";a:3:{s:11:"target_type";s:4:"user";s:7:"handler";s:7:"default";s:16:"handler_settings";a:0:{}}}}s:13:" * definition";a:11:{s:5:"label";O:48:"Drupal\Core\StringTranslation\TranslatableMarkup":3:{s:9:" * string";s:11:"Authored by";s:12:" * arguments";a:0:{}s:10:" * options";a:0:{}}s:11:"description";O:48:"Drupal\Core\StringTranslation\TranslatableMarkup":3:{s:9:" * string";s:49:"The user ID of author of the Managed Link entity.";s:12:" * arguments";a:0:{}s:10:" * options";a:0:{}}s:12:"revisionable";b:1;s:22:"default_value_callback";s:41:"Drupal\node\Entity\Node::getCurrentUserId";s:12:"translatable";b:1;s:7:"display";a:2:{s:4:"view";a:2:{s:7:"options";a:3:{s:5:"label";s:6:"hidden";s:4:"type";s:6:"author";s:6:"weight";i:0;}s:12:"configurable";b:1;}s:4:"form";a:2:{s:7:"options";a:3:{s:4:"type";s:29:"entity_reference_autocomplete";s:6:"weight";i:5;s:8:"settings";a:4:{s:14:"match_operator";s:8:"CONTAINS";s:4:"size";s:2:"60";s:17:"autocomplete_type";s:4:"tags";s:11:"placeholder";s:0:"";}}s:12:"configurable";b:1;}}s:8:"provider";s:5:"linky";s:10:"field_name";s:7:"user_id";s:11:"entity_type";s:5:"linky";s:6:"bundle";N;s:13:"initial_value";N;}}s:4:"link";O:37:"Drupal\Core\Field\BaseFieldDefinition":5:{s:7:" * type";s:4:"link";s:9:" * schema";a:4:{s:7:"columns";a:3:{s:3:"uri";a:3:{s:11:"description";s:20:"The URI of the link.";s:4:"type";s:7:"varchar";s:6:"length";i:2048;}s:5:"title";a:3:{s:11:"description";s:14:"The link text.";s:4:"type";s:7:"varchar";s:6:"length";i:255;}s:7:"options";a:4:{s:11:"description";s:41:"Serialized array of options for the link.";s:4:"type";s:4:"blob";s:4:"size";s:3:"big";s:9:"serialize";b:1;}}s:7:"indexes";a:1:{s:3:"uri";a:1:{i:0;a:2:{i:0;s:3:"uri";i:1;i:30;}}}s:11:"unique keys";a:0:{}s:12:"foreign keys";a:0:{}}s:10:" * indexes";a:0:{}s:17:" * itemDefinition";O:51:"Drupal\Core\Field\TypedData\FieldItemDataDefinition":2:{s:18:" * fieldDefinition";r:133;s:13:" * definition";a:2:{s:4:"type";s:15:"field_item:link";s:8:"settings";a:2:{s:5:"title";i:2;s:9:"link_type";i:16;}}}s:13:" * definition";a:9:{s:5:"label";O:48:"Drupal\Core\StringTranslation\TranslatableMarkup":3:{s:9:" * string";s:4:"Link";s:12:" * arguments";a:0:{}s:10:" * options";a:0:{}}s:11:"description";O:48:"Drupal\Core\StringTranslation\TranslatableMarkup":3:{s:9:" * string";s:41:"The location this managed link points to.";s:12:" * arguments";a:0:{}s:10:" * options";a:0:{}}s:8:"required";b:1;s:7:"display";a:2:{s:4:"view";a:1:{s:7:"options";a:2:{s:4:"type";s:4:"link";s:6:"weight";i:-2;}}s:4:"form";a:1:{s:7:"options";a:2:{s:4:"type";s:12:"link_default";s:6:"weight";i:-2;}}}s:8:"provider";s:5:"linky";s:10:"field_name";s:4:"link";s:11:"entity_type";s:5:"linky";s:6:"bundle";N;s:13:"initial_value";N;}}s:8:"langcode";O:37:"Drupal\Core\Field\BaseFieldDefinition":5:{s:7:" * type";s:8:"language";s:9:" * schema";a:4:{s:7:"columns";a:1:{s:5:"value";a:2:{s:4:"type";s:13:"varchar_ascii";s:6:"length";i:12;}}s:11:"unique keys";a:0:{}s:7:"indexes";a:0:{}s:12:"foreign keys";a:0:{}}s:10:" * indexes";a:0:{}s:17:" * itemDefinition";O:51:"Drupal\Core\Field\TypedData\FieldItemDataDefinition":2:{s:18:" * fieldDefinition";r:189;s:13:" * definition";a:2:{s:4:"type";s:19:"field_item:language";s:8:"settings";a:0:{}}}s:13:" * definition";a:8:{s:5:"label";O:48:"Drupal\Core\StringTranslation\TranslatableMarkup":3:{s:9:" * string";s:13:"Language code";s:12:" * arguments";a:0:{}s:10:" * options";a:0:{}}s:11:"description";O:48:"Drupal\Core\StringTranslation\TranslatableMarkup":3:{s:9:" * string";s:46:"The language code for the Managed Link entity.";s:12:" * arguments";a:0:{}s:10:" * options";a:0:{}}s:7:"display";a:1:{s:4:"form";a:2:{s:7:"options";a:2:{s:4:"type";s:15:"language_select";s:6:"weight";i:10;}s:12:"configurable";b:1;}}s:8:"provider";s:5:"linky";s:10:"field_name";s:8:"langcode";s:11:"entity_type";s:5:"linky";s:6:"bundle";N;s:13:"initial_value";N;}}s:7:"created";O:37:"Drupal\Core\Field\BaseFieldDefinition":5:{s:7:" * type";s:7:"created";s:9:" * schema";a:4:{s:7:"columns";a:1:{s:5:"value";a:1:{s:4:"type";s:3:"int";}}s:11:"unique keys";a:0:{}s:7:"indexes";a:0:{}s:12:"foreign keys";a:0:{}}s:10:" * indexes";a:0:{}s:17:" * itemDefinition";O:51:"Drupal\Core\Field\TypedData\FieldItemDataDefinition":2:{s:18:" * fieldDefinition";r:225;s:13:" * definition";a:2:{s:4:"type";s:18:"field_item:created";s:8:"settings";a:0:{}}}s:13:" * definition";a:7:{s:5:"label";O:48:"Drupal\Core\StringTranslation\TranslatableMarkup":3:{s:9:" * string";s:7:"Created";s:12:" * arguments";a:0:{}s:10:" * options";a:0:{}}s:11:"description";O:48:"Drupal\Core\StringTranslation\TranslatableMarkup":3:{s:9:" * string";s:37:"The time that the entity was created.";s:12:" * arguments";a:0:{}s:10:" * options";a:0:{}}s:8:"provider";s:5:"linky";s:10:"field_name";s:7:"created";s:11:"entity_type";s:5:"linky";s:6:"bundle";N;s:13:"initial_value";N;}}s:7:"changed";O:37:"Drupal\Core\Field\BaseFieldDefinition":5:{s:7:" * type";s:7:"changed";s:9:" * schema";a:4:{s:7:"columns";a:1:{s:5:"value";a:1:{s:4:"type";s:3:"int";}}s:11:"unique keys";a:0:{}s:7:"indexes";a:0:{}s:12:"foreign keys";a:0:{}}s:10:" * indexes";a:0:{}s:17:" * itemDefinition";O:51:"Drupal\Core\Field\TypedData\FieldItemDataDefinition":2:{s:18:" * fieldDefinition";r:254;s:13:" * definition";a:2:{s:4:"type";s:18:"field_item:changed";s:8:"settings";a:0:{}}}s:13:" * definition";a:7:{s:5:"label";O:48:"Drupal\Core\StringTranslation\TranslatableMarkup":3:{s:9:" * string";s:7:"Changed";s:12:" * arguments";a:0:{}s:10:" * options";a:0:{}}s:11:"description";O:48:"Drupal\Core\StringTranslation\TranslatableMarkup":3:{s:9:" * string";s:41:"The time that the entity was last edited.";s:12:" * arguments";a:0:{}s:10:" * options";a:0:{}}s:8:"provider";s:5:"linky";s:10:"field_name";s:7:"changed";s:11:"entity_type";s:5:"linky";s:6:"bundle";N;s:13:"initial_value";N;}}s:7:"checked";O:37:"Drupal\Core\Field\BaseFieldDefinition":5:{s:7:" * type";s:9:"timestamp";s:9:" * schema";a:4:{s:7:"columns";a:1:{s:5:"value";a:1:{s:4:"type";s:3:"int";}}s:11:"unique keys";a:0:{}s:7:"indexes";a:0:{}s:12:"foreign keys";a:0:{}}s:10:" * indexes";a:0:{}s:17:" * itemDefinition";O:51:"Drupal\Core\Field\TypedData\FieldItemDataDefinition":2:{s:18:" * fieldDefinition";r:283;s:13:" * definition";a:2:{s:4:"type";s:20:"field_item:timestamp";s:8:"settings";a:0:{}}}s:13:" * definition";a:8:{s:5:"label";O:48:"Drupal\Core\StringTranslation\TranslatableMarkup":3:{s:9:" * string";s:12:"Last checked";s:12:" * arguments";a:0:{}s:10:" * options";a:0:{}}s:11:"description";O:48:"Drupal\Core\StringTranslation\TranslatableMarkup":3:{s:9:" * string";s:40:"The time that the link was last checked.";s:12:" * arguments";a:0:{}s:10:" * options";a:0:{}}s:13:"default_value";a:1:{i:0;a:1:{s:5:"value";i:0;}}s:8:"provider";s:5:"linky";s:10:"field_name";s:7:"checked";s:11:"entity_type";s:5:"linky";s:6:"bundle";N;s:13:"initial_value";N;}}}',
))
->values(array(
  'collection' => 'entity.storage_schema.sql',
  'name' => 'linky.entity_schema_data',
  'value' => 'a:1:{s:5:"linky";a:1:{s:11:"primary key";a:1:{i:0;s:2:"id";}}}',
))
->values(array(
  'collection' => 'entity.storage_schema.sql',
  'name' => 'linky.field_schema_data.changed',
  'value' => 'a:1:{s:5:"linky";a:1:{s:6:"fields";a:1:{s:7:"changed";a:2:{s:4:"type";s:3:"int";s:8:"not null";b:0;}}}}',
))
->values(array(
  'collection' => 'entity.storage_schema.sql',
  'name' => 'linky.field_schema_data.checked',
  'value' => 'a:1:{s:5:"linky";a:1:{s:6:"fields";a:1:{s:7:"checked";a:2:{s:4:"type";s:3:"int";s:8:"not null";b:0;}}}}',
))
->values(array(
  'collection' => 'entity.storage_schema.sql',
  'name' => 'linky.field_schema_data.created',
  'value' => 'a:1:{s:5:"linky";a:1:{s:6:"fields";a:1:{s:7:"created";a:2:{s:4:"type";s:3:"int";s:8:"not null";b:0;}}}}',
))
->values(array(
  'collection' => 'entity.storage_schema.sql',
  'name' => 'linky.field_schema_data.id',
  'value' => 'a:1:{s:5:"linky";a:1:{s:6:"fields";a:1:{s:2:"id";a:4:{s:4:"type";s:3:"int";s:8:"unsigned";b:0;s:4:"size";s:6:"normal";s:8:"not null";b:1;}}}}',
))
->values(array(
  'collection' => 'entity.storage_schema.sql',
  'name' => 'linky.field_schema_data.langcode',
  'value' => 'a:1:{s:5:"linky";a:1:{s:6:"fields";a:1:{s:8:"langcode";a:3:{s:4:"type";s:13:"varchar_ascii";s:6:"length";i:12;s:8:"not null";b:1;}}}}',
))
->values(array(
  'collection' => 'entity.storage_schema.sql',
  'name' => 'linky.field_schema_data.link',
  'value' => 'a:1:{s:5:"linky";a:2:{s:6:"fields";a:3:{s:9:"link__uri";a:4:{s:11:"description";s:20:"The URI of the link.";s:4:"type";s:7:"varchar";s:6:"length";i:2048;s:8:"not null";b:0;}s:11:"link__title";a:4:{s:11:"description";s:14:"The link text.";s:4:"type";s:7:"varchar";s:6:"length";i:255;s:8:"not null";b:0;}s:13:"link__options";a:5:{s:11:"description";s:41:"Serialized array of options for the link.";s:4:"type";s:4:"blob";s:4:"size";s:3:"big";s:9:"serialize";b:1;s:8:"not null";b:0;}}s:7:"indexes";a:1:{s:22:"linky_field__link__uri";a:1:{i:0;a:2:{i:0;s:9:"link__uri";i:1;i:30;}}}}}',
))
->values(array(
  'collection' => 'entity.storage_schema.sql',
  'name' => 'linky.field_schema_data.user_id',
  'value' => 'a:1:{s:5:"linky";a:2:{s:6:"fields";a:1:{s:7:"user_id";a:4:{s:11:"description";s:28:"The ID of the target entity.";s:4:"type";s:3:"int";s:8:"unsigned";b:1;s:8:"not null";b:1;}}s:7:"indexes";a:1:{s:31:"linky_field__user_id__target_id";a:1:{i:0;s:7:"user_id";}}}}',
))
->values(array(
  'collection' => 'entity.storage_schema.sql',
  'name' => 'linky.field_schema_data.uuid',
  'value' => 'a:1:{s:5:"linky";a:2:{s:6:"fields";a:1:{s:4:"uuid";a:4:{s:4:"type";s:13:"varchar_ascii";s:6:"length";i:128;s:6:"binary";b:0;s:8:"not null";b:1;}}s:11:"unique keys";a:1:{s:24:"linky_field__uuid__value";a:1:{i:0;s:4:"uuid";}}}}',
))
->values(array(
  'collection' => 'system.schema',
  'name' => 'dynamic_entity_reference',
  'value' => 'i:8000;',
))
->values(array(
  'collection' => 'system.schema',
  'name' => 'link',
  'value' => 'i:8000;',
))
->values(array(
  'collection' => 'system.schema',
  'name' => 'linky',
  'value' => 'i:8000;',
))
->execute();


// Update core.extension.
$extensions = $connection->select('config')
  ->fields('config', ['data'])
  ->condition('collection', '')
  ->condition('name', 'core.extension')
  ->execute()
  ->fetchField();
$extensions = unserialize($extensions);
$extensions['module']['dynamic_entity_reference'] = 0;
$extensions['module']['link'] = 0;
$extensions['module']['linky'] = 0;
$connection->update('config')
  ->fields([
    'data' => serialize($extensions),
  ])
  ->condition('collection', '')
  ->condition('name', 'core.extension')
  ->execute();
