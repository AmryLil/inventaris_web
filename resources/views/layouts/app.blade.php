<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Sistem Inventory')</title>
    
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <style>
        /* Variabel CSS */
        :root {
            --primary-color: #4e73df;
            --secondary-color: #858796;
            --success-color: #1cc88a;
            --info-color: #36b9cc;
            --warning-color: #f6c23e;
            --danger-color: #e74a3b;
            --light-color: #f8f9fc;
            --dark-color: #5a5c69;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: var(--light-color);
            overflow-x: hidden;
        }

        /* Sidebar Styles */
        .sidebar {
            background: linear-gradient(180deg, var(--primary-color) 0%, #224abe 100%);
            min-height: 100vh;
            width: 280px;
            position: fixed;
            top: 0;
            left: 0;
            transition: all 0.3s;
            z-index: 1000;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
        }

        .sidebar.collapsed {
            margin-left: -280px;
        }

        .sidebar-brand {
            padding: 1.5rem;
            text-align: center;
            color: white;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }

        .sidebar-brand img {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            margin-bottom: 1rem;
            border: 4px solid rgba(255,255,255,0.1);
        }

        .nav-item {
            margin: 0.25rem 1rem;
        }

        .nav-link {
            color: rgba(255,255,255,0.8);
            padding: 1rem;
            border-radius: 0.35rem;
            display: flex;
            align-items: center;
            gap: 1rem;
            transition: all 0.2s;
        }

        .nav-link:hover {
            color: white;
            background: rgba(255,255,255,0.1);
        }

        .nav-link.active {
            color: white;
            background: rgba(255,255,255,0.2);
        }

        /* Main Content Styles */
        .main-content {
            margin-left: 280px;
            padding: 1.5rem;
            transition: all 0.3s;
        }

        .main-content.expanded {
            margin-left: 0;
        }

        /* Card Styles */
        .stats-card {
            background: white;
            border-radius: 0.5rem;
            padding: 1.5rem;
            height: 100%;
            border-left: 0.25rem solid;
            transition: transform 0.2s;
        }

        .stats-card:hover {
            transform: translateY(-3px);
        }

        .stats-card.primary { border-left-color: var(--primary-color); }
        .stats-card.success { border-left-color: var(--success-color); }
        .stats-card.warning { border-left-color: var(--warning-color); }
        .stats-card.info { border-left-color: var(--info-color); }

        /* Product Cards */
        .product-card {
            background: white;
            border-radius: 0.5rem;
            overflow: hidden;
            transition: all 0.3s;
            height: 100%;
            border: none;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
        }

        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 0.5rem 2rem 0 rgba(58, 59, 69, 0.2);
        }

        .product-image {
            height: 200px;
            position: relative;
            overflow: hidden;
        }

        .product-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.3s;
        }

        /* Login Styles */
        .login-container {
            min-height: 100vh;
            background: linear-gradient(135deg, #2c3e50, #3498db);
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }

        .login-card {
            width: 100%;
            max-width: 400px;
            background: rgba(255, 255, 255, 0.95);
            padding: 2.5rem;
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2);
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .sidebar {
                margin-left: -280px;
            }
            
            .sidebar.active {
                margin-left: 0;
            }
            
            .main-content {
                margin-left: 0;
            }
            
            .toggle-sidebar {
                display: block;
            }
        }
    </style>
</head>
<body>
    @yield('content')
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Toggle Sidebar
        function toggleSidebar() {
            document.querySelector('.sidebar').classList.toggle('collapsed');
            document.querySelector('.main-content').classList.toggle('expanded');
        }

        // Responsive checks
        function checkWidth() {
            if (window.innerWidth < 768) {
                document.querySelector('.sidebar').classList.add('collapsed');
                document.querySelector('.main-content').classList.add('expanded');
            }
        }

        window.addEventListener('load', checkWidth);
        window.addEventListener('resize', checkWidth);
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // SweetAlert untuk flash message sukses
            @if(session('success'))
                Swal.fire({
                    title: 'Berhasil!',
                    text: '{{ session("success") }}',
                    icon: 'success',
                    confirmButtonText: 'OK'
                });
            @endif
        });
        </script>
    @yield('scripts')
</body>
</html>