<?php

require_once 'vendor/autoload.php';

$db = \Nuntius\Nuntius::getDb();

//$this->db
//  ->getTable('running_context')
//  ->filter(\r\row('user')->eq($this->data['user']))
//  ->run($this->db->getConnection())
//  ->toArray();


$foo = $db->getQuery()->table('superheroes')->condition('age', 18)->execute();
Kint::dump($foo);
/*$rows = $db->getQuery()
  ->table('logger')
  ->condition('name', ['roy', 'david', 'noy'], 'IN')
  ->condition('age', 28, '<')
  ->execute();

\Kint::dump($rows);*/

