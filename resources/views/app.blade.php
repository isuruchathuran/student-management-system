<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="CodXpress Student Management System - Professional Admin Dashboard">
    <title>@stack('page_title', 'Dashboard') | CodXpress</title>

    {{-- Bootstrap 5.3 --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    {{-- Font Awesome 6 --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    {{-- Google Fonts: Inter --}}
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        /* ═══════════════════════════════════════════════════════
           CODXPRESS DARK DESIGN SYSTEM
        ═══════════════════════════════════════════════════════ */
        :root {
            --bg-primary:     #0f172a;
            --bg-secondary:   #1e293b;
            --bg-tertiary:    #273549;
            --bg-card:        #1e293b;
            --accent:         #3b82f6;
            --accent-hover:   #2563eb;
            --accent-light:   rgba(59,130,246,0.15);
            --accent-glow:    rgba(59,130,246,0.3);
            --success:        #10b981;
            --warning:        #f59e0b;
            --danger:         #ef4444;
            --purple:         #8b5cf6;
            --text-primary:   #f1f5f9;
            --text-secondary: #94a3b8;
            --text-muted:     #64748b;
            --border:         rgba(255,255,255,0.07);
            --border-accent:  rgba(59,130,246,0.4);
            --sidebar-w:      260px;
            --topnav-h:       68px;
            --radius:         12px;
            --radius-lg:      16px;
            --shadow:         0 4px 24px rgba(0,0,0,0.4);
            --shadow-lg:      0 8px 40px rgba(0,0,0,0.5);
            --glass:          rgba(255,255,255,0.03);
            --glass-border:   rgba(255,255,255,0.08);
        }

        * { box-sizing: border-box; }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--bg-primary);
            color: var(--text-primary);
            margin: 0;
            overflow-x: hidden;
        }

        /* ─── SIDEBAR ─────────────────────────────────────── */
        #sidebar {
            position: fixed;
            top: 0; left: 0;
            width: var(--sidebar-w);
            height: 100vh;
            background: var(--bg-secondary);
            border-right: 1px solid var(--border);
            display: flex;
            flex-direction: column;
            z-index: 1050;
            transition: transform 0.3s ease;
            overflow-y: auto;
        }

        #sidebar::-webkit-scrollbar { width: 4px; }
        #sidebar::-webkit-scrollbar-track { background: transparent; }
        #sidebar::-webkit-scrollbar-thumb { background: var(--bg-tertiary); border-radius: 4px; }

        .sidebar-brand {
            padding: 20px 24px;
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            gap: 12px;
            min-height: var(--topnav-h);
        }

        .brand-icon {
            width: 40px; height: 40px;
            background: linear-gradient(135deg, var(--accent), var(--purple));
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.1rem;
            color: white;
            font-weight: 700;
            flex-shrink: 0;
            box-shadow: 0 4px 12px var(--accent-glow);
        }

        .brand-text h6 {
            font-size: 0.85rem;
            font-weight: 700;
            color: var(--text-primary);
            margin: 0;
            line-height: 1.2;
            letter-spacing: 0.3px;
        }

        .brand-text small {
            font-size: 0.68rem;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .sidebar-nav {
            padding: 16px 12px;
            flex: 1;
        }

        .nav-section-label {
            font-size: 0.65rem;
            font-weight: 700;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 1.5px;
            padding: 8px 12px 6px;
            margin-top: 8px;
        }

        .sidebar-link {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 10px 14px;
            border-radius: 10px;
            color: var(--text-secondary);
            text-decoration: none;
            font-size: 0.875rem;
            font-weight: 500;
            transition: all 0.2s ease;
            margin-bottom: 2px;
            position: relative;
        }

        .sidebar-link .nav-icon {
            width: 20px;
            text-align: center;
            font-size: 0.9rem;
            flex-shrink: 0;
        }

        .sidebar-link:hover {
            background: var(--accent-light);
            color: var(--accent);
            text-decoration: none;
        }

        .sidebar-link.active {
            background: var(--accent-light);
            color: var(--accent);
            border: 1px solid var(--border-accent);
        }

        .sidebar-link.active::before {
            content: '';
            position: absolute;
            left: -12px;
            top: 50%;
            transform: translateY(-50%);
            width: 3px;
            height: 60%;
            background: var(--accent);
            border-radius: 0 3px 3px 0;
        }

        .sidebar-link.logout-link {
            color: var(--danger);
            margin-top: 8px;
        }

        .sidebar-link.logout-link:hover {
            background: rgba(239,68,68,0.15);
            color: var(--danger);
        }

        .sidebar-footer {
            padding: 16px;
            border-top: 1px solid var(--border);
        }

        .sidebar-user-mini {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .sidebar-user-mini .avatar-sm {
            width: 34px; height: 34px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--accent), var(--purple));
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.8rem;
            color: white;
            font-weight: 700;
            flex-shrink: 0;
        }

        .sidebar-user-mini .user-info span {
            display: block;
            font-size: 0.8rem;
            font-weight: 600;
            color: var(--text-primary);
            line-height: 1.2;
        }

        .sidebar-user-mini .user-info small {
            font-size: 0.7rem;
            color: var(--text-muted);
        }

        /* ─── TOP NAVBAR ─────────────────────────────────── */
        #topnav {
            position: fixed;
            top: 0;
            left: var(--sidebar-w);
            right: 0;
            height: var(--topnav-h);
            background: var(--bg-secondary);
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            padding: 0 28px;
            gap: 16px;
            z-index: 1040;
            transition: left 0.3s ease;
        }

        .topnav-left { flex: 1; display: flex; align-items: center; gap: 16px; }

        .sidebar-toggle {
            background: none;
            border: none;
            color: var(--text-secondary);
            font-size: 1.1rem;
            cursor: pointer;
            padding: 6px 8px;
            border-radius: 8px;
            transition: all 0.2s;
            display: none;
        }

        .sidebar-toggle:hover { background: var(--glass); color: var(--text-primary); }

        .search-bar {
            display: flex;
            align-items: center;
            gap: 10px;
            background: var(--bg-tertiary);
            border: 1px solid var(--border);
            border-radius: 10px;
            padding: 8px 16px;
            max-width: 340px;
            flex: 1;
            transition: border-color 0.2s;
        }

        .search-bar:focus-within {
            border-color: var(--border-accent);
        }

        .search-bar input {
            background: none;
            border: none;
            outline: none;
            color: var(--text-primary);
            font-size: 0.875rem;
            font-family: 'Inter', sans-serif;
            width: 100%;
        }

        .search-bar input::placeholder { color: var(--text-muted); }
        .search-bar .search-icon { color: var(--text-muted); font-size: 0.85rem; }

        .topnav-right { display: flex; align-items: center; gap: 8px; }

        .nav-icon-btn {
            position: relative;
            width: 38px; height: 38px;
            border-radius: 10px;
            background: var(--bg-tertiary);
            border: 1px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--text-secondary);
            cursor: pointer;
            transition: all 0.2s;
            text-decoration: none;
        }

        .nav-icon-btn:hover { background: var(--accent-light); color: var(--accent); border-color: var(--border-accent); }

        .badge-dot {
            position: absolute;
            top: 7px; right: 7px;
            width: 7px; height: 7px;
            border-radius: 50%;
            background: var(--danger);
            border: 2px solid var(--bg-secondary);
        }

        .user-pill {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 6px 16px 6px 6px;
            background: rgba(30, 41, 59, 0.7);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 50px;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .user-pill:hover { 
            border-color: rgba(139, 92, 246, 0.4); 
            background: rgba(30, 41, 59, 0.9);
            transform: translateY(-1px);
            box-shadow: 0 6px 20px rgba(139, 92, 246, 0.15);
        }

        /* Hide the default Bootstrap dropdown caret */
        .user-pill::after { display: none !important; }

        .user-pill .dropdown-icon {
            font-size: 0.75rem;
            color: var(--text-secondary);
            transition: transform 0.3s ease;
        }

        .user-pill[aria-expanded="true"] .dropdown-icon {
            transform: rotate(180deg);
        }

        .user-avatar {
            width: 38px; height: 38px;
            border-radius: 50%;
            background: linear-gradient(135deg, #6366f1, #8b5cf6, #d946ef);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1rem;
            color: white;
            font-weight: 700;
            box-shadow: inset 0 -2px 5px rgba(0,0,0,0.2), 0 2px 10px rgba(99, 102, 241, 0.3);
            border: 2px solid rgba(255, 255, 255, 0.15);
        }

        .user-pill .user-name {
            font-size: 0.9rem;
            font-weight: 600;
            color: #f8fafc;
            letter-spacing: 0.3px;
        }
        
        .user-pill .user-name small {
            font-weight: 400;
            color: #94a3b8;
            font-size: 0.75rem;
            margin-left: 4px;
        }

        /* ─── MAIN CONTENT ───────────────────────────────── */
        #main-content {
            margin-left: var(--sidebar-w);
            margin-top: var(--topnav-h);
            min-height: calc(100vh - var(--topnav-h));
            padding: 28px;
            transition: margin-left 0.3s ease;
        }

        /* ─── PAGE HEADER ────────────────────────────────── */
        .page-header {
            margin-bottom: 24px;
        }

        .page-header h1 {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--text-primary);
            margin: 0 0 4px;
        }

        .page-header p {
            font-size: 0.875rem;
            color: var(--text-secondary);
            margin: 0;
        }

        .breadcrumb-custom {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 0.8rem;
            color: var(--text-muted);
        }

        .breadcrumb-custom a { color: var(--accent); text-decoration: none; }
        .breadcrumb-custom a:hover { text-decoration: underline; }

        /* ─── CARDS ──────────────────────────────────────── */
        .card-dark {
            background: var(--bg-card);
            border: 1px solid var(--border);
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow);
            overflow: hidden;
        }

        .card-dark-header {
            padding: 18px 24px 16px;
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .card-dark-header h5 {
            font-size: 1rem;
            font-weight: 600;
            color: var(--text-primary);
            margin: 0;
        }

        .card-dark-body { padding: 24px; }

        /* STAT CARDS */
        .stat-card {
            background: var(--bg-card);
            border: 1px solid var(--border);
            border-radius: var(--radius-lg);
            padding: 22px 24px;
            display: flex;
            align-items: center;
            gap: 18px;
            transition: all 0.3s ease;
            cursor: default;
        }

        .stat-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 40px rgba(0,0,0,0.4);
            border-color: var(--border-accent);
        }

        .stat-icon {
            width: 54px; height: 54px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.4rem;
            flex-shrink: 0;
        }

        .stat-icon.blue   { background: rgba(59,130,246,0.15); color: var(--accent); }
        .stat-icon.green  { background: rgba(16,185,129,0.15); color: var(--success); }
        .stat-icon.purple { background: rgba(139,92,246,0.15); color: var(--purple); }
        .stat-icon.amber  { background: rgba(245,158,11,0.15); color: var(--warning); }

        .stat-info h3 {
            font-size: 1.8rem;
            font-weight: 800;
            color: var(--text-primary);
            margin: 0 0 2px;
            line-height: 1;
        }

        .stat-info p {
            font-size: 0.82rem;
            color: var(--text-secondary);
            margin: 0;
            font-weight: 500;
        }

        .stat-info .stat-trend {
            font-size: 0.72rem;
            color: var(--success);
            margin-top: 4px;
        }

        /* ─── TABLES ─────────────────────────────────────── */
        .table-dark-custom {
            width: 100%;
            color: var(--text-primary);
            border-collapse: collapse;
        }

        .table-dark-custom thead th {
            background: var(--bg-tertiary);
            color: var(--text-secondary);
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            padding: 12px 16px;
            border-bottom: 1px solid var(--border);
            white-space: nowrap;
        }

        .table-dark-custom tbody tr {
            border-bottom: 1px solid var(--border);
            transition: background 0.15s;
        }

        .table-dark-custom tbody tr:hover {
            background: rgba(255,255,255,0.025);
        }

        .table-dark-custom tbody tr:last-child { border-bottom: none; }

        .table-dark-custom td {
            padding: 13px 16px;
            font-size: 0.875rem;
            color: var(--text-primary);
            vertical-align: middle;
        }

        .table-wrapper-dark { overflow-x: auto; }

        /* ─── BADGES ─────────────────────────────────────── */
        .badge-dark {
            display: inline-flex;
            align-items: center;
            padding: 4px 10px;
            border-radius: 50px;
            font-size: 0.72rem;
            font-weight: 600;
        }

        .badge-blue   { background: rgba(59,130,246,0.15); color: var(--accent); border: 1px solid rgba(59,130,246,0.3); }
        .badge-green  { background: rgba(16,185,129,0.15); color: var(--success); border: 1px solid rgba(16,185,129,0.3); }
        .badge-purple { background: rgba(139,92,246,0.15); color: var(--purple); border: 1px solid rgba(139,92,246,0.3); }
        .badge-amber  { background: rgba(245,158,11,0.15); color: var(--warning); border: 1px solid rgba(245,158,11,0.3); }
        .badge-red    { background: rgba(239,68,68,0.15); color: var(--danger); border: 1px solid rgba(239,68,68,0.3); }

        /* ─── BUTTONS ────────────────────────────────────── */
        .btn-accent {
            background: var(--accent);
            border: none;
            color: white;
            padding: 9px 20px;
            border-radius: 10px;
            font-size: 0.875rem;
            font-weight: 600;
            font-family: 'Inter', sans-serif;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
            transition: all 0.2s ease;
            text-decoration: none;
        }

        .btn-accent:hover {
            background: var(--accent-hover);
            transform: translateY(-1px);
            box-shadow: 0 6px 20px var(--accent-glow);
            color: white;
        }

        .btn-icon {
            width: 32px; height: 32px;
            border-radius: 8px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 0.8rem;
            border: none;
            cursor: pointer;
            transition: all 0.2s;
            text-decoration: none;
        }

        .btn-icon.edit  { background: rgba(59,130,246,0.15); color: var(--accent); border: 1px solid rgba(59,130,246,0.2); }
        .btn-icon.edit:hover  { background: var(--accent); color: white; }
        .btn-icon.del   { background: rgba(239,68,68,0.15); color: var(--danger); border: 1px solid rgba(239,68,68,0.2); }
        .btn-icon.del:hover   { background: var(--danger); color: white; }

        /* ─── SEARCH INPUT ───────────────────────────────── */
        .search-input-dark {
            background: var(--bg-tertiary);
            border: 1px solid var(--border);
            color: var(--text-primary);
            border-radius: 10px;
            padding: 9px 16px 9px 40px;
            font-size: 0.875rem;
            font-family: 'Inter', sans-serif;
            outline: none;
            width: 280px;
            transition: border-color 0.2s;
        }

        .search-input-dark::placeholder { color: var(--text-muted); }
        .search-input-dark:focus { border-color: var(--border-accent); }

        .search-input-wrapper {
            position: relative;
            display: inline-flex;
            align-items: center;
        }

        .search-input-wrapper .si-icon {
            position: absolute;
            left: 12px;
            color: var(--text-muted);
            font-size: 0.85rem;
        }

        /* ─── MODALS ─────────────────────────────────────── */
        .modal-dark .modal-content {
            background: var(--bg-secondary);
            border: 1px solid var(--border);
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow-lg);
        }

        .modal-dark .modal-header {
            border-bottom: 1px solid var(--border);
            padding: 20px 24px 18px;
        }

        .modal-dark .modal-title {
            font-size: 1rem;
            font-weight: 700;
            color: var(--text-primary);
        }

        .modal-dark .modal-body { padding: 24px; }

        .modal-dark .modal-footer {
            border-top: 1px solid var(--border);
            padding: 16px 24px;
        }

        .modal-dark .btn-close {
            filter: invert(1) opacity(0.6);
        }

        .modal-dark .btn-close:hover { filter: invert(1) opacity(1); }

        /* FORM CONTROLS DARK */
        .form-dark .form-label {
            font-size: 0.82rem;
            font-weight: 600;
            color: var(--text-secondary);
            margin-bottom: 6px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .form-dark .form-control,
        .form-dark .form-select {
            background: var(--bg-tertiary);
            border: 1px solid var(--border);
            color: var(--text-primary);
            border-radius: 10px;
            padding: 10px 14px;
            font-size: 0.875rem;
            font-family: 'Inter', sans-serif;
            transition: border-color 0.2s;
        }

        .form-dark .form-control:focus,
        .form-dark .form-select:focus {
            background: var(--bg-tertiary);
            border-color: var(--border-accent);
            color: var(--text-primary);
            box-shadow: 0 0 0 3px var(--accent-light);
        }

        .form-dark .form-control::placeholder { color: var(--text-muted); }
        .form-dark .form-control[readonly] { opacity: 0.6; cursor: not-allowed; }
        .form-dark .form-select option { background: var(--bg-secondary); color: var(--text-primary); }

        .form-dark .invalid-feedback { font-size: 0.78rem; }

        /* ─── ACTIVITY ITEM ──────────────────────────────── */
        .activity-item {
            display: flex;
            align-items: flex-start;
            gap: 14px;
            padding: 14px 0;
            border-bottom: 1px solid var(--border);
        }

        .activity-item:last-child { border-bottom: none; }

        .activity-dot {
            width: 36px; height: 36px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.85rem;
            flex-shrink: 0;
        }

        .activity-content p {
            font-size: 0.85rem;
            color: var(--text-primary);
            margin: 0 0 3px;
            font-weight: 500;
        }

        .activity-content small {
            font-size: 0.75rem;
            color: var(--text-muted);
        }

        /* ─── OVERLAY ────────────────────────────────────── */
        #sidebar-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.6);
            z-index: 1045;
        }

        /* ─── RESPONSIVE ─────────────────────────────────── */
        @media (max-width: 991.98px) {
            #sidebar {
                transform: translateX(-100%);
            }
            #sidebar.open {
                transform: translateX(0);
            }
            #topnav {
                left: 0;
            }
            #main-content {
                margin-left: 0;
            }
            .sidebar-toggle { display: flex; }
            #sidebar-overlay { display: none; }
            #sidebar.open ~ #sidebar-overlay { display: block; }
        }

        @media (max-width: 575.98px) {
            #main-content { padding: 16px; }
            .search-bar { max-width: 200px; }
            .user-name { display: none; }
        }

        /* ─── SCROLLBAR GLOBAL ───────────────────────────── */
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: var(--bg-primary); }
        ::-webkit-scrollbar-thumb { background: var(--bg-tertiary); border-radius: 4px; }
        ::-webkit-scrollbar-thumb:hover { background: var(--text-muted); }

        /* ─── ANIMATIONS ─────────────────────────────────── */
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(16px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        .fade-in-up { animation: fadeInUp 0.4s ease forwards; }

        @keyframes pulse-dot {
            0%, 100% { opacity: 1; }
            50%       { opacity: 0.4; }
        }

        .pulse { animation: pulse-dot 2s ease infinite; }

        /* ─── EMPTY STATE ────────────────────────────────── */
        .empty-state {
            text-align: center;
            padding: 48px 20px;
            color: var(--text-muted);
        }

        .empty-state i {
            font-size: 2.5rem;
            margin-bottom: 12px;
            display: block;
            opacity: 0.4;
        }

        .empty-state p { font-size: 0.9rem; }

        /* ─── AVATAR CIRCLE ──────────────────────────────── */
        .avatar-circle {
            width: 36px; height: 36px;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 0.8rem;
            font-weight: 700;
            color: white;
        }

        /* ─── ADDRESS TRUNCATE ───────────────────────────── */
        .cell-truncate {
            max-width: 160px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        /* ─── SECTION SEPARATOR ──────────────────────────── */
        /* ─── DATATABLES DARK MODE OVERRIDES ─────────────────── */
        .dataTables_wrapper .dataTables_length, .dataTables_wrapper .dataTables_filter, .dataTables_wrapper .dataTables_info, .dataTables_wrapper .dataTables_processing, .dataTables_wrapper .dataTables_paginate {
            color: var(--text-secondary) !important;
            font-size: 0.85rem;
            margin-bottom: 12px;
            margin-top: 12px;
        }
        
        .dataTables_wrapper .dataTables_filter input, .dataTables_wrapper .dataTables_length select {
            background-color: var(--bg-tertiary);
            border: 1px solid var(--border);
            color: var(--text-primary);
            border-radius: 6px;
            padding: 4px 8px;
            outline: none;
        }

        .dataTables_wrapper .dataTables_filter input:focus {
            border-color: var(--border-accent);
        }
        
        table.dataTable.table-dark-custom {
            border-bottom: 1px solid var(--border);
        }
        
        .page-item.disabled .page-link {
            background-color: transparent;
            border-color: var(--border);
            color: var(--text-muted);
        }
        
        .page-item .page-link {
            background-color: var(--bg-tertiary);
            border-color: var(--border);
            color: var(--text-secondary);
        }
        
        .page-item.active .page-link {
            background-color: var(--accent);
            border-color: var(--accent);
            color: white;
        }
        
        /* Hide existing custom search if DataTable is active */
        .dt-active .search-input-wrapper { display: none !important; }

    </style>

    @stack('css')
</head>
<body>

{{-- ═══════════════════════════════ SIDEBAR OVERLAY ═════════════════════════════ --}}
<div id="sidebar-overlay" onclick="closeSidebar()"></div>

{{-- ═══════════════════════════════════ SIDEBAR ══════════════════════════════════ --}}
@include('component.sidebar')

{{-- ═══════════════════════════════════ TOPNAV ═══════════════════════════════════ --}}
@include('component.topnav')

{{-- ═══════════════════════════════ MAIN CONTENT ═════════════════════════════════ --}}
<main id="main-content">
    @yield('content')
</main>

{{-- ─── jQuery (Required for DataTables) ─────────────────────────────────── --}}
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

{{-- ─── Bootstrap 5 JS ─────────────────────────────────────────────────────── --}}
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

{{-- ─── DataTables JS & CSS ────────────────────────────────────────────────── --}}
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>

{{-- SweetAlert2 --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    // ─── Sidebar toggle ───────────────────────────────────────────────────────
    function toggleSidebar() {
        document.getElementById('sidebar').classList.toggle('open');
    }

    function closeSidebar() {
        document.getElementById('sidebar').classList.remove('open');
    }

    // ─── Active sidebar link ──────────────────────────────────────────────────
    document.addEventListener('DOMContentLoaded', function () {
        const links = document.querySelectorAll('.sidebar-link');
        const currentPath = window.location.pathname;

        links.forEach(link => {
            if (link.getAttribute('href') && currentPath.startsWith(link.getAttribute('href'))) {
                link.classList.add('active');
            }
        });
        
        // ─── Global SweetAlert handler ──────────────────────────────────────────
        @if(session('success'))
            Swal.fire({
                icon: 'success',
                title: '{!! session("title", "Success!") !!}',
                text: '{!! session("success") !!}',
                timer: {{ session()->has('timer') ? session('timer') : 3000 }},
                showConfirmButton: {{ session('showConfirmButton', false) ? 'true' : 'false' }},
                confirmButtonText: '{{ session("confirmButtonText", "OK") }}'
            });
        @endif

        @if(session('error'))
            Swal.fire({
                icon: 'error',
                title: '{!! session("title", "Error!") !!}',
                text: '{!! session("error") !!}',
                timer: {{ session()->has('timer') ? session('timer') : 4000 }},
                showConfirmButton: {{ session('showConfirmButton', false) ? 'true' : 'false' }},
                confirmButtonText: '{{ session("confirmButtonText", "OK") }}'
            });
        @endif
    });
</script>

@stack('script')

</body>
</html>
