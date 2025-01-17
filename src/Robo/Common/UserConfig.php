<?php

namespace Acquia\Blt\Robo\Common;

use Acquia\Blt\Robo\Blt;

/**
 * Store user preferences.
 */
class UserConfig {

  /**
   * User configuration file path.
   *
   * @var string
   */
  private $configPath;

  /**
   * User configuration.
   *
   * @var array
   */
  private $config;

  /**
   * UserConfig constructor.
   *
   * @param string $configDir
   *   Directory to store user config.
   */
  public function __construct(string $configDir) {
    $this->configPath = $configDir . DIRECTORY_SEPARATOR . 'user.json';
    if (file_exists($this->configPath)) {
      $this->config = json_decode(file_get_contents($this->configPath), TRUE);
    }
    else {
      mkdir($configDir, 0777, TRUE);
      $this->setTelemetryUserData();
    }
  }

  /**
   * Check if telemetry preferences are set.
   *
   * @return bool
   *   TRUE if preferences set, FALSE otherwise.
   */
  public function isTelemetrySet() {
    return isset($this->config['telemetry']);
  }

  /**
   * Check if telemetry is enabled.
   *
   * @return bool
   *   TRUE if enabled, FALSE otherwise.
   */
  public function isTelemetryEnabled() {
    return isset($this->config['telemetry']) ? $this->config['telemetry'] : FALSE;
  }

  /**
   * Enable or disable telemetry.
   *
   * @param bool $enabled
   *   Whether to enable or disable telemetry.
   */
  public function setTelemetryEnabled(bool $enabled) {
    $this->config['telemetry'] = $enabled;
    $this->save();
  }

  /**
   * Get telemetry user data.
   *
   * @return array
   *   Telemetry user data.
   */
  public function getTelemetryUserData() {
    $data = $this->config['telemetryUserData'];
    $data['app_version'] = Blt::VERSION;

    return $data;
  }

  /**
   * Initialize telemetry user data.
   */
  public function setTelemetryUserData() {
    $this->config['telemetryUserData'] = [
      'platform' => EnvironmentDetector::getPlatform(),
      'os_name' => EnvironmentDetector::getOsName(),
      'os_version' => EnvironmentDetector::getOsVersion(),
    ];
    $this->save();
  }

  /**
   * Write user config to disk.
   */
  public function save() {
    file_put_contents($this->configPath, json_encode($this->config));
  }

}
