<?php

/**
 * @file
 * Contains \Drupal\ban\BanIpManager.
 */

namespace Drupal\ban;

use Drupal\Core\Database\Connection;

/**
 * Ban IP manager.
 */
class BanIpManager implements BanIpManagerInterface {

  /**
   * The database connection used to check the IP against.
   *
   * @var \Drupal\Core\Database\Connection
   */
  protected $connection;

  /**
   * Construct the BanSubscriber.
   *
   * @param \Drupal\Core\Database\Connection $connection
   *   The database connection which will be used to check the IP against.
   */
  public function __construct(Connection $connection) {
    $this->connection = $connection;
  }

  /**
   * {@inheritdoc}
   */
  public function isBanned($ip) {
    return (bool) $this->connection->query("SELECT * FROM {ban_ip} WHERE ip = :ip", array(':ip' => $ip))->fetchField();
  }

  /**
   * {@inheritdoc}
   */
  public function findAll() {
    return $this->connection->query('SELECT * FROM {ban_ip}');
  }

  /**
   * {@inheritdoc}
   */
  public function banIp($ip) {
    $this->connection->insert('ban_ip')
      ->fields(array('ip' => $ip))
      ->execute();
  }

  /**
   * {@inheritdoc}
   */
  public function unbanIp($id) {
    $this->connection->delete('ban_ip')
      ->condition('ip', $id)
      ->execute();
  }

  /**
   * {@inheritdoc}
   */
  public function findById($ban_id) {
    return $this->connection->query("SELECT ip FROM {ban_ip} WHERE iid = :iid", array(':iid' => $ban_id))->fetchField();
  }
}
