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

</header>
