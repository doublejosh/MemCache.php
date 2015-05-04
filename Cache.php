<?php
/**
 * Manage job listing features.
 */

class Cache {

  private $client,
          $lifetime = 900,
          $host = 'localhost',
          $port = '11211';

  /**
   * Instantiate a new cache class.
   *
   * @param array $options
   */
  public function __construct($options) {
    $availOptions = ['host', 'port', 'lifetime'];
    foreach ($availOptions as $o) {
      try {
        if (isset($options[$o])) $this->{$o} = $options[$o];
      }
      catch (Exception $e) {
        var_dump($e->getMessage());
      }
    }
    $this->connect();
  }


  /**
   * Connect to cache backend.
   *
   * @return string|boolean
   */
  private function connect() {
    if (!class_exists('Memcache')) return FALSE;
    $this->client = new Memcache;
    $this->client->connect($this->host, $this->port) or die ("Could not connect to cache.");

    return TRUE;
  }


  /**
   * Set content to a cache backend.
   *
   * @param string $cid
   * @param string $content
   *
   * @return string|boolean
   */
  public function set($cid, $content) {
    $this->client->set($cid, $content, FALSE, $this->lifetime) or die ("Failed to save data to cache.");

    return TRUE;
  }


  /**
   * Get content from a cache backend.
   *
   * @param string $cid
   *
   * @return string|boolean
   */
  public function get($cid) {
    if ($output = $this->client->get($cid)) return $output;

    return FALSE;
  }

}
