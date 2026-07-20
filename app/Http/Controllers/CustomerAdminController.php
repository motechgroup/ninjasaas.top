<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Role;

class CustomerAdminController extends Controller
{
    /**
     * Display a paginated listing of customers with filters and search.
     */
    public function index(Request $request)
    {
        $query = User::with(['roles', 'purchases', 'licenses', 'tickets', 'serviceRequests']);

        // Search filter
        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('envato_username', 'like', "%{$search}%");
            });
        }

        // Status filter
        if ($status = $request->input('status')) {
            if (in_array($status, ['active', 'suspended', 'blocked'])) {
                $query->where('status', $status);
            }
        }

        // Role filter
        if ($role = $request->input('role')) {
            $query->whereHas('roles', function ($q) use ($role) {
                $q->where('name', $role);
            });
        }

        $customers = $query->orderBy('created_at', 'desc')->paginate(15)->withQueryString();

        $stats = [
            'total' => User::count(),
            'active' => User::where('status', 'active')->count(),
            'suspended' => User::where('status', 'suspended')->count(),
            'blocked' => User::where('status', 'blocked')->count(),
            'envato' => User::whereNotNull('envato_username')->count(),
        ];

        $roles = Role::pluck('name')->toArray();

        return view('admin.customers.index', compact('customers', 'stats', 'roles'));
    }

    /**
     * Display detailed overview of a single customer.
     */
    public function show(User $customer)
    {
        $customer->load([
            'roles',
            'purchases.product',
            'licenses.product',
            'tickets' => fn($q) => $q->orderBy('created_at', 'desc'),
            'serviceRequests' => fn($q) => $q->orderBy('created_at', 'desc'),
        ]);

        $roles = Role::pluck('name')->toArray();

        return view('admin.customers.show', compact('customer', 'roles'));
    }

    /**
     * Update customer status (Active, Suspended, Blocked).
     */
    public function updateStatus(Request $request, User $customer)
    {
        $request->validate([
            'status' => ['required', Rule::in(['active', 'suspended', 'blocked'])],
            'status_reason' => ['nullable', 'string', 'max:500'],
            'suspended_days' => ['nullable', 'integer', 'min:1', 'max:365'],
        ]);

        if ($customer->id === Auth::id()) {
            return back()->withErrors(['error' => 'You cannot modify your own account status.']);
        }

        $suspendedUntil = null;
        if ($request->status === 'suspended' && $request->filled('suspended_days')) {
            $suspendedUntil = now()->addDays((int) $request->suspended_days);
        }

        $customer->update([
            'status' => $request->status,
            'status_reason' => $request->status === 'active' ? null : $request->status_reason,
            'suspended_until' => $request->status === 'suspended' ? $suspendedUntil : null,
        ]);

        $statusLabel = ucfirst($request->status);
        return back()->with('success', "Customer account status updated to {$statusLabel} successfully.");
    }

    /**
     * Update customer profile details & roles.
     */
    public function updateProfile(Request $request, User $customer)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($customer->id)],
            'envato_username' => ['nullable', 'string', 'max:255'],
            'role' => ['nullable', 'string', 'exists:roles,name'],
            'password' => ['nullable', 'string', 'min:8'],
        ]);

        $updateData = [
            'name' => $request->name,
            'email' => $request->email,
            'envato_username' => $request->envato_username,
        ];

        if ($request->filled('password')) {
            $updateData['password'] = Hash::make($request->password);
        }

        $customer->update($updateData);

        if ($request->filled('role')) {
            $customer->syncRoles([$request->role]);
        }

        return back()->with('success', 'Customer profile updated successfully.');
    }

    /**
     * Delete customer account securely.
     */
    public function destroy(User $customer)
    {
        if ($customer->id === Auth::id()) {
            return back()->withErrors(['error' => 'You cannot delete your own account.']);
        }

        if ($customer->hasRole('Super Admin') && User::role('Super Admin')->count() <= 1) {
            return back()->withErrors(['error' => 'Cannot delete the only Super Admin account.']);
        }

        $customerName = $customer->name;
        $customer->delete();

        return redirect()->route('admin.customers.index')->with('success', "Customer account '{$customerName}' deleted successfully.");
    }

    /**
     * Impersonate customer (Log in as customer for support troubleshooting).
     */
    public function impersonate(User $customer)
    {
        if ($customer->id === Auth::id()) {
            return back()->withErrors(['error' => 'You are already logged in as yourself.']);
        }

        session(['impersonator_id' => Auth::id()]);
        Auth::login($customer);
        session(['2fa_passed' => true]);

        return redirect()->route('dashboard')->with('success', "Now impersonating customer {$customer->name}.");
    }

    /**
     * Stop impersonating customer and return to Admin account.
     */
    public function stopImpersonating()
    {
        $impersonatorId = session('impersonator_id');

        if ($impersonatorId) {
            $admin = User::find($impersonatorId);
            if ($admin) {
                Auth::login($admin);
                session(['2fa_passed' => true]);
                session()->forget('impersonator_id');
                return redirect()->route('admin.customers.index')->with('success', 'Returned to Admin session.');
            }
        }

        return redirect()->route('home');
    }
}
