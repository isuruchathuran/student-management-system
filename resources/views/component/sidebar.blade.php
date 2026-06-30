{{-- ═══════════════════════════════════════════════════════
     SIDEBAR COMPONENT
═══════════════════════════════════════════════════════ --}}
<aside id="sidebar">

    {{-- Brand --}}
    <div class="sidebar-brand">
        <div class="brand-icon">CX</div>
        <div class="brand-text">
            <h6>CodXpress</h6>
        </div>
    </div>

    {{-- Navigation --}}
    <nav class="sidebar-nav">
        <span class="nav-section-label">Main Menu</span>

        <a href="{{ route('dashboard') }}"
           class="sidebar-link @if(request()->routeIs('dashboard')) active @endif">
            <i class="fa-solid fa-gauge nav-icon"></i>
            Dashboard
        </a>

        <a href="{{ route('students.index') }}"
           class="sidebar-link @if(request()->routeIs('students.*')) active @endif">
            <i class="fa-solid fa-user-graduate nav-icon"></i>
            Students
        </a>

        <a href="{{ route('teachers.index') }}"
           class="sidebar-link @if(request()->routeIs('teachers.*')) active @endif">
            <i class="fa-solid fa-chalkboard-user nav-icon"></i>
            Teachers
        </a>

        <a href="{{ route('subjects.index') }}"
           class="sidebar-link @if(request()->routeIs('subjects.*')) active @endif">
            <i class="fa-solid fa-book-open nav-icon"></i>
            Subjects
        </a>

        <span class="nav-section-label">Analytics</span>

        <a href="{{ route('reports.index') }}"
           class="sidebar-link @if(request()->routeIs('reports.*')) active @endif">
            <i class="fa-solid fa-chart-bar nav-icon"></i>
            Reports
        </a>


    </nav>

    {{-- Footer user --}}
    <div class="sidebar-footer">
        <div class="sidebar-user-mini">
            <div class="avatar-sm">A</div>
            <div class="user-info">
                <span>Admin User</span>
                <small>admin@codxpress.lk</small>
            </div>
        </div>
    </div>

</aside>
