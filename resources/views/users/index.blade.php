@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<section class="page-heading">
    <div>
        <p class="eyebrow">DECODELABS / OVERVIEW</p>
        <h1>Welcome back, Khansa <span aria-hidden="true">✦</span></h1>
        <p class="page-subtitle">Your user directory, database records, and account activity.</p>
    </div>
    <a href="{{ route('users.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg"></i> Add user</a>
</section>

<section class="stats-grid" aria-label="User statistics">
    <article class="stat-box">
        <div class="stat-icon blue"><i class="bi bi-people-fill"></i></div>
        <div class="stat-body"><p>Total users</p><h3>{{ number_format($totalUsers) }}</h3></div>
        <span class="stat-note">In your directory</span>
    </article>
    <article class="stat-box">
        <div class="stat-icon green"><i class="bi bi-person-check-fill"></i></div>
        <div class="stat-body"><p>Active users</p><h3>{{ number_format($activeUsers) }}</h3></div>
        <span class="stat-note">Enabled accounts</span>
    </article>
    <article class="stat-box">
        <div class="stat-icon purple"><i class="bi bi-person-plus-fill"></i></div>
        <div class="stat-body"><p>Joined this week</p><h3>{{ number_format($newUsers) }}</h3></div>
        <span class="stat-note">Last 7 days</span>
    </article>
</section>

<section class="main-card" id="user-directory">
    <div class="main-card-header">
        <div class="card-title">
            <div class="icon"><i class="bi bi-people-fill"></i></div>
            <div><h4>Your users</h4><p>Manage the permanent records in your SQL database.</p></div>
        </div>
        <span class="count-pill">{{ number_format($totalUsers) }} {{ \Illuminate\Support\Str::plural('user', $totalUsers) }}</span>
    </div>

    <div class="table-controls">
        <form action="{{ route('users.index') }}" method="GET" class="table-search" role="search">
            @if($search)<a class="clear-search" href="{{ route('users.index', ['per_page' => request('per_page', 10)]) }}" aria-label="Clear search"><i class="bi bi-x-lg"></i></a>@endif
            <i class="bi bi-search"></i>
            <input type="search" name="search" value="{{ $search }}" placeholder="Search by name or email..." aria-label="Search by name or email">
            <input type="hidden" name="per_page" value="{{ request('per_page', 10) }}">
        </form>
        <form action="{{ route('users.index') }}" method="GET" class="per-page">
            @if($search)<input type="hidden" name="search" value="{{ $search }}">@endif
            <label for="per-page">Rows per page</label>
            <select id="per-page" name="per_page" onchange="this.form.submit()">
                @foreach([10, 25, 50] as $size)
                    <option value="{{ $size }}" @selected((int) request('per_page', 10) === $size)>{{ $size }}</option>
                @endforeach
            </select>
        </form>
    </div>

    <div class="table-responsive">
        <table class="users-table">
            <thead><tr><th scope="col">#</th><th scope="col">Name</th><th scope="col">Email address</th><th scope="col">Age</th><th scope="col">Status</th><th scope="col">Date added</th><th scope="col" class="actions-heading">Actions</th></tr></thead>
            <tbody>
                @forelse($users as $user)
                    @php $colors = ['avatar-c1', 'avatar-c2', 'avatar-c3', 'avatar-c4', 'avatar-c5', 'avatar-c6']; @endphp
                    <tr>
                        <td class="row-number">{{ $users->firstItem() + $loop->index }}</td>
                        <td><div class="user-cell"><div class="user-avatar {{ $colors[$loop->index % count($colors)] }}">{{ strtoupper(substr($user->name, 0, 1)) }}</div><div><strong>{{ $user->name }}</strong><span class="user-caption">Member</span></div></div></td>
                        <td><a class="email-link" href="mailto:{{ $user->email }}">{{ $user->email }}</a></td>
                        <td>{{ $user->age }}</td>
                        <td><span class="status-pill {{ $user->is_active ? 'is-active' : 'is-inactive' }}"><span></span>{{ $user->is_active ? 'Active' : 'Inactive' }}</span></td>
                        <td><span class="date-value">{{ $user->created_at?->format('M j, Y') }}</span></td>
                        <td><div class="action-group">
                            <a href="{{ route('users.edit', $user) }}" class="btn-icon btn-edit" aria-label="Edit {{ $user->name }}" title="Edit user"><i class="bi bi-pencil"></i></a>
                            <form action="{{ route('users.destroy', $user) }}" method="POST" onsubmit="return confirm('Delete {{ addslashes($user->name) }}? This cannot be undone.')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn-icon btn-delete" aria-label="Delete {{ $user->name }}" title="Delete user"><i class="bi bi-trash3"></i></button>
                            </form>
                        </div></td>
                    </tr>
                @empty
                    <tr><td colspan="7"><div class="empty-state"><div class="empty-icon"><i class="bi bi-people"></i></div><h3>{{ $search ? 'No matches found' : 'Your directory is ready' }}</h3><p>{{ $search ? 'Try another name or email, or clear your search.' : 'Add your first user to start building your directory.' }}</p>@if($search)<a href="{{ route('users.index') }}" class="btn btn-light">Clear search</a>@else<a href="{{ route('users.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg"></i> Add your first user</a>@endif</div></td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($users->total())
        <div class="pagination-wrap">
            <p class="pagination-info">Showing <strong>{{ $users->firstItem() }}</strong>–<strong>{{ $users->lastItem() }}</strong> of <strong>{{ number_format($users->total()) }}</strong> users</p>
            {{ $users->onEachSide(1)->links('pagination::bootstrap-5') }}
        </div>
    @endif
</section>
@endsection
