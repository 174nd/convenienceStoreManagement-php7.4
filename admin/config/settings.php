<?php
require_once($backurl . 'config/conn.php');
require_once($backurl . 'config/function.php');
$df['home'] = $df['host'] . 'admin/';
kicked("admin");

$setSidebar = array(
  'Dashboard' => array('fas fa-window-restore', $df['home']),
  'Barang' => array('fas fa-pallet', $df['home'] . 'barang/'),
  'Stok' => array('fas fa-truck-loading', $df['home'] . 'stok/'),
  'Satuan' => array('fas fa-infinity', $df['home'] . 'satuan/'),
  'Kategori' => array('fas fa-boxes', $df['home'] . 'kategori/'),
  'Perusahaan' => array('fas fa-building', $df['home'] . 'pt/'),
  'User' => array('fas fa-user', $df['home'] . 'user/'),



  // 'Model Barang' => array('fas fa-boxes', $df['home'] . 'model-barang/'),
  // 'Asset' => array('fas fa-truck-loading', $df['home'] . 'asset/'),
  // 'Ruangan' => array('fas fa-person-booth', $df['home'] . 'ruangan/'),
  // 'Gedung' => array('fas fa-city', $df['home'] . 'gedung/'),
);
