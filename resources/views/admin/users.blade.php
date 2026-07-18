<x-app-layout>
    <x-slot name="header">
        Users & Roles Management
    </x-slot>

    <div class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800/85 rounded-2xl shadow-sm overflow-hidden">
        <div class="px-6 py-5 border-b border-slate-200 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-950/20">
            <h3 class="font-outfit font-bold text-slate-950 dark:text-white">Registered Users</h3>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 dark:bg-slate-950 text-xs font-semibold text-slate-500 uppercase border-b border-slate-200 dark:border-slate-800">
                        <th class="px-6 py-3.5">Name</th>
                        <th class="px-6 py-3.5">Email</th>
                        <th class="px-6 py-3.5">Envato Username</th>
                        <th class="px-6 py-3.5">Role</th>
                        <th class="px-6 py-3.5 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 dark:divide-slate-800 text-sm">
                    @foreach ($users as $user)
                        <tr class="hover:bg-slate-50 dark:hover:bg-slate-850/40 transition-colors">
                            <td class="px-6 py-4 font-bold text-slate-900 dark:text-white">
                                {{ $user->name }}
                            </td>
                            <td class="px-6 py-4 text-slate-500">
                                {{ $user->email }}
                            </td>
                            <td class="px-6 py-4 font-mono text-xs">
                                {{ $user->envato_username ?: 'Not Linked' }}
                            </td>
                            <td class="px-6 py-4">
                                @if($user->roles->isNotEmpty())
                                    @foreach($user->roles as $role)
                                        <x-badge color="indigo">{{ $role->name }}</x-badge>
                                    @endforeach
                                @else
                                    <x-badge color="gray">Customer</x-badge>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right">
                                <form action="{{ route('admin.users.update', $user->id) }}" method="POST" class="inline-flex items-center gap-2">
                                    @csrf
                                    @method('PATCH')
                                    
                                    <select name="role" required class="rounded-lg border-slate-200 dark:border-slate-800 dark:bg-slate-950 text-slate-900 dark:text-white text-xs py-1 px-2.5 focus:ring-indigo-500">
                                        <option value="Customer" {{ !$user->hasAnyRole(['Super Admin', 'Support Staff', 'Content Manager']) ? 'selected' : '' }}>Customer</option>
                                        <option value="Super Admin" {{ $user->hasRole('Super Admin') ? 'selected' : '' }}>Super Admin</option>
                                        <option value="Support Staff" {{ $user->hasRole('Support Staff') ? 'selected' : '' }}>Support Staff</option>
                                        <option value="Content Manager" {{ $user->hasRole('Content Manager') ? 'selected' : '' }}>Content Manager</option>
                                    </select>
                                    
                                    <button type="submit" class="px-3 py-1 bg-slate-900 hover:bg-slate-800 dark:bg-indigo-600 dark:hover:bg-indigo-500 text-white text-xs font-bold rounded-lg transition-colors">
                                        Save
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        @if($users->hasPages())
            <div class="px-6 py-4 border-t border-slate-200 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-950/20">
                {{ $users->links() }}
            </div>
        @endif
    </div>
</x-app-layout>
