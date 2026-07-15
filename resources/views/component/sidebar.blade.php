{{-- ═══════════════════════════════════════════════════════
     SIDEBAR COMPONENT
═══════════════════════════════════════════════════════ --}}
<nav id="sidebar">
    <div class="sidebar-brand">
        <div class="brand-icon">LMS</div>
        <div class="brand-text">
            <h6>Learning Platform</h6>
            @if(Auth::guard('admin')->check())
                <small>Admin Portal</small>
            @elseif(Auth::guard('teacher')->check())
                <small>Teacher Portal</small>
            @elseif(Auth::guard('student')->check())
                <small>Student Portal</small>
            @endif
        </div>
    </div>
    <div class="sidebar-nav">
        <div class="nav-section-label">Main Menu</div>
        
        @if(Auth::guard('admin')->check())
            {{-- ADMIN LINKS --}}
            <a href="{{ route('admin.dashboard') }}" class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <i class="fa-solid fa-gauge nav-icon"></i> Dashboard
            </a>
            <a href="{{ route('admin.students.index') }}" class="sidebar-link {{ request()->routeIs('admin.students.*') ? 'active' : '' }}">
                <i class="fa-solid fa-user-graduate nav-icon"></i> Students
            </a>
            <a href="{{ route('admin.teachers.index') }}" class="sidebar-link {{ request()->routeIs('admin.teachers.*') ? 'active' : '' }}">
                <i class="fa-solid fa-chalkboard-user nav-icon"></i> Teachers
            </a>
            <a href="{{ route('admin.subjects.index') }}" class="sidebar-link {{ request()->routeIs('admin.subjects.*') ? 'active' : '' }}">
                <i class="fa-solid fa-book-open nav-icon"></i> Subjects
            </a>
            <a href="{{ route('admin.quizzes.index') }}" class="sidebar-link {{ request()->routeIs('admin.quizzes.*') ? 'active' : '' }}">
                <i class="fa-solid fa-clipboard-question nav-icon"></i> Quizzes
            </a>
            
            <div class="nav-section-label">Analytics</div>
            <a href="{{ route('admin.results.index') }}" class="sidebar-link {{ request()->routeIs('admin.results.*') ? 'active' : '' }}">
                <i class="fa-solid fa-square-poll-vertical nav-icon"></i> All Results
            </a>
            <a href="{{ route('admin.activity-logs') }}" class="sidebar-link {{ request()->routeIs('admin.activity-logs') ? 'active' : '' }}">
                <i class="fa-solid fa-bolt nav-icon"></i> Activity Logs
            </a>
        @elseif(Auth::guard('teacher')->check())
            {{-- TEACHER LINKS --}}
            <a href="{{ route('teacher.dashboard') }}" class="sidebar-link {{ request()->routeIs('teacher.dashboard') ? 'active' : '' }}">
                <i class="fa-solid fa-gauge nav-icon"></i> Dashboard
            </a>
            <a href="{{ route('teacher.questions.index') }}" class="sidebar-link {{ request()->routeIs('teacher.questions.*') ? 'active' : '' }}">
                <i class="fa-solid fa-clipboard-question nav-icon"></i> Question Bank
            </a>
            <a href="{{ route('teacher.quizzes.index') }}" class="sidebar-link {{ request()->routeIs('teacher.quizzes.*') ? 'active' : '' }}">
                <i class="fa-solid fa-clock nav-icon"></i> Quizzes
            </a>
            
            <div class="nav-section-label">Analytics</div>
            <a href="{{ route('teacher.results.index') }}" class="sidebar-link {{ request()->routeIs('teacher.results.*') ? 'active' : '' }}">
                <i class="fa-solid fa-square-poll-vertical nav-icon"></i> Student Results
            </a>
            
        @elseif(Auth::guard('student')->check())
            {{-- STUDENT LINKS --}}
            <a href="{{ route('student.dashboard') }}" class="sidebar-link {{ request()->routeIs('student.dashboard') ? 'active' : '' }}">
                <i class="fa-solid fa-gauge nav-icon"></i> Dashboard
            </a>
            <a href="{{ route('student.results.index') }}" class="sidebar-link {{ request()->routeIs('student.results.*') ? 'active' : '' }}">
                <i class="fa-solid fa-award nav-icon"></i> My Results
            </a>
        @endif
        
    </div>
    
    <div class="sidebar-footer">
        <div class="sidebar-user-mini">
            @if(Auth::guard('admin')->check())
                <div class="avatar-sm" style="background:linear-gradient(135deg,var(--accent),var(--blue))">A</div>
                <div class="user-info">
                    <span>Admin</span>
                    <small>System Administrator</small>
                </div>
            @elseif(Auth::guard('teacher')->check())
                <div class="avatar-sm" style="background:linear-gradient(135deg,var(--blue),var(--success))">
                    {{ substr(Auth::guard('teacher')->user()->Teacher_Name, 0, 1) }}
                </div>
                <div class="user-info">
                    <span>{{ Auth::guard('teacher')->user()->Teacher_Name }}</span>
                    <small>Teacher</small>
                </div>
            @elseif(Auth::guard('student')->check())
                <div class="avatar-sm" style="background:linear-gradient(135deg,var(--success),var(--accent))">
                    {{ substr(Auth::guard('student')->user()->Name, 0, 1) }}
                </div>
                <div class="user-info">
                    <span>{{ Auth::guard('student')->user()->Name }}</span>
                    <small>{{ Auth::guard('student')->user()->reg_No }}</small>
                </div>
            @endif
        </div>
    </div>
</nav>
