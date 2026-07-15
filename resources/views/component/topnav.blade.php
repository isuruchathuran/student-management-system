{{-- ═══════════════════════════════════════════════════════
     TOP NAVBAR COMPONENT
═══════════════════════════════════════════════════════ --}}
<header id="topnav">

    <div class="topnav-left">
        {{-- Mobile sidebar toggle --}}
        <button class="sidebar-toggle" onclick="toggleSidebar()" aria-label="Toggle Sidebar">
            <i class="fa-solid fa-bars"></i>
        </button>

        {{-- Global Search --}}
        <div class="search-bar">
            <i class="fa-solid fa-magnifying-glass search-icon"></i>
            <input type="text" id="globalSearch" placeholder="Search students, teachers...">
        </div>
    </div>
    
    <div class="topnav-right">
        @php
            $user = null;
            $guard = null;
            $portalName = '';
            
            if (Auth::guard('admin')->check()) {
                $user = Auth::guard('admin')->user();
                $guard = 'admin';
                $portalName = 'Admin';
            } elseif (Auth::guard('teacher')->check()) {
                $user = Auth::guard('teacher')->user();
                $guard = 'teacher';
                $portalName = 'Teacher';
            } elseif (Auth::guard('student')->check()) {
                $user = Auth::guard('student')->user();
                $guard = 'student';
                $portalName = 'Student';
            }
            
            $displayName = $user->Name ?? $user->Teacher_Name ?? 'Admin';
            $avatarLetter = substr($displayName, 0, 1);
        @endphp

        @if($guard)
        <div class="dropdown">
            <a href="#" class="user-pill text-decoration-none dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                <div class="user-avatar">
                    {{ strtoupper($avatarLetter) }}
                </div>
                <span class="user-name">{{ $displayName }} <small>({{ $portalName }})</small></span>
                <i class="fa-solid fa-chevron-down dropdown-icon ms-1"></i>
            </a>
            <ul class="dropdown-menu dropdown-menu-end shadow-lg" style="background: var(--bg-card); border: 1px solid var(--border);">
                <li>
                    <a class="dropdown-item text-light d-flex align-items-center gap-2 py-2" href="#" onclick="confirmSwitchPortal(event, '{{ route($guard.'.logout') }}')">
                        <i class="fa-solid fa-right-left text-accent" style="width: 20px; text-align: center;"></i> Switch Portal
                    </a>
                </li>
                <li><hr class="dropdown-divider border-secondary"></li>
                <li>
                    <form method="POST" action="{{ route($guard.'.logout') }}">
                        @csrf
                        <button type="submit" class="dropdown-item text-danger d-flex align-items-center gap-2 py-2" style="background: transparent;">
                            <i class="fa-solid fa-arrow-right-from-bracket" style="width: 20px; text-align: center;"></i> Logout
                        </button>
                    </form>
                </li>
            </ul>
        </div>
        @endif
    </div>

</header>

@push('script')
<script>
function confirmSwitchPortal(e, logoutUrl) {
    e.preventDefault();
    Swal.fire({
        title: 'Switch Portal?',
        text: "You will be logged out of your current session.",
        icon: 'warning',
        showCancelButton: true,
        background: '#1e293b',
        color: '#f8fafc',
        confirmButtonColor: '#3b82f6',
        cancelButtonColor: '#ef4444',
        confirmButtonText: 'Yes, switch portal'
    }).then((result) => {
        if (result.isConfirmed) {
            // Create a form to POST to the logout URL
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = logoutUrl;
            
            const csrfToken = document.createElement('input');
            csrfToken.type = 'hidden';
            csrfToken.name = '_token';
            csrfToken.value = '{{ csrf_token() }}';
            
            form.appendChild(csrfToken);
            document.body.appendChild(form);
            form.submit();
        }
    });
}
</script>
@endpush
