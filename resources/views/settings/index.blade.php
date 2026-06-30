@extends('app')

@push('page_title', 'Settings')

@section('content')
<div class="fade-in-up">

    {{-- ── Page Header ─────────────────────────────────────────────────── --}}
    <div class="page-header">
        <div class="breadcrumb-custom mb-1">
            <a href="{{ route('dashboard') }}">Dashboard</a>
            <i class="fa-solid fa-chevron-right" style="font-size:0.6rem;"></i>
            Settings
        </div>
        <h1><i class="fa-solid fa-gear me-2" style="color:var(--text-secondary);font-size:1.3rem;"></i>Settings</h1>
        <p>Manage your account, security, and system preferences</p>
    </div>

    <div class="row g-4">

        {{-- ── Admin Profile ────────────────────────────────────────────── --}}
        <div class="col-12 col-lg-6">
            <div class="card-dark">
                <div class="card-dark-header">
                    <h5><i class="fa-solid fa-user-circle me-2" style="color:var(--accent);"></i>Admin Profile</h5>
                </div>
                <div class="card-dark-body">
                    <div class="d-flex align-items-center gap-4 mb-4">
                        <div style="width:80px;height:80px;border-radius:50%;background:linear-gradient(135deg,var(--accent),var(--purple));display:flex;align-items:center;justify-content:center;font-size:1.8rem;color:white;font-weight:700;flex-shrink:0;box-shadow:0 8px 24px var(--accent-glow);">
                            A
                        </div>
                        <div>
                            <h5 style="margin:0;font-weight:700;color:var(--text-primary);">Admin User</h5>
                            <p style="margin:4px 0 0;font-size:0.85rem;color:var(--text-secondary);">System Administrator</p>
                            <span class="badge-dark badge-green mt-1"><i class="fa-solid fa-circle me-1" style="font-size:0.5rem;"></i>Active</span>
                        </div>
                    </div>
                    <form class="form-dark">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">First Name</label>
                                <input type="text" class="form-control" value="Admin" placeholder="First name">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Last Name</label>
                                <input type="text" class="form-control" value="User" placeholder="Last name">
                            </div>
                            <div class="col-12">
                                <label class="form-label">Email Address</label>
                                <input type="email" class="form-control" value="admin@codxpress.lk" placeholder="Email address">
                            </div>
                            <div class="col-12">
                                <label class="form-label">Phone Number</label>
                                <input type="text" class="form-control" value="+94 71 000 0000" placeholder="Phone number">
                            </div>
                            <div class="col-12">
                                <button type="button" class="btn-accent" onclick="showSaved()">
                                    <i class="fa-solid fa-floppy-disk me-1"></i> Save Profile
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- ── Change Password ───────────────────────────────────────────── --}}
        <div class="col-12 col-lg-6">
            <div class="card-dark">
                <div class="card-dark-header">
                    <h5><i class="fa-solid fa-lock me-2" style="color:var(--warning);"></i>Change Password</h5>
                </div>
                <div class="card-dark-body">
                    <form class="form-dark">
                        <div class="row g-3">
                            <div class="col-12">
                                <label class="form-label">Current Password</label>
                                <input type="password" class="form-control" placeholder="Enter current password">
                            </div>
                            <div class="col-12">
                                <label class="form-label">New Password</label>
                                <input type="password" class="form-control" placeholder="Enter new password">
                            </div>
                            <div class="col-12">
                                <label class="form-label">Confirm New Password</label>
                                <input type="password" class="form-control" placeholder="Confirm new password">
                            </div>
                            <div class="col-12">
                                <div style="background:rgba(245,158,11,0.1);border:1px solid rgba(245,158,11,0.3);border-radius:10px;padding:12px 16px;font-size:0.82rem;color:var(--warning);margin-bottom:4px;">
                                    <i class="fa-solid fa-circle-info me-2"></i>
                                    Password must be at least 8 characters and contain uppercase, lowercase, and numbers.
                                </div>
                            </div>
                            <div class="col-12">
                                <button type="button" class="btn-accent" style="background:var(--warning);" onclick="showSaved('Password updated!')">
                                    <i class="fa-solid fa-key me-1"></i> Update Password
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- ── System Settings ──────────────────────────────────────────── --}}
        <div class="col-12 col-lg-6">
            <div class="card-dark">
                <div class="card-dark-header">
                    <h5><i class="fa-solid fa-sliders me-2" style="color:var(--purple);"></i>System Settings</h5>
                </div>
                <div class="card-dark-body">
                    <div style="display:flex;flex-direction:column;gap:20px;">

                        <div style="display:flex;align-items:center;justify-content:space-between;padding:14px;background:var(--bg-tertiary);border-radius:10px;border:1px solid var(--border);">
                            <div>
                                <p style="margin:0;font-weight:600;font-size:0.9rem;color:var(--text-primary);">Email Notifications</p>
                                <small style="color:var(--text-muted);">Receive email alerts for new registrations</small>
                            </div>
                            <label class="toggle-switch">
                                <input type="checkbox" checked>
                                <span class="toggle-slider"></span>
                            </label>
                        </div>

                        <div style="display:flex;align-items:center;justify-content:space-between;padding:14px;background:var(--bg-tertiary);border-radius:10px;border:1px solid var(--border);">
                            <div>
                                <p style="margin:0;font-weight:600;font-size:0.9rem;color:var(--text-primary);">Auto-generate IDs</p>
                                <small style="color:var(--text-muted);">Automatically generate student/teacher IDs</small>
                            </div>
                            <label class="toggle-switch">
                                <input type="checkbox" checked>
                                <span class="toggle-slider"></span>
                            </label>
                        </div>

                        <div style="display:flex;align-items:center;justify-content:space-between;padding:14px;background:var(--bg-tertiary);border-radius:10px;border:1px solid var(--border);">
                            <div>
                                <p style="margin:0;font-weight:600;font-size:0.9rem;color:var(--text-primary);">Two-Factor Auth</p>
                                <small style="color:var(--text-muted);">Enable 2FA for admin login</small>
                            </div>
                            <label class="toggle-switch">
                                <input type="checkbox">
                                <span class="toggle-slider"></span>
                            </label>
                        </div>

                        <div style="display:flex;align-items:center;justify-content:space-between;padding:14px;background:var(--bg-tertiary);border-radius:10px;border:1px solid var(--border);">
                            <div>
                                <p style="margin:0;font-weight:600;font-size:0.9rem;color:var(--text-primary);">Maintenance Mode</p>
                                <small style="color:var(--text-muted);">Put system in maintenance mode</small>
                            </div>
                            <label class="toggle-switch">
                                <input type="checkbox">
                                <span class="toggle-slider"></span>
                            </label>
                        </div>

                    </div>

                    <div class="mt-4">
                        <button type="button" class="btn-accent" style="background:var(--purple);" onclick="showSaved('System settings saved!')">
                            <i class="fa-solid fa-floppy-disk me-1"></i> Save Settings
                        </button>
                    </div>
                </div>
            </div>
        </div>

        {{-- ── Theme Settings ───────────────────────────────────────────── --}}
        <div class="col-12 col-lg-6">
            <div class="card-dark">
                <div class="card-dark-header">
                    <h5><i class="fa-solid fa-palette me-2" style="color:var(--accent);"></i>Theme Settings</h5>
                </div>
                <div class="card-dark-body">
                    <p style="font-size:0.85rem;color:var(--text-secondary);margin-bottom:20px;">Choose your preferred accent color theme.</p>

                    <div class="d-flex flex-wrap gap-3 mb-4">
                        <div class="theme-swatch active-swatch" style="--sw:#3b82f6;" title="Blue (Default)">
                            <div style="width:48px;height:48px;border-radius:12px;background:#3b82f6;cursor:pointer;border:3px solid rgba(59,130,246,0.6);box-shadow:0 4px 12px rgba(59,130,246,0.4);"></div>
                            <small style="font-size:0.7rem;color:var(--text-muted);text-align:center;display:block;margin-top:6px;">Blue</small>
                        </div>
                        <div class="theme-swatch" title="Violet">
                            <div style="width:48px;height:48px;border-radius:12px;background:#8b5cf6;cursor:pointer;border:3px solid transparent;"></div>
                            <small style="font-size:0.7rem;color:var(--text-muted);text-align:center;display:block;margin-top:6px;">Violet</small>
                        </div>
                        <div class="theme-swatch" title="Emerald">
                            <div style="width:48px;height:48px;border-radius:12px;background:#10b981;cursor:pointer;border:3px solid transparent;"></div>
                            <small style="font-size:0.7rem;color:var(--text-muted);text-align:center;display:block;margin-top:6px;">Emerald</small>
                        </div>
                        <div class="theme-swatch" title="Rose">
                            <div style="width:48px;height:48px;border-radius:12px;background:#f43f5e;cursor:pointer;border:3px solid transparent;"></div>
                            <small style="font-size:0.7rem;color:var(--text-muted);text-align:center;display:block;margin-top:6px;">Rose</small>
                        </div>
                        <div class="theme-swatch" title="Amber">
                            <div style="width:48px;height:48px;border-radius:12px;background:#f59e0b;cursor:pointer;border:3px solid transparent;"></div>
                            <small style="font-size:0.7rem;color:var(--text-muted);text-align:center;display:block;margin-top:6px;">Amber</small>
                        </div>
                    </div>

                    <div class="section-sep"></div>

                    <div style="display:flex;flex-direction:column;gap:16px;">
                        <div>
                            <label style="font-size:0.82rem;font-weight:600;color:var(--text-secondary);text-transform:uppercase;letter-spacing:0.5px;">Sidebar Style</label>
                            <div class="d-flex gap-2 mt-2">
                                <button class="btn-accent" style="font-size:0.8rem;padding:7px 16px;">Dark</button>
                                <button class="btn-accent" style="font-size:0.8rem;padding:7px 16px;background:var(--bg-tertiary);color:var(--text-secondary);">Compact</button>
                            </div>
                        </div>
                        <div>
                            <label style="font-size:0.82rem;font-weight:600;color:var(--text-secondary);text-transform:uppercase;letter-spacing:0.5px;">Font Size</label>
                            <div class="d-flex align-items-center gap-3 mt-2">
                                <span style="font-size:0.75rem;color:var(--text-muted);">Small</span>
                                <input type="range" min="12" max="18" value="14" style="flex:1;accent-color:var(--accent);">
                                <span style="font-size:0.75rem;color:var(--text-muted);">Large</span>
                            </div>
                        </div>
                    </div>

                    <div class="mt-4">
                        <button type="button" class="btn-accent" onclick="showSaved('Theme settings saved!')">
                            <i class="fa-solid fa-floppy-disk me-1"></i> Save Theme
                        </button>
                    </div>
                </div>
            </div>
        </div>

        {{-- ── System Info ──────────────────────────────────────────────── --}}
        <div class="col-12">
            <div class="card-dark">
                <div class="card-dark-header">
                    <h5><i class="fa-solid fa-circle-info me-2" style="color:var(--text-muted);"></i>System Information</h5>
                </div>
                <div class="card-dark-body">
                    <div class="row g-3">
                        @php
                            $info = [
                                ['Application', 'CodXpress Student Management System'],
                                ['Laravel Version', app()->version()],
                                ['PHP Version', PHP_VERSION],
                                ['Environment', config('app.env')],
                                ['Timezone', config('app.timezone')],
                                ['Server Time', now()->format('d M Y, h:i A')],
                            ];
                        @endphp
                        @foreach($info as [$label, $value])
                        <div class="col-12 col-md-6 col-lg-4">
                            <div style="padding:14px;background:var(--bg-tertiary);border-radius:10px;border:1px solid var(--border);">
                                <small style="font-size:0.72rem;text-transform:uppercase;letter-spacing:0.8px;color:var(--text-muted);font-weight:600;">{{ $label }}</small>
                                <p style="margin:4px 0 0;font-size:0.9rem;color:var(--text-primary);font-weight:500;">{{ $value }}</p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

    </div>

</div>
@endsection

@push('css')
<style>
    /* Toggle switch */
    .toggle-switch { position: relative; display: inline-block; width: 46px; height: 24px; cursor: pointer; }
    .toggle-switch input { opacity: 0; width: 0; height: 0; }
    .toggle-slider {
        position: absolute; inset: 0;
        background: var(--bg-primary);
        border: 1px solid var(--border);
        border-radius: 50px;
        transition: 0.3s;
    }
    .toggle-slider::before {
        content: '';
        position: absolute;
        width: 18px; height: 18px;
        left: 3px; top: 2px;
        background: var(--text-muted);
        border-radius: 50%;
        transition: 0.3s;
    }
    .toggle-switch input:checked + .toggle-slider { background: var(--accent); border-color: var(--accent); }
    .toggle-switch input:checked + .toggle-slider::before { background: white; transform: translateX(22px); }
</style>
@endpush

@push('script')
<script>
    function showSaved(msg = 'Changes saved successfully!') {
        Swal.fire({
            title: 'Saved!',
            text: msg,
            icon: 'success',
            timer: 2000,
            showConfirmButton: false,
            background: '#1e293b',
            color: '#f1f5f9',
        });
    }
</script>
@endpush
