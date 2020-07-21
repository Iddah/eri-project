<?php

use Drupal\Component\Serialization\Yaml;
use Drupal\block_content\Entity\BlockContent;

function createBlockContent() {
  $blocks = Yaml::decode(file_get_contents(__DIR__ . '/block.content.yml'));
  foreach ($blocks as $uuid => $data) {
    $existing = \Drupal::entityTypeManager()->getStorage('block_content')->loadByProperties(['uuid' => $uuid]);
    if (!empty($existing)) {
      /** @var BlockContent $entity */
      $entity = reset($existing);
      $entity->set('info', $data['title']);
      $entity->set('body', ['value' => $data['body'], 'format' => 'full_html']);
      $entity->save();
      continue;
    }


    $block = BlockContent::create([
      'info' => $data['title'],
      'type' => 'basic',
      'langcode' => 'en',
      'body' => [
        'value' => $data['body'],
        'format' => 'full_html',
      ],
      'id' => $data['id'],
      'uuid' => $uuid,
    ]);
    $block->save();
  }
}

createBlockContent();