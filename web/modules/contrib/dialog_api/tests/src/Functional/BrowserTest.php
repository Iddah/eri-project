<?php

namespace Drupal\Tests\dialog_api\Functional;

use Drupal\Core\Url;
use Drupal\Tests\block\Functional\BlockTestBase;

/**
 * Simple test to ensure that main page loads with module enabled.
 *
 * @group dialog_api
 */
class BrowserTest extends BlockTestBase {

  /**
   * Modules to enable.
   *
   * @var array
   */
  public static $modules = ['dialog_api'];

  /**
   * Tests that the home page loads with a 200 response.
   */
  public function testLoad() {
    $this->drupalGet(Url::fromRoute('<front>'));
    $this->assertSession()->statusCodeEquals(200);
  }

  public function testBlock() {
    $block_name = 'system_powered_by_block';
    $title = 'Test powered by';
    // Enable a standard block.
    $default_theme = $this->config('system.theme')->get('default');
    $edit = [
      'id' => 'test_powered_by',
      'region' => DIALOG_API_REGION,
      'settings[label]' => $title,
      'settings[label_display]' => TRUE,
      'visibility[request_path][pages]' => '*',
    ];
    // Set the block to be hidden on any user path, and to be shown only to
    // authenticated users.
    $edit['visibility[request_path][pages]'] = '*';
    $this->drupalGet('admin/structure/block/add/' . $block_name . '/' . $default_theme);
    $this->drupalPostForm(NULL, $edit, t('Save block'));
    $this->drupalGet('');

    $session = $this->assertSession();
    $session->pageTextContainsOnce($title);
    $session->responseContains('<dialog ');
    $session->responseContains('class="dialog-api--dialog"');
    $session->responseContains('dialog-api--dialog--close');
  }

}
