<?php namespace RockTags;

use ProcessWire\Page;
use ProcessWire\RockMigrations;

class Tag extends Page {

  const tpl = 'rocktags_tag';
  const prefix = 'rocktags_tag_';

  public function init() {
    /** @var RockMigrations $rm */
    $rm = $this->wire->modules->get('RockMigrations');
    $rm->watch($this, false);
    $rm->setPageNameFromTitle(self::tpl);
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
          'pageClass' => '\RockTags\Tag',
          'fields' => [
            'title',
          ],
        ],
      ],
    ]);
  }

}
