
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Admin | Haroon & Sons')</title>
    <link rel="icon" href="{{ asset('assets/images/logoo.png') }}" type="image/png">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&family=Playfair+Display:ital,wght@0,500;0,600;0,700;1,500;1,600&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('assets/css/admin.css') }}">
</head>

<body class="admin-body">

    <!-- Admin SVG Icons -->
    <svg xmlns="http://www.w3.org/2000/svg" width="0" height="0"
         style="position:absolute;overflow:hidden"
         aria-hidden="true" focusable="false">

        <symbol id="admin-icon-grid" viewBox="0 0 24 24">
            <rect x="3" y="3" width="7" height="7" rx="1.5"/>
            <rect x="14" y="3" width="7" height="7" rx="1.5"/>
            <rect x="3" y="14" width="7" height="7" rx="1.5"/>
            <rect x="14" y="14" width="7" height="7" rx="1.5"/>
        </symbol>

        <symbol id="admin-icon-posts" viewBox="0 0 24 24">
            <rect x="4" y="3" width="16" height="18" rx="2"/>
            <path d="M8 7h8M8 11h8M8 15h5"/>
        </symbol>

        <symbol id="admin-icon-plus" viewBox="0 0 24 24">
            <path d="M12 5v14M5 12h14"/>
        </symbol>

        <symbol id="admin-icon-bell" viewBox="0 0 24 24">
            <path d="M18 8a6 6 0 0 0-12 0c0 7-3 7-3 9h18c0-2-3-2-3-9M10 21h4"/>
        </symbol>

        <symbol id="admin-icon-external" viewBox="0 0 24 24">
            <path d="M14 3h7v7M21 3 11 13M10 4H5a2 2 0 0 0-2 2v13a2 2 0 0 0 2 2h13a2 2 0 0 0 2-2v-5"/>
        </symbol>

        <symbol id="admin-icon-logout" viewBox="0 0 24 24">
            <path d="M9 4H5a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h4M14 7l5 5-5 5M8 12h11"/>
        </symbol>

        <symbol id="admin-icon-check" viewBox="0 0 24 24">
            <rect x="3" y="3" width="18" height="18" rx="5"/>
            <path d="m7.5 12 3 3 6-6"/>
        </symbol>

        <symbol id="admin-icon-draft" viewBox="0 0 24 24">
            <path d="m15 5 4 4M4 20l5-1L20 8a2.8 2.8 0 0 0-4-4L5 15l-1 5ZM13 21h8"/>
        </symbol>

        <symbol id="admin-icon-review" viewBox="0 0 24 24">
            <path d="M12 3 14.8 8.7 21 9.6 16.5 14 17.6 20.2 12 17.2 6.4 20.2 7.5 14 3 9.6 9.2 8.7 12 3Z"/>
        </symbol>

        <symbol id="admin-icon-comment" viewBox="0 0 24 24">
            <path d="M21 11.5a8.5 8.5 0 0 1-8.5 8.5 9 9 0 0 1-4-.9L3 21l1.9-5.5a9 9 0 0 1-.9-4A8.5 8.5 0 0 1 12.5 3H13a8.5 8.5 0 0 1 8 8v.5Z"/>
        </symbol>

        <symbol id="admin-icon-video" viewBox="0 0 24 24">
            <rect x="3" y="5" width="18" height="14" rx="2"/>
            <path d="m10 9 5 3-5 3V9Z"/>
        </symbol>

        <symbol id="admin-icon-chart" viewBox="0 0 24 24">
            <path d="M3 3v18h18M8 16v-4M13 16V7M18 16v-7"/>
        </symbol>

        <symbol id="admin-icon-lock" viewBox="0 0 24 24">
            <rect x="4" y="10" width="16" height="11" rx="2"/>
            <path d="M8 10V7a4 4 0 0 1 8 0v3M12 14v3"/>
        </symbol>

        <symbol id="admin-icon-arrow" viewBox="0 0 24 24">
            <path d="M4 12h16M14 6l6 6-6 6"/>
        </symbol>

    </svg>

    <a class="admin-skip-link" href="#admin-content">Skip to content</a>

    <div class="admin-shell">

        <!-- Sidebar -->
        <aside class="admin-sidebar">

            <a href="{{ route('admin.dashboard') }}" class="admin-brand">
                <img src="{{ asset('assets/images/logoo.png') }}" alt="">
                <span>
                    <strong>HAROON &amp; SONS</strong>
                    <small>ADMIN WORKSPACE</small>
                </span>
            </a>

            <p class="admin-workspace-label">WORKSPACE</p>

            <nav aria-label="Admin navigation">

                <!-- Dashboard -->
                <a href="{{ route('admin.dashboard') }}"
                   @class(['admin-nav-link', 'active' => request()->routeIs('admin.dashboard')])
                   @if(request()->routeIs('admin.dashboard')) aria-current="page" @endif>

                    <svg class="admin-icon" aria-hidden="true">
                        <use href="#admin-icon-grid"/>
                    </svg>

                    <span class="nav-text">Overview</span>
                </a>

                <!-- Manage Posts -->
                <a href="{{ route('admin.posts.index') }}"
                   @class(['admin-nav-link', 'active' => request()->routeIs('admin.posts.index', 'admin.posts.edit')])
                   @if(request()->routeIs('admin.posts.index', 'admin.posts.edit')) aria-current="page" @endif>

                    <svg class="admin-icon" aria-hidden="true">
                        <use href="#admin-icon-posts"/>
                    </svg>

                    <span class="nav-text">Manage posts</span>
                </a>

                <!-- Create Post -->
                <a href="{{ route('admin.posts.create') }}"
                   @class(['admin-nav-link', 'active' => request()->routeIs('admin.posts.create')])
                   @if(request()->routeIs('admin.posts.create')) aria-current="page" @endif>

                    <svg class="admin-icon" aria-hidden="true">
                        <use href="#admin-icon-plus"/>
                    </svg>

                    <span class="nav-text">Create post</span>
                </a>

                <!-- Reviews -->
                <a href="{{ route('admin.reviews.index') }}"
                   @class(['admin-nav-link', 'active' => request()->routeIs('admin.reviews.*')])
                   @if(request()->routeIs('admin.reviews.*')) aria-current="page" @endif>

                    <svg class="admin-icon" aria-hidden="true">
                        <use href="#admin-icon-review"/>
                    </svg>

                    <span class="nav-text">Customer reviews</span>
                </a>

                <!-- Comments -->
                <a href="{{ route('admin.comments.index') }}"
                   @class(['admin-nav-link', 'active' => request()->routeIs('admin.comments.*')])
                   @if(request()->routeIs('admin.comments.*')) aria-current="page" @endif>

                    <svg class="admin-icon" aria-hidden="true">
                        <use href="#admin-icon-comment"/>
                    </svg>

                    <span class="nav-text">Comments</span>
                </a>
<!-- Analytics -->
                <a href="{{ route('admin.analytics') }}"
                   @class(['admin-nav-link', 'active' => request()->routeIs('admin.analytics')])
                   @if(request()->routeIs('admin.analytics')) aria-current="page" @endif>

                    <svg class="admin-icon" aria-hidden="true">
                        <use href="#admin-icon-chart"/>
                    </svg>

                    <span class="nav-text">Analytics</span>
                </a>

                <!-- Notifications -->
                <a href="{{ route('admin.notifications.index') }}"
                   @class(['admin-nav-link', 'active' => request()->routeIs('admin.notifications.*')])
                   @if(request()->routeIs('admin.notifications.*')) aria-current="page" @endif>

                    <svg class="admin-icon" aria-hidden="true">
                        <use href="#admin-icon-bell"/>
                    </svg>

                    <span class="nav-text">Notifications</span>

                    @if(($unreadNotifications ?? 0) > 0)
                        <span class="sidebar-count">{{ $unreadNotifications }}</span>
                    @endif
                </a>

                <!-- Change Password -->
                <a href="{{ route('admin.password.edit') }}"
                   @class(['admin-nav-link', 'active' => request()->routeIs('admin.password.*')])
                   @if(request()->routeIs('admin.password.*')) aria-current="page" @endif>

                    <svg class="admin-icon" aria-hidden="true">
                        <use href="#admin-icon-lock"/>
                    </svg>

                    <span class="nav-text">Change password</span>
                </a>

            </nav>

            <!-- Sidebar Footer -->
            <div class="admin-sidebar-footer">

                <a class="sidebar-website-link"
                   href="{{ route('home') }}"
                   target="_blank"
                   rel="noopener">

                    <svg class="admin-icon" aria-hidden="true">
                        <use href="#admin-icon-external"/>
                    </svg>

                    <span>View website</span>
                </a>

                <form class="admin-logout"
                      method="POST"
                      action="{{ route('admin.logout') }}">

                    @csrf

                    <button type="submit">
                        <svg class="admin-icon" aria-hidden="true">
                            <use href="#admin-icon-logout"/>
                        </svg>

                        <span>Sign out</span>
                    </button>

                </form>

            </div>

        </aside>

        <!-- Main Content -->
        <main class="admin-main" id="admin-content">

            <!-- Topbar -->
            <header class="admin-topbar">

                <div class="admin-topbar-heading">

                    <span class="admin-kicker">
                        HAROON &amp; SONS
                        <span aria-hidden="true">/</span>
                        ADMIN
                    </span>

                    <h1>@yield('page_heading', 'Overview')</h1>

                </div>

                <div class="admin-user">

                    <span class="admin-avatar">
                        {{ strtoupper(substr(auth('admin')->user()->name, 0, 1)) }}
                    </span>

                    <span class="admin-user-copy">
                        <strong>{{ auth('admin')->user()->name }}</strong>
                        <small>Administrator</small>
                    </span>

                </div>

            </header>

            <!-- Success Message -->
            @if(session('status'))
                <div class="flash-message" role="status">

                    <svg class="admin-icon" aria-hidden="true">
                        <use href="#admin-icon-check"/>
                    </svg>

                    <span>{{ session('status') }}</span>

                </div>
            @endif

            <!-- Error Message -->
            @if(session('error'))
                <div class="flash-message" role="alert">
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            <!-- Page Content -->
            @yield('content')

        </main>

    </div>

</body>
</html>
