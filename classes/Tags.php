<?php namespace RockTags;

use ProcessWire\Page;

class Tags extends Page {

  const tpl = 'rocktags_tags';
  const prefix = 'rocktags_tags_';

  public function init() {
    /** @var RockMigrations $rm */
    $rm = $this->wire->modules->get('RockMigrations');
    $rm->watch($this, false);
  }

  /** ##### magic ##### */

  public function onCreate() {
    $this->status = 1;
  }

  /** ##### frontend ##### */

  /** ##### backend ##### */

  public function migrate() {
    /** @var RockMigrations $rm */
    $rm = $this->wire->modules->get('RockMigrations');
    $rm->migrate([
      'fields' => [],
      'templates' => [
        self::tpl => [
          'pageClass' => '\RockTags\Tags',
          'fields' => [
            'title',
          ],
          'sortfield' => 'title',
          'parentTemplates' => [
            Root::tpl,
          ],
        ],
      ],
    ]);
  }

}
