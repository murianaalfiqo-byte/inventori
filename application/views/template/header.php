<?php /* Filename: application/views/template/header.php */ ?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= $title; ?> | Inventory POS</title>
  
  <!-- Google Fonts: Inter -->
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <!-- FontAwesome 6 -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <!-- AdminLTE 3 CSS -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
  <!-- jQuery -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

  <style>
      :root {
          --bg-main: #0f172a;
          --card-bg: #1e293b;
          --border-color: rgba(255, 255, 255, 0.08);
          --text-main: #f8fafc;
          --text-muted: #94a3b8;
          --accent: #3b82f6;
          --accent-hover: #2563eb;
      }

      body {
          font-family: 'Inter', sans-serif !important;
          background-color: var(--bg-main) !important;
          color: var(--text-main) !important;
      }

      /* Navbar Modern */
      .main-header.navbar {
          background: rgba(15, 23, 42, 0.9) !important;
          backdrop-filter: blur(10px);
          border-bottom: 1px solid var(--border-color) !important;
      }
      .main-header .nav-link {
          color: var(--text-muted) !important;
          transition: 0.2s;
      }
      .main-header .nav-link:hover {
          color: var(--text-main) !important;
      }

      /* Content Wrapper */
      .content-wrapper {
          background-color: var(--bg-main) !important;
          padding: 20px;
      }
      .content-header h1 {
          font-weight: 600;
          color: var(--text-main);
          font-size: 22px;
          letter-spacing: -0.5px;
      }

      /* Modern Cards */
      .card {
          background-color: var(--card-bg) !important;
          border: 1px solid var(--border-color) !important;
          border-radius: 16px !important;
          box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.3) !important;
          color: var(--text-main) !important;
          margin-bottom: 24px;
      }
      .card-header {
          background: transparent !important;
          border-bottom: 1px solid var(--border-color) !important;
          padding: 20px 24px;
          font-weight: 600;
      }
      .card-body {
          padding: 24px !important;
      }

      /* Tables Modern */
      .table {
          color: var(--text-main) !important;
          background: transparent !important;
      }
      .table th {
          border-top: none !important;
          border-bottom: 1px solid var(--border-color) !important;
          color: var(--text-muted);
          font-weight: 500;
          font-size: 13px;
          text-transform: uppercase;
          letter-spacing: 0.5px;
      }
      .table td {
          border-color: var(--border-color) !important;
          vertical-align: middle !important;
          font-size: 14px;
      }
      .table-bordered td, .table-bordered th {
          border: 1px solid var(--border-color) !important;
      }

      /* Buttons Modern */
      .btn-primary {
          background-color: var(--accent) !important;
          border-color: var(--accent) !important;
          border-radius: 10px !important;
          font-weight: 500;
          padding: 8px 16px;
          transition: all 0.2s ease;
      }
      .btn-primary:hover {
          background-color: var(--accent-hover) !important;
          transform: translateY(-1px);
      }
      .btn-success { background-color: #10b981 !important; border-color: #10b981 !important; border-radius: 10px !important; }
      .btn-danger { background-color: #ef4444 !important; border-color: #ef4444 !important; border-radius: 10px !important; }
      .btn-warning { background-color: #f59e0b !important; border-color: #f59e0b !important; border-radius: 10px !important; color: #fff !important; }
      .btn-info { background-color: #06b6d4 !important; border-color: #06b6d4 !important; border-radius: 10px !important; }

      /* Inputs Modern */
      .form-control {
          background-color: rgba(15, 23, 42, 0.6) !important;
          border: 1px solid var(--border-color) !important;
          color: var(--text-main) !important;
          border-radius: 10px !important;
          padding: 10px 14px;
      }
      .form-control:focus {
          border-color: var(--accent) !important;
          box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.15) !important;
          background-color: rgba(15, 23, 42, 0.9) !important;
      }
      label {
          font-weight: 500;
          font-size: 13px;
          color: var(--text-muted);
          margin-bottom: 8px;
      }

      /* Small Boxes Dashboard */
      .small-box {
          border-radius: 16px !important;
          overflow: hidden;
          box-shadow: 0 10px 25px -5px rgba(0,0,0,0.3) !important;
          border: 1px solid var(--border-color);
          background: var(--card-bg) !important;
      }
      .small-box .inner { padding: 24px; }
      .small-box h3 { font-weight: 700; font-size: 28px; }
      .small-box p { color: var(--text-muted); font-size: 14px; margin-bottom: 0; }
      .small-box .icon { color: rgba(255,255,255,0.05) !important; right: 20px !important; top: 15px !important; font-size: 60px !important; }

      /* Footer */
      .main-footer {
          background: var(--bg-main) !important;
          border-top: 1px solid var(--border-color) !important;
          color: var(--text-muted) !important;
          font-size: 13px;
      }
  </style>
</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper">
  <nav class="main-header navbar navbar-expand navbar-dark">
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
      </li>
    </ul>
    <ul class="navbar-nav ml-auto">
      <li class="nav-item">
        <a class="nav-link" href="<?= site_url('auth/logout') ?>">
          <i class="fas fa-sign-out-alt mr-1"></i> Logout
        </a>
      </li>
    </ul>
  </nav>