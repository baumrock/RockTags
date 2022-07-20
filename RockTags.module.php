<?php namespace ProcessWire;

use RockTags\Root;
use RockTags\Tag;
use RockTags\Tags;

/**
 * @author Bernhard Baumrock, 20.07.2022
 * @license Licensed under MIT
 * @link https://www.baumrock.com
 */
class RockTags extends WireData implements Module {

  public static function getModuleInfo() {
    return [
      'title' => 'RockTags',
      'version' => '1.0.0',
      'summary' => 'Module to quickly add a multilang tagging system to your site',
      'autoload' => true,
      'singular' => true,
      'icon' => 'filter-o',
      'requires' => [
        'RockMigrations',
      ],
      'installs' => [],
    ];
  }

  public function init() {
    /** @var RockMigrations $rm */
    $rm = $this->wire->modules->get('RockMigrations');
    $rm->watch($this);
    $rm->initClasses(__DIR__."/classes", "RockTags");
  }

  public function migrate() {
    /** @var RockMigrations $rm */
    $rm = $this->wire->modules->get('RockMigrations');

    // first create root
    $root = new Root();
    $root->migrate();

    // then create tags and tag
    $tmp = new Tags();
    $tmp->migrate();
    $tmp = new Tag();
    $tmp->migrate();
    $rm->setParentChild(Tags::tpl, Tag::tpl, false);

    // make sure that we can add tags-pages under root
    $rm->setTemplateData(Root::tpl, [
      'childTemplates' => [Tags::tpl],
      'childNameFormat' => 'title',
    ]);
  }

  public function ___install() {
    $this->init();
    $this->migrate();
  }

}
