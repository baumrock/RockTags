<?php

namespace RockTags;

use ProcessWire\LanguagesPageFieldValue;
use ProcessWire\LanguagesValueInterface;
use ProcessWire\Notice;
use ProcessWire\Page;
use ProcessWire\ProcessPageEdit;
use ProcessWire\RockMigrations;
use RockMigrations\MagicPage;

class Tag extends Page
{
  use MagicPage;

  const tpl = 'rocktags_tag';
  const prefix = 'rocktags_tag_';

  public function init()
  {
    /** @var RockMigrations $rm */
    $rm = $this->wire->modules->get('RockMigrations');
    $rm->setPageNameFromTitle(self::tpl);
  }

  public function ready()
  {
    $this->checkTagTranslations();
  }

  /** ##### magic ##### */

  public function onCreate()
  {
    $this->status = 1;
  }

  /** ##### frontend ##### */

  /** ##### backend ##### */

  public function checkTagTranslations()
  {
    if (!$langs = $this->wire->languages) return;
    if ($this->wire->page->template != 'admin') return;
    if ($this->wire->config->ajax) return;

    $id = 0;
    $p = $this->wire->process;
    if ($p == 'ProcessPageView' and $p->getPage()->id == 10) {
      $id = $this->wire->input->get('id', 'int');
    }

    $pages = $this->wire->pages->find([
      'template' => self::tpl,
      'has_parent' => '/rocktags',
      ['id', '!=', $id],
    ]);
    foreach ($pages as $tag) {
      $values = $tag->getUnformatted('title');
      if (!$values instanceof LanguagesPageFieldValue) continue;
      $values = $values->getArray();
      foreach ($langs as $lang) {
        if ($values[$lang->id]) continue;
        $link = "<a href='{$tag->editUrl()}'>#{$tag->name}</a>";
        $this->warning("Missing tag translation for tag $link", Notice::allowMarkup);
      }
    }
  }

  public function migrate()
  {
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
          'noShortcut' => 1, // dont show in add new menu
        ],
      ],
    ]);
  }
}
