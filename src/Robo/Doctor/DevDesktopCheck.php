<?php

namespace Acquia\Blt\Robo\Doctor;

/**
 * BLT Doctor checks for dev desktop.
 */
class DevDesktopCheck extends DoctorCheck {

  /**
   * Perform all checks.
   */
  public function performAllChecks() {
    $this->checkDevDesktop();
  }

  /**
   * Checks that Dev Desktop is configured correctly.
   */
  protected function checkDevDesktop() {
    if ($this->getInspector()->isDevDesktopInitialized()) {
      if (getenv('DEVDESKTOP_DRUPAL_SETTINGS_DIR')) {
        $this->logProblem(__FUNCTION__, [
          "DevDesktop usage is enabled, but \$DEVDESKTOP_DRUPAL_SETTINGS_DIR is not set in your environmental variables.",
          "",
          "Add `export DEVDESKTOP_DRUPAL_SETTINGS_DIR=\"\$HOME/.acquia/DevDesktop/DrupalSettings\"` to ~/.bash_profile or equivalent for your system.`",
        ], 'error');
      }
      elseif (strstr(getenv('DEVDESKTOP_DRUPAL_SETTINGS_DIR'), '~')) {
        $this->logProblem(__FUNCTION__, [
          "\$DEVDESKTOP_DRUPAL_SETTINGS_DIR contains a '~'. This does not always expand to your home directory.",
          "",
          "Add `export DEVDESKTOP_DRUPAL_SETTINGS_DIR=\"\$HOME/.acquia/DevDesktop/DrupalSettings\"` to ~/.bash_profile or equivalent for your system.`",
        ], 'error');
      }

      $variables_order = ini_get('variables_order');
      $php_ini_file = php_ini_loaded_file();
      if (!strstr($variables_order, 'E')) {
        $this->logProblem(__FUNCTION__, [
          "DevDesktop usage is enabled, but variables_order does support environmental variables.",
          "",
          "Define variables_order = \"EGPCS\" in $php_ini_file",
        ], 'error');
      }
    }
  }

}
