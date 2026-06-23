<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\AuditLog;
use Illuminate\Http\Request;

class AuditLogController extends Controller
{
    public function index(Request $request)
    {
        $filter = $request->query('filter', 'all'); // all | staff | customer

        $query = AuditLog::with('user')->orderByDesc('created_at');

        if ($filter === 'staff') {
            $query->where('actor_type', 'staff');
        } elseif ($filter === 'customer') {
            $query->where('actor_type', 'customer');
        }

        $logs = $query->paginate(20)->withQueryString();
        $totalEntries = AuditLog::count();

        return view('admin.audit-logs', [
            'logs' => $logs,
            'filter' => $filter,
            'totalEntries' => $totalEntries,
        ]);
    }
}
