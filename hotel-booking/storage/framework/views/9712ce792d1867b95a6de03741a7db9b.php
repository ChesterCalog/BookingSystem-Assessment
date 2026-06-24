<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>My Dashboard — Grand Horizon</title>
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400..900;1,400..900&family=Plus+Jakarta+Sans:wght@200..800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .serif { font-family: 'Playfair Display', serif; }
        /* ── DESIGN TOKENS ─────────────────────────────────────────── */
        :root {
            --cream:        #F5F1EA;
            --cream-dark:   #EDE8DF;
            --brown-deep:   #1A1109;
            --brown-mid:    #5C3D1E;
            --terracotta:   #8B3A10;
            --terra-hover:  #72300D;
            --gold:         #C8902A;
            --gold-light:   #F0C96A;
            --muted:        #8A7C6E;
            --border:       #D9D0C3;
            --white:        #FFFFFF;
            --status-pending-bg:   #FEF3C7;
            --status-pending-text: #92400E;
            --status-confirmed-bg:   #D1FAE5;
            --status-confirmed-text: #065F46;
            --status-cancelled-bg:   #FEE2E2;
            --status-cancelled-text: #991B1B;
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            background-color: var(--cream);
            color: var(--brown-deep);
            font-family: 'Plus Jakarta Sans', ui-sans-serif, system-ui, sans-serif;
            min-height: 100vh;
        }

        /* ── PAGE LAYOUT ────────────────────────────────────────────── */
        .page-wrapper {
            max-width: 1200px;
            margin: 0 auto;
            padding: 2.5rem 2rem 4rem;
        }

        /* ── PAGE HEADER ────────────────────────────────────────────── */
        .page-header {
            margin-bottom: 2.5rem;
        }
        .page-header-eyebrow {
            font-size: 0.7rem;
            font-weight: 600;
            letter-spacing: 0.15em;
            text-transform: uppercase;
            color: var(--gold);
            margin-bottom: 0.35rem;
        }
        .page-header h1 {
            font-size: 2rem;
            font-weight: 700;
            color: var(--brown-deep);
            letter-spacing: -0.01em;
        }
        .page-header p {
            margin-top: 0.4rem;
            font-size: 0.9rem;
            color: var(--muted);
        }

        /* ── FLASH MESSAGES ─────────────────────────────────────────── */
        .flash {
            padding: 0.85rem 1.25rem;
            border-radius: 8px;
            font-size: 0.85rem;
            margin-bottom: 1.5rem;
            border-left: 3px solid;
        }
        .flash.success {
            background: var(--status-confirmed-bg);
            color: var(--status-confirmed-text);
            border-color: #34D399;
        }
        .flash.error {
            background: var(--status-cancelled-bg);
            color: var(--status-cancelled-text);
            border-color: #F87171;
        }

        /* ── CARD ───────────────────────────────────────────────────── */
        .card {
            background: var(--white);
            border-radius: 12px;
            border: 1px solid var(--border);
            overflow: hidden;
        }
        .card-header {
            padding: 1.4rem 1.75rem;
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .card-header h2 {
            font-size: 1.2rem;
            font-weight: 700;
            color: var(--brown-deep);
        }
        .card-header-sub {
            font-size: 0.8rem;
            color: var(--muted);
            margin-top: 0.15rem;
        }

        /* ── BOOKINGS TABLE ─────────────────────────────────────────── */
        .bookings-wrap {
            margin-bottom: 2rem;
        }
        .bookings-table-container {
            overflow-x: auto;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        thead th {
            padding: 0.9rem 1.25rem;
            font-size: 0.68rem;
            font-weight: 600;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            color: var(--muted);
            background: var(--cream);
            text-align: left;
            border-bottom: 1px solid var(--border);
            white-space: nowrap;
        }
        tbody tr {
            border-bottom: 1px solid var(--border);
            transition: background 0.15s;
        }
        tbody tr:last-child { border-bottom: none; }
        tbody tr:hover { background: #FAFAF8; }
        tbody td {
            padding: 1rem 1.25rem;
            font-size: 0.85rem;
            color: var(--brown-deep);
            vertical-align: middle;
        }
        .td-id {
            font-size: 0.78rem;
            color: var(--muted);
            font-family: ui-monospace, monospace;
            white-space: nowrap;
        }
        .td-room-name {
            font-weight: 600;
            line-height: 1.2;
        }
        .td-room-type {
            font-size: 0.75rem;
            color: var(--muted);
        }
        .td-date { white-space: nowrap; color: var(--muted); font-size: 0.82rem; }
        .td-amount {
            font-weight: 700;
            white-space: nowrap;
        }

        /* Status badges */
        .badge {
            display: inline-block;
            padding: 0.25rem 0.75rem;
            border-radius: 999px;
            font-size: 0.72rem;
            font-weight: 600;
            white-space: nowrap;
        }
        .badge-pending {
            background: var(--status-pending-bg);
            color: var(--status-pending-text);
        }
        .badge-confirmed {
            background: var(--status-confirmed-bg);
            color: var(--status-confirmed-text);
        }
        .badge-cancelled {
            background: var(--status-cancelled-bg);
            color: var(--status-cancelled-text);
        }

        /* Empty state */
        .empty-state {
            padding: 3.5rem 1.5rem;
            text-align: center;
        }
        .empty-state-icon {
            font-size: 2.5rem;
            margin-bottom: 0.75rem;
            opacity: 0.4;
        }
        .empty-state h3 {
            font-size: 1rem;
            font-weight: 600;
            color: var(--brown-deep);
            margin-bottom: 0.35rem;
        }
        .empty-state p { font-size: 0.82rem; color: var(--muted); }
        .empty-state a {
            display: inline-block;
            margin-top: 1.25rem;
            padding: 0.6rem 1.5rem;
            background: var(--terracotta);
            color: var(--white);
            border-radius: 6px;
            font-size: 0.78rem;
            font-weight: 600;
            letter-spacing: 0.06em;
            text-transform: uppercase;
            text-decoration: none;
            transition: background 0.2s;
        }
        .empty-state a:hover { background: var(--terra-hover); }

        /* ── PROFILE AVATAR SECTION ─────────────────────────────────── */
        .avatar-section {
            margin-top: 2rem;
        }
        .avatar-grid {
            display: grid;
            grid-template-columns: repeat(6, 1fr);
            gap: 1rem;
            padding: 1.75rem;
        }
        @media (max-width: 768px) {
            .avatar-grid { grid-template-columns: repeat(3, 1fr); }
        }
        @media (max-width: 480px) {
            .avatar-grid { grid-template-columns: repeat(2, 1fr); }
        }

        .avatar-option {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 0.5rem;
            cursor: pointer;
        }
        .avatar-option input[type="radio"] { display: none; }
        .avatar-ring {
            width: 80px; height: 80px;
            border-radius: 50%;
            border: 2.5px solid var(--border);
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
            background: var(--cream-dark);
            transition: border-color 0.2s, box-shadow 0.2s, transform 0.15s;
        }
        .avatar-ring svg { width: 72px; height: 72px; }
        .avatar-option input[type="radio"]:checked + .avatar-ring {
            border-color: var(--gold);
            box-shadow: 0 0 0 3px rgba(200, 144, 42, 0.25);
        }
        .avatar-option:hover .avatar-ring {
            border-color: var(--gold-light);
            transform: translateY(-2px);
        }
        .avatar-label {
            font-size: 0.7rem;
            color: var(--muted);
            font-weight: 500;
        }

        .avatar-actions {
            padding: 0 1.75rem 1.75rem;
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        .btn-primary {
            padding: 0.65rem 1.75rem;
            background: var(--terracotta);
            color: var(--white);
            border: none;
            border-radius: 6px;
            font-size: 0.8rem;
            font-weight: 600;
            letter-spacing: 0.06em;
            text-transform: uppercase;
            cursor: pointer;
            transition: background 0.2s;
        }
        .btn-primary:hover { background: var(--terra-hover); }
        .avatar-hint {
            font-size: 0.76rem;
            color: var(--muted);
        }


    </style>
</head>
<body>


<nav class="sticky top-0 z-50 bg-white/95 backdrop-blur-md border-b border-stone-100 px-6 lg:px-12 py-4 flex flex-col lg:flex-row justify-between items-center gap-4 lg:gap-0 shadow-sm">

    <div class="flex items-center space-x-2">
        <span class="text-2xl text-amber-700">✨</span>
        <span class="serif text-xl font-bold tracking-widest uppercase text-stone-900">Grand Horizon</span>
    </div>

    <div class="hidden md:flex items-center space-x-8 text-xs font-bold tracking-widest uppercase text-stone-600">
        <a href="<?php echo e(url('/')); ?>#amenities" class="hover:text-amber-800 transition">The Resort Experience</a>
        <a href="<?php echo e(url('/')); ?>#products" class="hover:text-amber-800 transition">Our Accommodations</a>
    </div>

    <div class="flex flex-wrap items-center justify-center gap-4 text-xs font-bold tracking-widest uppercase w-full lg:w-auto">
        
        
        <div class="flex items-center gap-3 px-3 py-2">
            <span class="text-stone-600"><?php echo e(Auth::user()->name); ?></span>
            <div class="w-8 h-8 rounded-full border-2 border-amber-600 overflow-hidden flex items-center justify-center bg-stone-100">
                <?php echo $__env->make('partials.avatar', ['avatar' => Auth::user()->avatar ?? 'male_1', 'size' => 32], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            </div>
        </div>

        <?php if(Auth::user()->role === 'staff' || Auth::user()->role === 'admin'): ?>
            <a href="<?php echo e(route('staff.portal')); ?>" class="text-stone-500 hover:text-stone-800 border border-stone-200 hover:border-stone-400 px-4 py-2.5 rounded-xl transition bg-stone-50/50">
                Staff Portal
            </a>
        <?php endif; ?>

        <form method="POST" action="<?php echo e(route('logout')); ?>" class="inline m-0 p-0">
            <?php echo csrf_field(); ?>
            <button type="submit" class="text-stone-400 hover:text-red-700 transition px-3 py-2 tracking-widest uppercase font-bold text-xs cursor-pointer bg-transparent border-0 p-0 m-0">
                Log Out
            </button>
        </form>
    </div>

</nav>


<div class="page-wrapper">

    
    <div class="page-header">
        <div class="page-header-eyebrow">Member Dashboard</div>
        <h1>Welcome back, <?php echo e(explode(' ', Auth::user()->name)[0]); ?></h1>
        <p>Track your reservation status and manage your member profile.</p>
    </div>

    
    <?php if(session('success')): ?>
        <div class="flash success"><?php echo e(session('success')); ?></div>
    <?php endif; ?>
    <?php if(session('error')): ?>
        <div class="flash error"><?php echo e(session('error')); ?></div>
    <?php endif; ?>

    
    <div class="bookings-wrap">
        <div class="card">
            <div class="card-header">
                <div>
                    <h2>Booking Tickets</h2>
                    <div class="card-header-sub">All reservations linked to your account</div>
                </div>
            </div>

            <div class="bookings-table-container">
                <?php if($bookings->isEmpty()): ?>
                    <div class="empty-state">
                        <div class="empty-state-icon">🛎️</div>
                        <h3>No reservations yet</h3>
                        <p>Your confirmed bookings will appear here once you make a reservation.</p>
                        <a href="<?php echo e(url('/')); ?>#products">Browse Accommodations</a> 
                    </div>
                <?php else: ?>
                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Room / Type</th>
                                <th>Check-In</th>
                                <th>Check-Out</th>
                                <th>Amount</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $bookings; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $booking): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td class="td-id"><?php echo e($booking->booking_code); ?></td>
                                <td>
                                    
                                    <div class="td-room-name">
                                        <?php echo e($booking->roomType->name ?? 'Room'); ?>

                                    </div>
                                    <div class="td-room-type">
                                        <?php echo e($booking->roomType->name ?? '—'); ?>

                                    </div>
                                </td>
                                <td class="td-date"><?php echo e($booking->check_in->format('Y-m-d')); ?></td>
                                <td class="td-date"><?php echo e($booking->check_out->format('Y-m-d')); ?></td>
                                <td class="td-amount">
                                    $<?php echo e(number_format($booking->total_price, 0)); ?>

                                </td>
                                <td>
                                    <?php
                                        $statusClass = match(strtolower($booking->status)) {
                                            'confirmed' => 'badge-confirmed',
                                            'cancelled' => 'badge-cancelled',
                                            default     => 'badge-pending',
                                        };
                                    ?>
                                    <span class="badge <?php echo e($statusClass); ?>">
                                        <?php echo e(ucfirst($booking->status)); ?>

                                    </span>
                                </td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                <?php endif; ?>
            </div>
        </div>
    </div>

    
    <?php if(Auth::user()->role !== 'guest'): ?>
    <div class="avatar-section">
        <div class="card">
            <div class="card-header">
                <div>
                    <h2>Profile Avatar</h2>
                    <div class="card-header-sub">Choose the avatar that represents you across Grand Horizon</div>
                </div>
            </div>

            <form method="POST" action="<?php echo e(route('dashboard.avatar')); ?>">
                <?php echo csrf_field(); ?>
                <div class="avatar-grid">

                    
                    <label class="avatar-option">
                        <input type="radio" name="avatar" value="male_1"
                            <?php echo e((Auth::user()->avatar ?? 'male_1') === 'male_1' ? 'checked' : ''); ?>>
                        <div class="avatar-ring">
                            
                            <svg viewBox="0 0 72 72" xmlns="http://www.w3.org/2000/svg">
                                <circle cx="36" cy="36" r="36" fill="#EDE8DF"/>
                                
                                <path d="M14 68 Q14 52 36 52 Q58 52 58 68" fill="#5C3D1E"/>
                                
                                <rect x="30" y="44" width="12" height="10" rx="3" fill="#F5CBA7"/>
                                
                                <ellipse cx="36" cy="34" rx="14" ry="16" fill="#F5CBA7"/>
                                
                                <path d="M22 28 Q22 16 36 15 Q50 16 50 28 L50 24 Q50 12 36 12 Q22 12 22 24 Z" fill="#3D2B1A"/>
                                
                                <ellipse cx="30" cy="33" rx="2" ry="2.2" fill="#2C1810"/>
                                <ellipse cx="42" cy="33" rx="2" ry="2.2" fill="#2C1810"/>
                                
                                <circle cx="31" cy="32.3" r="0.6" fill="white"/>
                                <circle cx="43" cy="32.3" r="0.6" fill="white"/>
                                
                                <ellipse cx="36" cy="38" rx="1.5" ry="1" fill="#E0A882"/>
                                
                                <path d="M31 42 Q36 45 41 42" stroke="#C0806A" stroke-width="1.2" fill="none" stroke-linecap="round"/>
                                
                                <path d="M26 54 L32 50 L36 55 L40 50 L46 54" stroke="white" stroke-width="1.5" fill="none"/>
                            </svg>
                        </div>
                        <span class="avatar-label">Classic</span>
                    </label>

                    <label class="avatar-option">
                        <input type="radio" name="avatar" value="male_2"
                            <?php echo e((Auth::user()->avatar ?? 'male_1') === 'male_2' ? 'checked' : ''); ?>>
                        <div class="avatar-ring">
                            
                            <svg viewBox="0 0 72 72" xmlns="http://www.w3.org/2000/svg">
                                <circle cx="36" cy="36" r="36" fill="#EDE8DF"/>
                                <path d="M14 68 Q14 52 36 52 Q58 52 58 68" fill="#4A3728"/>
                                <rect x="30" y="44" width="12" height="10" rx="3" fill="#FDDCB0"/>
                                <ellipse cx="36" cy="34" rx="14" ry="16" fill="#FDDCB0"/>
                                
                                <path d="M22 30 Q21 18 30 14 Q40 11 50 16 Q52 20 50 28 Q48 18 38 16 Q26 15 24 26 Z" fill="#7B4F2A"/>
                                <path d="M22 30 Q22 22 24 26" fill="#7B4F2A"/>
                                
                                <path d="M22 28 Q20 22 24 18 Q22 22 22 28 Z" fill="#7B4F2A"/>
                                <ellipse cx="30" cy="33" rx="2" ry="2.2" fill="#2C1810"/>
                                <ellipse cx="42" cy="33" rx="2" ry="2.2" fill="#2C1810"/>
                                <circle cx="31" cy="32.3" r="0.6" fill="white"/>
                                <circle cx="43" cy="32.3" r="0.6" fill="white"/>
                                <ellipse cx="36" cy="38" rx="1.5" ry="1" fill="#E8BB8A"/>
                                <path d="M31 42 Q36 45.5 41 42" stroke="#C07858" stroke-width="1.2" fill="none" stroke-linecap="round"/>
                                
                                <path d="M28 43 Q36 47 44 43" stroke="#D4A070" stroke-width="0.6" fill="none" stroke-dasharray="1,2"/>
                                <path d="M26 54 L32 50 L36 55 L40 50 L46 54" stroke="#B0C4DE" stroke-width="1.5" fill="none"/>
                            </svg>
                        </div>
                        <span class="avatar-label">Dapper</span>
                    </label>

                    <label class="avatar-option">
                        <input type="radio" name="avatar" value="male_3"
                            <?php echo e((Auth::user()->avatar ?? 'male_1') === 'male_3' ? 'checked' : ''); ?>>
                        <div class="avatar-ring">
                            
                            <svg viewBox="0 0 72 72" xmlns="http://www.w3.org/2000/svg">
                                <circle cx="36" cy="36" r="36" fill="#EDE8DF"/>
                                <path d="M14 68 Q14 52 36 52 Q58 52 58 68" fill="#2C1A0E"/>
                                <rect x="30" y="44" width="12" height="10" rx="3" fill="#C68642"/>
                                <ellipse cx="36" cy="34" rx="14" ry="16" fill="#C68642"/>
                                
                                <circle cx="24" cy="26" r="5" fill="#1A0D05"/>
                                <circle cx="30" cy="20" r="5.5" fill="#1A0D05"/>
                                <circle cx="37" cy="18" r="5.5" fill="#1A0D05"/>
                                <circle cx="44" cy="20" r="5" fill="#1A0D05"/>
                                <circle cx="49" cy="26" r="4.5" fill="#1A0D05"/>
                                <circle cx="22" cy="30" r="4" fill="#1A0D05"/>
                                <circle cx="50" cy="30" r="4" fill="#1A0D05"/>
                                <ellipse cx="30" cy="33" rx="2" ry="2.2" fill="#1A0A04"/>
                                <ellipse cx="42" cy="33" rx="2" ry="2.2" fill="#1A0A04"/>
                                <circle cx="31" cy="32.3" r="0.6" fill="white"/>
                                <circle cx="43" cy="32.3" r="0.6" fill="white"/>
                                <ellipse cx="36" cy="38" rx="1.5" ry="1" fill="#A86830"/>
                                <path d="M31 42 Q36 45 41 42" stroke="#8B4513" stroke-width="1.2" fill="none" stroke-linecap="round"/>
                                <path d="M26 54 L32 50 L36 55 L40 50 L46 54" stroke="white" stroke-width="1.5" fill="none"/>
                            </svg>
                        </div>
                        <span class="avatar-label">Curly</span>
                    </label>

                    
                    <label class="avatar-option">
                        <input type="radio" name="avatar" value="female_1"
                            <?php echo e((Auth::user()->avatar ?? 'male_1') === 'female_1' ? 'checked' : ''); ?>>
                        <div class="avatar-ring">
                            
                            <svg viewBox="0 0 72 72" xmlns="http://www.w3.org/2000/svg">
                                <circle cx="36" cy="36" r="36" fill="#EDE8DF"/>
                                <path d="M14 68 Q14 52 36 52 Q58 52 58 68" fill="#7B4EA0"/>
                                <rect x="30" y="44" width="12" height="10" rx="3" fill="#F5CBA7"/>
                                
                                <path d="M20 28 Q18 48 22 60 Q28 56 28 52 Q26 42 24 28" fill="#2C1810"/>
                                <path d="M52 28 Q54 48 50 60 Q44 56 44 52 Q46 42 48 28" fill="#2C1810"/>
                                <ellipse cx="36" cy="34" rx="14" ry="16" fill="#F5CBA7"/>
                                
                                <path d="M22 30 Q22 16 36 15 Q50 16 50 30 L50 26 Q50 12 36 12 Q22 12 22 26 Z" fill="#2C1810"/>
                                <ellipse cx="30" cy="33" rx="1.8" ry="2" fill="#2C1810"/>
                                <ellipse cx="42" cy="33" rx="1.8" ry="2" fill="#2C1810"/>
                                
                                <path d="M27.5 30.5 L28.5 29.5 M30 30 L30 29 M32.5 30.5 L31.5 29.5" stroke="#2C1810" stroke-width="0.8"/>
                                <path d="M39.5 30.5 L40.5 29.5 M42 30 L42 29 M44.5 30.5 L43.5 29.5" stroke="#2C1810" stroke-width="0.8"/>
                                <circle cx="31" cy="32.5" r="0.5" fill="white"/>
                                <circle cx="43" cy="32.5" r="0.5" fill="white"/>
                                <ellipse cx="36" cy="38" rx="1.2" ry="0.9" fill="#E0A882"/>
                                
                                <path d="M32 42 Q34 44 36 43 Q38 44 40 42 Q38 45 36 44.5 Q34 45 32 42Z" fill="#D4706A"/>
                                
                                <ellipse cx="26" cy="38" rx="3" ry="1.5" fill="#FFBFB5" opacity="0.5"/>
                                <ellipse cx="46" cy="38" rx="3" ry="1.5" fill="#FFBFB5" opacity="0.5"/>
                            </svg>
                        </div>
                        <span class="avatar-label">Elegant</span>
                    </label>

                    <label class="avatar-option">
                        <input type="radio" name="avatar" value="female_2"
                            <?php echo e((Auth::user()->avatar ?? 'male_1') === 'female_2' ? 'checked' : ''); ?>>
                        <div class="avatar-ring">
                            
                            <svg viewBox="0 0 72 72" xmlns="http://www.w3.org/2000/svg">
                                <circle cx="36" cy="36" r="36" fill="#EDE8DF"/>
                                <path d="M14 68 Q14 52 36 52 Q58 52 58 68" fill="#C8902A"/>
                                <rect x="30" y="44" width="12" height="10" rx="3" fill="#FDDCB0"/>
                                
                                <path d="M22 28 Q20 42 24 50 Q28 48 28 44 Q24 38 24 28" fill="#8B4513"/>
                                <path d="M50 28 Q52 42 48 50 Q44 48 44 44 Q48 38 48 28" fill="#8B4513"/>
                                <ellipse cx="36" cy="34" rx="14" ry="16" fill="#FDDCB0"/>
                                
                                <path d="M22 28 Q22 14 36 13 Q50 14 50 28 L50 24 Q50 11 36 11 Q22 11 22 24 Z" fill="#8B4513"/>
                                
                                <path d="M22 24 Q28 30 36 27 Q44 30 50 24 Q44 22 36 22 Q28 22 22 24 Z" fill="#8B4513"/>
                                <ellipse cx="30" cy="34" rx="1.8" ry="2" fill="#2C1810"/>
                                <ellipse cx="42" cy="34" rx="1.8" ry="2" fill="#2C1810"/>
                                <path d="M27.5 31.5 L28.5 30.5 M30 31 L30 30 M32.5 31.5 L31.5 30.5" stroke="#2C1810" stroke-width="0.8"/>
                                <path d="M39.5 31.5 L40.5 30.5 M42 31 L42 30 M44.5 31.5 L43.5 30.5" stroke="#2C1810" stroke-width="0.8"/>
                                <circle cx="31" cy="33.5" r="0.5" fill="white"/>
                                <circle cx="43" cy="33.5" r="0.5" fill="white"/>
                                <ellipse cx="36" cy="38.5" rx="1.2" ry="0.9" fill="#E8BB8A"/>
                                <path d="M32 43 Q34 45 36 44 Q38 45 40 43 Q38 46 36 45.5 Q34 46 32 43Z" fill="#D4706A"/>
                                <ellipse cx="26" cy="39" rx="3" ry="1.5" fill="#FFBFB5" opacity="0.5"/>
                                <ellipse cx="46" cy="39" rx="3" ry="1.5" fill="#FFBFB5" opacity="0.5"/>
                            </svg>
                        </div>
                        <span class="avatar-label">Chic</span>
                    </label>

                    <label class="avatar-option">
                        <input type="radio" name="avatar" value="female_3"
                            <?php echo e((Auth::user()->avatar ?? 'male_1') === 'female_3' ? 'checked' : ''); ?>>
                        <div class="avatar-ring">
                            
                            <svg viewBox="0 0 72 72" xmlns="http://www.w3.org/2000/svg">
                                <circle cx="36" cy="36" r="36" fill="#EDE8DF"/>
                                <path d="M14 68 Q14 52 36 52 Q58 52 58 68" fill="#2E4A6B"/>
                                <rect x="30" y="44" width="12" height="10" rx="3" fill="#A0522D"/>
                                <ellipse cx="36" cy="34" rx="14" ry="16" fill="#A0522D"/>
                                
                                <path d="M22 28 Q22 16 36 15 Q50 16 50 28 L50 24 Q50 12 36 12 Q22 12 22 24 Z" fill="#1A0D05"/>
                                
                                <circle cx="36" cy="13" r="6" fill="#1A0D05"/>
                                <circle cx="36" cy="13" r="4" fill="#2C1A0E"/>
                                
                                <path d="M33 10 Q36 8 39 10" stroke="#4A2C14" stroke-width="0.8" fill="none"/>
                                
                                <path d="M22 26 Q22 30 24 32" stroke="#1A0D05" stroke-width="2" fill="none"/>
                                <path d="M50 26 Q50 30 48 32" stroke="#1A0D05" stroke-width="2" fill="none"/>
                                <ellipse cx="30" cy="33" rx="1.8" ry="2" fill="#1A0A04"/>
                                <ellipse cx="42" cy="33" rx="1.8" ry="2" fill="#1A0A04"/>
                                <path d="M27.5 30.5 L28.5 29.5 M30 30 L30 29 M32.5 30.5 L31.5 29.5" stroke="#1A0A04" stroke-width="0.8"/>
                                <path d="M39.5 30.5 L40.5 29.5 M42 30 L42 29 M44.5 30.5 L43.5 30.5" stroke="#1A0A04" stroke-width="0.8"/>
                                <circle cx="31" cy="32.5" r="0.5" fill="white"/>
                                <circle cx="43" cy="32.5" r="0.5" fill="white"/>
                                <ellipse cx="36" cy="38" rx="1.2" ry="0.9" fill="#7A3B10"/>
                                <path d="M32 42 Q34 44 36 43.5 Q38 44 40 42 Q38 45 36 44.5 Q34 45 32 42Z" fill="#C05A50"/>
                                <ellipse cx="26" cy="38" rx="3" ry="1.5" fill="#FF9080" opacity="0.35"/>
                                <ellipse cx="46" cy="38" rx="3" ry="1.5" fill="#FF9080" opacity="0.35"/>
                            </svg>
                        </div>
                        <span class="avatar-label">Radiant</span>
                    </label>

                </div>

                <div class="avatar-actions">
                    <button type="submit" class="btn-primary">Save Avatar</button>
                    <span class="avatar-hint">Your avatar is visible to resort staff during check-in.</span>
                </div>
            </form>
        </div>
    </div>
    <?php endif; ?>

</div>
</body>
</html>
<?php /**PATH C:\Users\Licensed User\Documents\GitHub\BookingSystem-Assessment\hotel-booking\resources\views/dashboard.blade.php ENDPATH**/ ?>