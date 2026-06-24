<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Join Membership | Grand Horizon Resort</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400..900;1,400..900&family=Plus+Jakarta+Sans:wght@200..800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .serif { font-family: 'Playfair Display', serif; }
    </style>
</head>
<body class="bg-[#FAF9F5] text-stone-800 antialiased min-h-screen flex flex-col justify-center py-12 sm:px-6 lg:px-8">

    <div class="sm:mx-auto sm:w-full sm:max-w-md text-center">
        <span class="text-3xl text-amber-700 block mb-2">✨</span>
        <h2 class="serif mt-2 text-center text-3xl font-bold tracking-tight text-stone-900">Become a Member</h2>
        <p class="mt-2 text-center text-sm text-stone-500 font-light">
            Already hold a membership?
            <a href="<?php echo e(route('login')); ?>" class="font-medium text-amber-700 hover:text-amber-800 transition">Log In Here</a>
        </p>
    </div>

    <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
        <div class="bg-white py-10 px-4 shadow-sm border border-stone-200/60 sm:rounded-3xl sm:px-10">
            <form method="POST" action="<?php echo e(route('register')); ?>" class="space-y-6">
                <?php echo csrf_field(); ?>

                <div>
                    <label for="name" class="block text-xs font-bold tracking-widest uppercase text-stone-600">Full Legal Name</label>
                    <div class="mt-2">
                        <input id="name" name="name" type="text" value="<?php echo e(old('name')); ?>" required autofocus autocomplete="name" class="block w-full rounded-xl border-0 py-3 text-stone-900 shadow-sm ring-1 ring-inset ring-stone-300 placeholder:text-stone-400 focus:ring-2 focus:ring-inset focus:ring-amber-800 sm:text-sm sm:leading-6 px-4">
                        <?php if($errors->has('name')): ?>
                            <p class="mt-2 text-red-600 text-xs font-medium"><?php echo e($errors->first('name')); ?></p>
                        <?php endif; ?>
                    </div>
                </div>

                <div>
                    <label for="email" class="block text-xs font-bold tracking-widest uppercase text-stone-600">Email Address</label>
                    <div class="mt-2">
                        <input id="email" name="email" type="email" value="<?php echo e(old('email')); ?>" required autocomplete="username" class="block w-full rounded-xl border-0 py-3 text-stone-900 shadow-sm ring-1 ring-inset ring-stone-300 placeholder:text-stone-400 focus:ring-2 focus:ring-inset focus:ring-amber-800 sm:text-sm sm:leading-6 px-4">
                        <?php if($errors->has('email')): ?>
                            <p class="mt-2 text-red-600 text-xs font-medium"><?php echo e($errors->first('email')); ?></p>
                        <?php endif; ?>
                    </div>
                </div>

                <div>
                    <label for="password" class="block text-xs font-bold tracking-widest uppercase text-stone-600">Secure Password</label>
                    <div class="mt-2">
                        <input id="password" name="password" type="password" required autocomplete="new-password" class="block w-full rounded-xl border-0 py-3 text-stone-900 shadow-sm ring-1 ring-inset ring-stone-300 placeholder:text-stone-400 focus:ring-2 focus:ring-inset focus:ring-amber-800 sm:text-sm sm:leading-6 px-4">
                        <?php if($errors->has('password')): ?>
                            <p class="mt-2 text-red-600 text-xs font-medium"><?php echo e($errors->first('password')); ?></p>
                        <?php endif; ?>
                    </div>
                </div>

                <div>
                    <label for="password_confirmation" class="block text-xs font-bold tracking-widest uppercase text-stone-600">Confirm Password</label>
                    <div class="mt-2">
                        <input id="password_confirmation" name="password_confirmation" type="password" required autocomplete="new-password" class="block w-full rounded-xl border-0 py-3 text-stone-900 shadow-sm ring-1 ring-inset ring-stone-300 placeholder:text-stone-400 focus:ring-2 focus:ring-inset focus:ring-amber-800 sm:text-sm sm:leading-6 px-4">
                    </div>
                </div>

                <input type="hidden" name="role" value="customer">

                <div class="pt-2">
                    <button type="submit" class="w-full flex justify-center bg-amber-800 text-white px-4 py-3 rounded-xl text-xs font-bold tracking-widest uppercase hover:bg-amber-900 transition shadow-md">
                        Complete Registration
                    </button>
                </div>
                
                <div class="pt-4 text-center border-t border-stone-100 flex flex-col gap-2">
                    <a href="<?php echo e(route('home')); ?>" class="text-xs bg-stone-50 hover:bg-stone-100 text-stone-600 transition font-bold py-2.5 rounded-lg border border-stone-200">
                        Continue as Guest
                    </a>
                    <a href="<?php echo e(route('home')); ?>" class="text-[11px] text-stone-400 hover:text-stone-500 transition font-light">← Cancel and Return to Homepage</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html><?php /**PATH C:\Users\Licensed User\Documents\GitHub\BookingSystem-Assessment\hotel-booking\resources\views/auth/register.blade.php ENDPATH**/ ?>