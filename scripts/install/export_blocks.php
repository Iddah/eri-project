<?php

$blocks = \Drupal::entityTypeManager()->getStorage('block_content')->loadByProperties(['reusable' => TRUE]);
$write = [];

foreach ($blocks as $block) {
  $title = $block->info->value;
  $body = $block->body->value;
  $uuid = $block->uuid();
  $write[$uuid] = [
    'title' => $title,
    'body' => $body,
    'id' => $block->id(),
  ];
}

$yaml = \Symfony\Component\Yaml\Yaml::dump($write);

file_put_contents(__DIR__ . '/block.content.yml', $yaml);