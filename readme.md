# RockTags

## List all tags

```php
foreach($rocktags->tags('your-parent-name') as $tag) {
  echo "<a href='tag-{$tag->name}'>{$tag->title}</a>";
}
```
