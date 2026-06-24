<?php $__env->startSection('title', 'Transaction Reports'); ?>

<?php $__env->startSection('content'); ?>
<div class="flex items-center justify-between mb-1">
    <p class="text-xs text-stone-500">Transaction Reports</p>
    <p class="text-xs text-stone-500"><?php echo e(now()->format('D, M j, Y')); ?></p>
</div>

<h1 class="text-3xl font-serif text-stone-800 mb-1">Transaction Reports</h1>
<p class="text-stone-500 mb-6">Daily booking transactions and revenue — last 10 days</p>


<div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
    <div class="bg-white rounded-xl border border-stone-200 p-5">
        <div class="flex items-center gap-3 mb-2">
            <span class="w-9 h-9 rounded-lg bg-stone-100 flex items-center justify-center">📈</span>
            <span class="text-xs uppercase text-stone-500">Total Revenue (10d)</span>
        </div>
        <div class="text-2xl font-semibold text-stone-800">$<?php echo e(number_format($totalRevenue, 0)); ?></div>
    </div>

    <div class="bg-white rounded-xl border border-stone-200 p-5">
        <div class="flex items-center gap-3 mb-2">
            <span class="w-9 h-9 rounded-lg bg-stone-100 flex items-center justify-center">✅</span>
            <span class="text-xs uppercase text-stone-500">Total Transactions</span>
        </div>
        <div class="text-2xl font-semibold text-stone-800"><?php echo e(number_format($totalTransactions)); ?></div>
    </div>

    <div class="bg-white rounded-xl border border-stone-200 p-5">
        <div class="flex items-center gap-3 mb-2">
            <span class="w-9 h-9 rounded-lg bg-stone-100 flex items-center justify-center">$</span>
            <span class="text-xs uppercase text-stone-500">Avg Daily Revenue</span>
        </div>
        <div class="text-2xl font-semibold text-stone-800">$<?php echo e(number_format($avgDailyRevenue, 0)); ?></div>
    </div>
</div>


<div class="bg-white rounded-xl border border-stone-200 p-5 mb-6">
    <h2 class="text-lg font-semibold text-stone-800 mb-4">Daily Transactions &amp; Revenue</h2>

    <?php
        $maxTx = max(1, $dailySeries->max('transactions'));
    ?>

    <div class="flex items-end gap-3 h-56 border-b border-stone-200 pb-1">
        <?php $__currentLoopData = $dailySeries; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $day): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php $heightPct = round(($day['transactions'] / $maxTx) * 100); ?>
            <div class="flex-1 flex flex-col items-center justify-end h-full group relative">
                <div class="text-[10px] text-stone-400 mb-1 opacity-0 group-hover:opacity-100 transition absolute -top-6 bg-stone-800 text-white px-2 py-1 rounded whitespace-nowrap">
                    <?php echo e($day['date']); ?> — <?php echo e($day['transactions']); ?> tx, $<?php echo e(number_format($day['revenue'], 0)); ?>

                </div>
                <div class="w-full max-w-[36px] bg-amber-300 hover:bg-amber-400 rounded-t-sm transition-all" style="height: <?php echo e(max($heightPct, 2)); ?>%"></div>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
    <div class="flex gap-3 mt-2">
        <?php $__currentLoopData = $dailySeries; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $day): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="flex-1 text-center text-xs text-stone-500"><?php echo e($day['date']); ?></div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
    <div class="flex items-center gap-2 mt-4 text-xs text-stone-500">
        <span class="w-3 h-3 bg-amber-300 rounded-sm"></span> Transactions
    </div>
</div>


<div class="bg-white rounded-xl border border-stone-200 p-5">
    <h2 class="text-lg font-semibold text-stone-800 mb-4">Transaction Details</h2>

    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="text-left text-xs uppercase text-stone-500 border-b border-stone-200">
                    <th class="py-2 pr-4">TX ID</th>
                    <th class="py-2 pr-4">Date</th>
                    <th class="py-2 pr-4">Booking</th>
                    <th class="py-2 pr-4">Guest</th>
                    <th class="py-2 pr-4">Room</th>
                    <th class="py-2 pr-4 text-right">Amount</th>
                    <th class="py-2 pr-4">Status</th>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $transactionDetails; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tx): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr class="border-b border-stone-100">
                        <td class="py-3 pr-4 text-stone-700"><?php echo e($tx['tx_id']); ?></td>
                        <td class="py-3 pr-4 text-stone-600"><?php echo e($tx['date']); ?></td>
                        <td class="py-3 pr-4 text-amber-700"><?php echo e($tx['booking_id']); ?></td>
                        <td class="py-3 pr-4 font-medium text-stone-800"><?php echo e($tx['guest']); ?></td>
                        <td class="py-3 pr-4 text-stone-600"><?php echo e($tx['room']); ?></td>
                        <td class="py-3 pr-4 text-right font-semibold text-stone-800">$<?php echo e(number_format($tx['amount'], 0)); ?></td>
                        <td class="py-3 pr-4">
                            <span class="px-2 py-0.5 rounded-md text-xs bg-emerald-50 text-emerald-700 border border-emerald-200">
                                <?php echo e($tx['status']); ?>

                            </span>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="7" class="py-6 text-center text-stone-400">No transactions in this period.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Licensed User\Documents\GitHub\BookingSystem-Assessment\hotel-booking\resources\views/admin/transaction-reports.blade.php ENDPATH**/ ?>