<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= isset($title) ? $title : 'DLH Banjarmasin' ?></title>

  <link href="<?= BASE_URL ?>assets/css/bootstrap.min.css" rel="stylesheet">
  <link href="<?= BASE_URL ?>assets/bootstrap-icons/bootstrap-icons.min.css" rel="stylesheet">
  <style>
    body {
      font-family: system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
      background-color: #f4f6f9;
      overflow-x: hidden;
    }

    /* Sidebar */
    .sidebar {
      min-height: 100vh;
      width: 260px;
      background-color: #1a5d35;
      position: fixed;
      top: 0;
      left: 0;
      z-index: 100;
      transition: all 0.3s;
    }

    .sidebar .logo-area {
      padding: 20px;
      text-align: center;
      border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    }

    .sidebar .logo-area img {
      width: 50px;
      margin-bottom: 10px;
    }

    .sidebar .logo-area h5 {
      color: #fff;
      font-size: 14px;
      font-weight: 700;
      margin: 0;
      line-height: 1.5;
    }

    .sidebar .nav-link {
      color: rgba(255, 255, 255, 0.8);
      padding: 12px 20px;
      font-size: 15px;
      border-left: 4px solid transparent;
    }

    .sidebar .nav-link:hover {
      background-color: rgba(255, 255, 255, 0.1);
      color: #fff;
    }

    .sidebar .nav-link.active {
      background-color: rgba(255, 255, 255, 0.15);
      color: #fff;
      border-left-color: #ffc107;
    }

    .sidebar .nav-link i {
      width: 25px;
      text-align: center;
      margin-right: 10px;
    }

    /* Main Content */
    .main-content {
      margin-left: 260px;
      padding: 20px;
      transition: all 0.3s;
    }

    .top-navbar {
      background: #fff;
      padding: 10px 20px;
      border-radius: 5px;
      box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
      margin-bottom: 25px;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    /* Cards */
    .stat-card {
      border: none;
      border-radius: 8px;
      color: #fff;
      position: relative;
      overflow: hidden;
      height: 120px;
      display: flex;
      align-items: center;
      padding: 0 20px;
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    .stat-card h3 {
      font-size: 36px;
      font-weight: bold;
      margin: 0;
    }

    .stat-card p {
      font-size: 14px;
      text-transform: uppercase;
      font-weight: 600;
      margin-bottom: 5px;
      opacity: 0.9;
    }

    .stat-card .icon-bg {
      position: absolute;
      right: 20px;
      top: 50%;
      transform: translateY(-50%);
      font-size: 60px;
      opacity: 0.4;
    }

    /* Colors */
    .bg-blue {
      background: #0d6efd;
    }

    .bg-yellow {
      background: #ffc107;
      color: #fff;
    }

    .bg-green {
      background: #198754;
    }

    .bg-cyan {
      background: #0dcaf0;
      color: #fff;
    }

    .bg-indigo {
      background: #6610f2;
      color: #fff;
    }
  </style>
</head>

<body>