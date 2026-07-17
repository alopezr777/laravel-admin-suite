@extends('layouts.app')
@section('title', 'Team members')
@section('content')
    <div class="page-heading"><div><h1>Team members</h1><p>Manage access, roles and active user accounts.</p></div><div class="heading-actions"><a class="btn btn-primary" href="{{ route('users.create') }}">Add team member</a></div></div>
    <section class="card">
        <form class="filters" method="GET"><input class="form-control search-control" name="search" type="search" value="{{ request('search') }}" placeholder="Search name or email"><button class="btn btn-secondary" type="submit">Search</button></form>
        <div class="table-wrap"><table class="table"><thead><tr><th>Member</th><th>Role</th><th>Status</th><th>Managed projects</th><th>Assigned tasks</th><th></th></tr></thead><tbody>
            @forelse($users as $user)
                <tr><td><div style="display:flex; align-items:center; gap:10px"><span class="avatar">{{ str($user->name)->explode(' ')->map(fn($word) => str($word)->substr(0, 1))->take(2)->join('') }}</span><div><div class="cell-title">{{ $user->name }}</div><div class="cell-subtitle">{{ $user->email }}</div></div></div></td><td><span class="badge badge-{{ $user->role }}">{{ $user->role }}</span></td><td><span class="badge {{ $user->is_active ? 'badge-active' : 'badge-high' }}">{{ $user->is_active ? 'Active' : 'Disabled' }}</span></td><td>{{ $user->managed_projects_count }}</td><td>{{ $user->assigned_tasks_count }}</td><td><div class="table-actions"><a class="btn btn-secondary btn-sm" href="{{ route('users.edit', $user) }}">Edit</a>@unless(auth()->user()->is($user))<form method="POST" action="{{ route('users.destroy', $user) }}" data-confirm="Remove this team member?">@csrf @method('DELETE')<button class="btn btn-danger btn-sm" type="submit">Remove</button></form>@endunless</div></td></tr>
            @empty<tr><td colspan="6" class="empty-state">No team members found.</td></tr>@endforelse
        </tbody></table></div>{{ $users->links() }}
    </section>
@endsection

