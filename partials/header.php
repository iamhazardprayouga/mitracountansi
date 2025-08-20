<?php
require_once("lib/config.php");
if (!isset($_SESSION)) session_start();

// Logout
$logoutAction = $_SERVER['PHP_SELF'] . "?doLogout=true";
if ((isset($_GET['doLogout'])) && ($_GET['doLogout'] == "true")) {
    session_destroy();
    header("Location: ../index.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Sistem Informasi Akuntansi</title>

  <!-- Bootstrap 5 -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

  <!-- Icons -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

  <!-- Animations -->
  <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

  <style>
    body {
      background: #f7f9fc;
      font-family: 'Poppins', sans-serif;
    }
    .sidebar {
      height: 100vh;
      background: rgba(15, 23, 42, 0.95);
      backdrop-filter: blur(10px);
      position: fixed;
      top: 0; left: 0;
      width: 250px;
      padding-top: 60px;
      transition: all 0.3s ease;
    }
    .sidebar a {
      color: #fff;
      display: block;
      padding: 12px 20px;
      text-decoration: none;
      transition: 0.3s;
    }
    .sidebar a:hover {
      background: rgba(255,255,255,0.1);
      padding-left: 30px;
    }
    .content {
      margin-left: 250px;
      padding: 30px;
      margin-top: 60px;
    }
    .navbar {
      background: #0f172a;
    }
    .navbar-brand {
      color: #fff !important;
      font-weight: 600;
    }
    .card {
      border: none;
      border-radius: 20px;
      box-shadow: 0 4px 20px rgba(0,0,0,0.05);
      transition: transform 0.3s ease;
    }
    .card:hover {
      transform: translateY(-5px);
    }
  </style>
</head>
<body>
