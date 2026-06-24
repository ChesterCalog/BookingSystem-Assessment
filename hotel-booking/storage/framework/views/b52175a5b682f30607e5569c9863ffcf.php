<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Grand Horizon Resort & Spa | Luxury Living</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400..900;1,400..900&family=Plus+Jakarta+Sans:wght@200..800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .serif { font-family: 'Playfair Display', serif; }
    </style>
</head>
<body class="bg-[#FAF9F5] text-stone-800 antialiased">

    <nav class="sticky top-0 z-50 bg-white/95 backdrop-blur-md border-b border-stone-100 px-6 lg:px-12 py-4 flex flex-col lg:flex-row justify-between items-center gap-4 lg:gap-0 shadow-sm">
        
        <div class="flex items-center space-x-2">
            <span class="text-2xl text-amber-700">✨</span>
            <span class="serif text-xl font-bold tracking-widest uppercase text-stone-900">Grand Horizon</span>
        </div>
        
        <div class="hidden md:flex items-center space-x-8 text-xs font-bold tracking-widest uppercase text-stone-600">
            <a href="#amenities" class="hover:text-amber-800 transition">The Resort Experience</a>
            <a href="#products" class="hover:text-amber-800 transition">Our Accommodations</a>
        </div>

        <div class="flex flex-wrap items-center justify-center gap-4 text-xs font-bold tracking-widest uppercase w-full lg:w-auto">
            <?php if(Route::has('login')): ?>
                <?php if(auth()->guard()->check()): ?>
                    <?php if(auth()->user()->role === 'customer'): ?>
                        <a href="<?php echo e(route('member.dashboard')); ?>" class="text-stone-600 hover:text-amber-700 transition px-3 py-2">Go to Dashboard</a>
                    <?php else: ?>
                        <a href="<?php echo e(route('staff.portal')); ?>" class="text-stone-600 hover:text-amber-700 transition px-3 py-2">Staff Portal</a>
                    <?php endif; ?>
                    <form method="POST" action="<?php echo e(route('logout')); ?>" class="inline m-0 p-0">
                        <?php echo csrf_field(); ?>
                        <button type="submit" class="text-stone-400 hover:text-red-700 transition px-3 py-2 tracking-widest uppercase font-bold text-xs cursor-pointer bg-transparent border-0 p-0 m-0">Log Out</button>
                    </form>
                <?php else: ?>
                    <a href="<?php echo e(route('staff.login')); ?>" class="text-stone-500 hover:text-stone-800 border border-stone-200 hover:border-stone-400 px-4 py-2.5 rounded-xl transition bg-stone-50/50">
                        Staff Portal
                    </a>
                    
                    <a href="<?php echo e(route('login')); ?>" class="text-stone-600 hover:text-amber-800 px-2 py-2.5 transition">
                        Log In
                    </a>
                    
                    <?php if(Route::has('register')): ?>
                        <a href="<?php echo e(route('register')); ?>" class="bg-amber-800 text-white px-5 py-2.5 rounded-xl hover:bg-amber-900 transition shadow-sm text-center">
                            Join Membership
                        </a>
                    <?php endif; ?>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </nav>

    <?php if(session('success')): ?>
        <div class="bg-emerald-600 text-white text-center py-3 px-4 text-xs font-bold tracking-wider uppercase shadow-md">
            ✨ <?php echo e(session('success')); ?>

        </div>
    <?php endif; ?>

    <header class="relative h-[85vh] flex items-center justify-center bg-stone-900 overflow-hidden">
        <img src="https://images.unsplash.com/photo-1571896349842-33c89424de2d?auto=format&fit=crop&w=1920&q=80" alt="Resort Pool" class="absolute inset-0 w-full h-full object-cover opacity-60">
        <div class="absolute inset-0 bg-gradient-to-t from-[#FAF9F5] via-transparent to-black/30"></div>
        
        <div class="relative z-10 text-center max-w-3xl mx-auto px-4 space-y-5">
            <span class="text-xs font-bold tracking-widest uppercase text-amber-400 bg-amber-950/40 px-4 py-2 rounded-full backdrop-blur-sm">An Unrivaled Escape</span>
            <h1 class="serif text-5xl md:text-7xl font-normal text-white leading-tight">Reimagine Luxury at the Edge of the Horizon</h1>
            <p class="text-stone-200 text-sm md:text-base font-light max-w-xl mx-auto leading-relaxed">An exclusive sanctuary blending pristine coastal architecture with unmatched, personalized hospitality.</p>
            <div class="pt-4">
                <a href="#products" class="bg-amber-800 text-white font-bold text-xs tracking-widest uppercase px-10 py-5 rounded-xl hover:bg-amber-900 transition shadow-2xl inline-block transform hover:-translate-y-0.5 duration-200">
                    Explore Accommodations As Guest
                </a>
            </div>
        </div>
    </header>

    <section id="amenities" class="max-w-7xl mx-auto px-6 lg:px-8 py-24 space-y-16">
        <div class="text-center max-w-xl mx-auto space-y-3">
            <span class="text-xs font-bold tracking-widest uppercase text-amber-800">The Horizon Way</span>
            <h2 class="serif text-3xl md:text-4xl font-bold text-stone-900">A World of Elevated Comfort</h2>
            <p class="text-sm text-stone-500 font-light leading-relaxed">Every structural feature is tailored exclusively to satisfy the parameters of absolute physical restoration.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="bg-white p-10 rounded-3xl border border-stone-200/60 shadow-sm space-y-4 hover:shadow-md transition">
                <div class="w-12 h-12 bg-amber-50 rounded-xl flex items-center justify-center text-xl">🌊</div>
                <h3 class="serif text-lg font-bold text-stone-800">Prime Oceanfront Views</h3>
                <p class="text-xs text-stone-500 leading-relaxed font-light">Wake up directly to unhindered ocean horizons and pristine private beaches just steps from your outdoor terrace.</p>
            </div>
            <div class="bg-white p-10 rounded-3xl border border-stone-200/60 shadow-sm space-y-4 hover:shadow-md transition">
                <div class="w-12 h-12 bg-amber-50 rounded-xl flex items-center justify-center text-xl">🍽️</div>
                <h3 class="serif text-lg font-bold text-stone-800">Bespoke Culinary Artisans</h3>
                <p class="text-xs text-stone-500 leading-relaxed font-light">Indulge in seasonal menus prepared by international award-winning chefs using entirely locally sourced premium ingredients.</p>
            </div>
            <div class="bg-white p-10 rounded-3xl border border-stone-200/60 shadow-sm space-y-4 hover:shadow-md transition">
                <div class="w-12 h-12 bg-amber-50 rounded-xl flex items-center justify-center text-xl">🔒</div>
                <h3 class="serif text-lg font-bold text-stone-800">Absolute Privacy Guard</h3>
                <p class="text-xs text-stone-500 leading-relaxed font-light">Enjoy frictionless digital guest services, private entry options, and an ultra-secure environment tailored for complete serenity.</p>
            </div>
        </div>
    </section>

    <section id="products" class="bg-stone-100/80 py-24 border-t border-stone-200/60">
        <div class="max-w-7xl mx-auto px-6 lg:px-8 space-y-16">
            <div class="text-center max-w-2xl mx-auto space-y-3">
                <span class="text-xs font-bold tracking-widest uppercase text-amber-800">Curated Living Spaces</span>
                <h2 class="serif text-3xl md:text-4xl font-bold text-stone-900">Our Premium Accommodations</h2>
                <p class="text-sm text-stone-500 font-light leading-relaxed">Explore our signature residential items. Each tier is structurally designed with integrated premium smart systems and panoramic landscape integration.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <?php $__empty_1 = true; $__currentLoopData = $roomTypes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $room): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <div class="bg-white rounded-3xl overflow-hidden shadow-sm border border-stone-200/40 group flex flex-col justify-between transform hover:-translate-y-1 transition duration-300">
                        <div class="relative overflow-hidden aspect-[4/3] bg-stone-200">
                            <?php if(str_contains(strtolower($room->name), 'deluxe')): ?>
                                <img src="https://images.unsplash.com/photo-1618773928121-c32242e63f39?auto=format&fit=crop&w=800&q=80" alt="Deluxe Suite" class="w-full h-full object-cover">
                            <?php elseif(str_contains(strtolower($room->name), 'standard')): ?>
                                <img src="https://images.unsplash.com/photo-1566665797739-1674de7a421a?auto=format&fit=crop&w=800&q=80" alt="Standard Room" class="w-full h-full object-cover">
                            <?php else: ?>
                                <img src="https://images.unsplash.com/photo-1590490360182-c33d57733427?auto=format&fit=crop&w=800&q=80" alt="Executive Suite" class="w-full h-full object-cover">
                            <?php endif; ?>

                            <div class="absolute top-4 right-4 bg-stone-900 text-white px-3 py-1.5 rounded-lg text-xs font-bold shadow-md">
                                $<?php echo e(number_format($room->base_price, 2)); ?> / Night
                            </div>
                        </div>

                        <div class="p-8 space-y-6 flex-grow flex flex-col justify-between">
                            <div class="space-y-3">
                                <h3 class="serif text-2xl font-bold text-stone-900"><?php echo e($room->name); ?></h3>
                                <p class="text-xs text-stone-500 leading-relaxed font-light">An elegant minimalist layout prioritizing absolute comfort. Optimized spatial ergonomics, premium linen setups, and custom workspace integrations.</p>
                            </div>

                            <div class="pt-2">
                                <a href="<?php echo e(route('booking.create')); ?>" class="w-full text-center bg-stone-900 text-white py-3 rounded-xl text-xs font-bold tracking-widest uppercase hover:bg-amber-800 transition block">
                                    Reserve This Space
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <div class="bg-white rounded-3xl overflow-hidden shadow-sm border border-stone-200/40 group flex flex-col justify-between">
                        <div class="relative overflow-hidden aspect-[4/3]">
                            <img src="https://images.unsplash.com/photo-1618773928121-c32242e63f39?auto=format&fit=crop&w=800&q=80" alt="Deluxe Suite" class="w-full h-full object-cover">
                            <div class="absolute top-4 right-4 bg-stone-900 text-white px-3 py-1.5 rounded-lg text-xs font-bold shadow-md">$350.00 / Night</div>
                        </div>
                        <div class="p-8 space-y-6 flex-grow flex flex-col justify-between">
                            <div class="space-y-3">
                                <h3 class="serif text-2xl font-bold text-stone-900">Horizon Deluxe Suite</h3>
                                <p class="text-xs text-stone-500 leading-relaxed font-light">Panoramic ocean view design featuring soundproof structural architecture and custom materials layout.</p>
                            </div>
                            <div class="pt-2">
                                <a href="<?php echo e(route('booking.create')); ?>" class="w-full text-center bg-stone-900 text-white py-3 rounded-xl text-xs font-bold tracking-widest uppercase hover:bg-amber-800 transition block">Reserve This Space</a>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <footer class="bg-stone-900 text-stone-500 text-xs py-10 border-t border-stone-800 text-center">
        <p class="font-light tracking-wide">&copy; 2026 Grand Horizon Luxury Resorts Group Ltd. Public Interface Active.</p>
    </footer>

</body>
</html><?php /**PATH C:\Users\Licensed User\Documents\GitHub\BookingSystem-Assessment\hotel-booking\resources\views/welcome.blade.php ENDPATH**/ ?>