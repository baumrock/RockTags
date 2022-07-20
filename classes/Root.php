<?php namespace RockTags;

use ProcessWire\Page;
use ProcessWire\RockMigrations;

class Root extends Page {

  const tpl = 'rocktags_root';
  const prefix = 'rocktags_root_';

  public function init() {
    /** @var RockMigrations $rm */
    $rm = $this->wire->modules->get('RockMigrations');
    $rm->watch($this, false);
  }

  public function migrate() {
    /** @var RockMigrations $rm */
    $rm = $this->wire->modules->get('RockMigrations');
    $rm->migrate([
      'fields' => [],
      'templates' => [
        self::tpl => [
          'pageClass' => '\RockTags\Root',
          'fields' => [
            'title',
          ],
          'noParents' => -1,
        ],
      ],
    ]);
    $rm->createPage("RockTags", "rocktags", self::tpl, 1, ['hidden', 'locked']);
  }

}
