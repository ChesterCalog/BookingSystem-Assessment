<?php $__env->startSection('title', 'Manage Accounts'); ?>

<?php $__env->startSection('content'); ?>
<div class="flex items-center justify-between mb-1">
    <p class="text-xs text-stone-500">Manage Accounts</p>
    <p class="text-xs text-stone-500"><?php echo e(now()->format('D, M j, Y')); ?></p>
</div>

<h1 class="text-3xl font-serif text-stone-800 mb-1">Manage Accounts</h1>
<p class="text-stone-500 mb-6">Staff accounts and customer profiles</p>

<?php if(session('success')): ?>
    <div class="mb-4 rounded-lg bg-emerald-50 border border-emerald-200 text-emerald-700 text-sm px-4 py-2">
        <?php echo e(session('success')); ?>

    </div>
<?php endif; ?>
<?php $__errorArgs = ['role'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
    <div class="mb-4 rounded-lg bg-red-50 border border-red-200 text-red-600 text-sm px-4 py-2">
        <?php echo e($message); ?>

    </div>
<?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>


<div class="bg-white rounded-xl border border-stone-200 p-5 mb-6">
    <h2 class="text-lg font-semibold text-stone-800 mb-4">Staff Accounts</h2>

    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="text-left text-xs uppercase text-stone-500 border-b border-stone-200">
                    <th class="py-2 pr-4">Name</th>
                    <th class="py-2 pr-4">Email</th>
                    <th class="py-2 pr-4">Role</th>
                    <th class="py-2 pr-4">Status</th>
                    <th class="py-2 pr-4">Last Login</th>
                    <th class="py-2 pr-4 text-right">Edit Role</th>
                </tr>
            </thead>
            <tbody>
                <?php $__currentLoopData = $staffAccounts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $staff): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr class="border-b border-stone-100">
                        <td class="py-3 pr-4 flex items-center gap-2">
                            <span class="w-8 h-8 rounded-full bg-stone-200 text-stone-600 text-xs flex items-center justify-center font-semibold">
                                <?php echo e(strtoupper(substr($staff->name, 0, 1))); ?><?php echo e(strtoupper(substr(strrchr($staff->name, ' ') ?: '', 1, 1))); ?>

                            </span>
                            <span class="font-medium text-stone-800"><?php echo e($staff->name); ?></span>
                        </td>
                        <td class="py-3 pr-4 text-stone-600"><?php echo e($staff->email); ?></td>
                        <td class="py-3 pr-4">
                            <span class="px-2 py-0.5 rounded-md text-xs border
                                <?php if($staff->role === 'admin'): ?> bg-purple-50 text-purple-700 border-purple-200
                                <?php elseif($staff->role === 'manager'): ?> bg-blue-50 text-blue-700 border-blue-200
                                <?php elseif($staff->role === 'front_desk'): ?> bg-amber-50 text-amber-700 border-amber-200
                                <?php elseif($staff->role === 'housekeeping'): ?> bg-teal-50 text-teal-700 border-teal-200
                                <?php else: ?> bg-stone-100 text-stone-600 border-stone-200
                                <?php endif; ?>">
                                <?php echo e(\Illuminate\Support\Str::headline($staff->role)); ?>

                            </span>
                            <?php if($staff->role === 'admin'): ?>
                                <div class="text-xs text-stone-400 mt-0.5">Protected</div>
                            <?php endif; ?>
                        </td>
                        <td class="py-3 pr-4">
                            <span class="inline-flex items-center gap-1 text-stone-600">
                                <span class="w-2 h-2 rounded-full <?php echo e(($staff->status ?? 'active') === 'active' ? 'bg-emerald-500' : 'bg-stone-400'); ?>"></span>
                                <?php echo e($staff->status ?? 'active'); ?>

                            </span>
                        </td>
                        <td class="py-3 pr-4 text-stone-600"><?php echo e(optional($staff->updated_at)->format('Y-m-d H:i') ?? '—'); ?></td>
                        <td class="py-3 pr-4 text-right">
                            <?php if($staff->role === 'admin'): ?>
                                <span class="text-stone-300 text-sm">Edit</span>
                            <?php else: ?>
                                <button type="button"
                                    onclick="document.getElementById('edit-role-<?php echo e($staff->id); ?>').classList.toggle('hidden')"
                                    class="text-sm border border-stone-300 rounded-md px-3 py-1 hover:bg-stone-50">
                                    Edit
                                </button>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php if($staff->role !== 'admin'): ?>
                        <tr id="edit-role-<?php echo e($staff->id); ?>" class="hidden bg-stone-50">
                            <td colspan="6" class="py-3 px-4">
                                <form action="<?php echo e(route('admin.accounts.update-role', $staff)); ?>" method="POST" class="flex items-center gap-3">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('PATCH'); ?>
                                    <select name="role" class="border border-stone-300 rounded-md text-sm px-2 py-1">
                                        <?php $__currentLoopData = ['manager', 'front_desk', 'housekeeping', 'maintenance']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $r): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($r); ?>" <?php echo e($staff->role === $r ? 'selected' : ''); ?>>
                                                <?php echo e(\Illuminate\Support\Str::headline($r)); ?>

                                            </option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                    <button type="submit" class="text-sm bg-stone-800 text-white rounded-md px-3 py-1 hover:bg-stone-700">
                                        Save
                                    </button>
                                </form>
                            </td>
                        </tr>
                    <?php endif; ?>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
    </div>
</div>


<div class="bg-white rounded-xl border border-stone-200 p-5">
    <div class="flex items-center justify-between mb-4">
        <h2 class="text-lg font-semibold text-stone-800">Customer Accounts</h2>
        <div class="flex items-center gap-4 text-xs text-stone-500">
            <span class="flex items-center gap-1"><span class="w-2 h-2 rounded-full bg-emerald-500"></span> Member — <?php echo e($memberCount); ?></span>
            <span class="flex items-center gap-1"><span class="w-2 h-2 rounded-full bg-stone-300"></span> Guest — <?php echo e($guestCount); ?></span>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="text-left text-xs uppercase text-stone-500 border-b border-stone-200">
                    <th class="py-2 pr-4">Name</th>
                    <th class="py-2 pr-4">Email</th>
                    <th class="py-2 pr-4">Phone</th>
                    <th class="py-2 pr-4">Membership</th>
                    <th class="py-2 pr-4">Bookings</th>
                    <th class="py-2 pr-4">Joined</th>
                    <th class="py-2 pr-4">Last Booking</th>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $customerAccounts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $customer): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr class="border-b border-stone-100">
                        <td class="py-3 pr-4 font-medium text-stone-800"><?php echo e($customer->name); ?></td>
                        <td class="py-3 pr-4 text-stone-600"><?php echo e($customer->email); ?></td>
                        <td class="py-3 pr-4 text-stone-600"><?php echo e($customer->phone ?? '—'); ?></td>
                        <td class="py-3 pr-4">
                            <span class="px-2 py-0.5 rounded-md text-xs border
                                <?php echo e($customer->membership_tier === 'Member' ? 'bg-emerald-50 text-emerald-700 border-emerald-200' : 'bg-stone-100 text-stone-600 border-stone-200'); ?>">
                                <?php echo e($customer->membership_tier); ?>

                            </span>
                        </td>
                        <td class="py-3 pr-4 text-stone-600"><?php echo e($customer->bookings_count); ?></td>
                        <td class="py-3 pr-4 text-stone-600"><?php echo e(optional($customer->created_at)->format('Y-m-d')); ?></td>
                        <td class="py-3 pr-4 text-stone-600">
                            <?php echo e($customer->last_booking_date ? \Carbon\Carbon::parse($customer->last_booking_date)->format('Y-m-d') : '—'); ?>

                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="7" class="py-6 text-center text-stone-400">No customer accounts yet.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Licensed User\Documents\GitHub\BookingSystem-Assessment\hotel-booking\resources\views/admin/manage-accounts.blade.php ENDPATH**/ ?>