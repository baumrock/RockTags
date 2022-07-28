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
      'version' => '1.0.1',
      'summary' => 'Module to quickly add a multilang tagging system to your site',
      'autoload' => true,
      'singular' => true,
      'icon' => 'tags',
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

  /**
   * Return the tag-page by name in the currently set language
   * @return Tag
   */
  public function getTagByName($name, $parent = null) {
    $prop = 'name';
    $lang = $this->wire->user->language;
    if(!$lang->isDefault()) $prop .= $lang;
    $selector = [$prop => $name];
    if($parent) $selector['parent'] = $parent;
    return $this->wire->pages->get($selector);
  }

  /**
   * Get tags from given page
   * @return PageArray
   */
  public function getTags($parentName, $selector = '') {
    $parent = $this->wire->pages->get([
      'parent' => $this->wire->pages->get("/rocktags"),
      'name|id' => $parentName,
    ]);
    return $parent->children($selector);
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

  /**
   * @return Root
   */
  public function rootPage() {
    return $this->wire->pages->get([
      'template' => Root::tpl,
    ]);
  }

  public function ___install() {
    $this->init();
    $this->migrate();
  }

}
