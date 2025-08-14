<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>CMS - College Management System
    </title>

    <link rel="shortcut icon" href="{{asset('favicon_io/favicon.ico')}}">
    <link rel="shortcut icon" sizes="16x16" href="{{asset('favicon_io/favicon-16x16.png')}}">
    <link rel="shortcut icon" sizes="32x32" href="{{asset('favicon_io/favicon-32x32.png')}}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('assets/logo.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('assets/logo.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('assets/logo.png') }}">

    <!-- Scripts -->
    <script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
    <!-- <script src="{{ asset('js/app.js') }}" defer></script> -->

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">
    <script src="{{ asset("js/jquery-3.6.0.min.js") }}"></script>
    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body>
    <div id="app">
        <nav class="navbar sticky-top navbar-expand-md navbar-light bg-white border-btm-e6">
            <div class="container">
                <a class="navbar-brand" href="{{ route('dashboard') }}">
                    <i class="bi bi-mortarboard"></i> CMS
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    @auth
                        @php
                            $latest_school_session = \App\Models\SchoolSession::latest()->first();
                            $current_school_session_name = null;
                            if($latest_school_session){
                                $current_school_session_name = $latest_school_session->session_name;
                            }
                        @endphp
                    <ul class="navbar-nav">
                        <li class="nav-item">
                            @if (session()->has('browse_session_name') && session('browse_session_name') !== $current_school_session_name)
                                <a class="nav-link text-danger disabled" href="#" tabindex="-1" aria-disabled="true"><i class="bi bi-exclamation-diamond-fill me-2"></i> Browsing Academic Year {{session('browse_session_name')}}</a>
                            @elseif(\App\Models\SchoolSession::latest()->count() > 0)
                                <a class="nav-link disabled" href="#" tabindex="-1" aria-disabled="true">Current Academic Year {{$current_school_session_name}}</a>
                            @else
                                <a class="nav-link text-danger disabled" href="#" tabindex="-1" aria-disabled="true"><i class="bi bi-exclamation-diamond-fill me-2"></i> Create an Academic Year.</a>
                            @endif
                        </li>
                    </ul>
                    @endauth
                    
                    <!-- Search Feature -->
                    @auth
                    <div class="navbar-nav mx-auto">
                        <form class="d-flex" id="globalSearchForm" action="{{ route('global.search') }}" method="GET">
                            <div class="input-group">
                                <input class="form-control" type="search" name="query" id="searchInput" placeholder="Search students, teachers..." aria-label="Search" value="{{ request('query') }}">
                                <button class="btn btn-outline-primary" type="submit">
                                    <i class="bi bi-search"></i>
                                </button>
                            </div>
                            <div id="searchResults" class="position-absolute bg-white border shadow-sm mt-5 w-100" style="display: none; z-index: 1000; max-height: 300px; overflow-y: auto;">
                                <!-- Search results will be populated here -->
                            </div>
                        </form>
                    </div>
                    @endauth
                    
                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ms-auto">
                        <!-- Authentication Links -->
                        @guest
                            @if (Route::has('login'))
                                <li class="nav-item">
                                    <a class="nav-link" href="">{{ __('Login') }}</a>
                                </li>
                            @endif
                        @else
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <span class="badge bg-light text-dark">{{ ucfirst(Auth::user()->role) }}</span>
                                    {{ Auth::user()->first_name }} {{ Auth::user()->last_name }}
                                </a>

                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ route('password.edit') }}">
                                        <i class="bi bi-key me-2"></i> Change Password
                                    </a>
                                    <a class="dropdown-item" href="{{ route('logout') }}">
                                        <i class="bi bi-door-open me-2"></i>Logout
                                    </a>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>
        <main>
            @yield('content')
        </main>
    </div>


    <!-- Global Search JavaScript -->
    <script>
        $(document).ready(function() {
            let searchTimeout;
            
            $('#searchInput').on('keyup', function() {
                const query = $(this).val();
                const resultsDiv = $('#searchResults');
                
                clearTimeout(searchTimeout);
                
                if (query.length < 2) {
                    resultsDiv.hide();
                    return;
                }
                
                searchTimeout = setTimeout(function() {
                    $.ajax({
                        url: '{{ route("global.search.ajax") }}',
                        method: 'GET',
                        data: { query: query },
                        success: function(data) {
                            resultsDiv.empty();
                            
                            if (data.length === 0) {
                                resultsDiv.html('<div class="p-3 text-muted">No results found</div>');
                            } else {
                                data.forEach(function(item) {
                                    const resultItem = $(`
                                        <a href="${item.url}" class="d-block text-decoration-none text-dark border-bottom p-2 hover-bg-light">
                                            <div class="d-flex align-items-center">
                                                <i class="bi ${item.icon} me-2"></i>
                                                <div>
                                                    <div class="fw-bold">${item.name}</div>
                                                    <small class="text-muted">${item.type} - ${item.details}</small>
                                                </div>
                                            </div>
                                        </a>
                                    `);
                                    resultsDiv.append(resultItem);
                                });
                            }
                            resultsDiv.show();
                        },
                        error: function() {
                            resultsDiv.html('<div class="p-3 text-muted">No results found</div>');
                            resultsDiv.show();
                        }
                    });
                }, 300);
            });
            
            // Hide search results when clicking outside
            $(document).on('click', function(e) {
                if (!$(e.target).closest('#globalSearchForm').length) {
                    $('#searchResults').hide();
                }
            });
            
            // Add hover effect styles
            $('<style>').text(`
                .hover-bg-light:hover {
                    background-color: #f8f9fa !important;
                }
                #searchResults a:last-child {
                    border-bottom: none !important;
                }
            `).appendTo('head');
        });
    </script>

</body>
</html>
