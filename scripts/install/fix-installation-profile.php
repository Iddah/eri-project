<?php

\Drupal::configFactory()->getEditable('core.extension')
  ->set('profile', 'minimal')
  ->save();
drupal_flush_all_caches();

\Drupal::service('module_installer')->install(['minimal']);
\Drupal::service('module_installer')->uninstall(['standard']);

$sc = \Drupal::keyValue('system.schema');
if ($sc->get('standard')) {
  $sc->delete('standard');
}
drupal_flush_all_caches();