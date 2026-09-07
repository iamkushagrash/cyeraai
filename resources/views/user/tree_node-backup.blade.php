@php
  $isRoot = $isRoot ?? false;
@endphp

<div class="user-node {{ $isRoot ? 'root' : '' }}"
     onclick="toggleNode(this)"
     ondblclick="showUserDetails(this)"
     data-userid="{{ $user->userid }}"
     data-name="{{ $user->name }}"
     data-doj="{{ $user->doj ?? 'N/A' }}"
     data-level="{{ $user->level ?? ($isRoot ? '0' : 'N/A') }}"
     data-package="{{ $user->package ?? 'N/A' }}"
     data-status="{{ $user->status }}">
     
    <strong>{{ $user->name }}</strong><br>
    <small>ID: {{ $user->userid }}</small><br>
    <small>{{ $user->doj ?? 'N/A' }}</small><br>
    <small>{{ $user->level ?? 'Self' }}</small><br>
    <span class="{{ $user->status == 'Active' ? 'status-active' : 'status-inactive' }}">
        {{ $user->status }}
    </span>

    @if (!empty($user->children) && count($user->children) > 0)
        <div class="toggle-arrow">▼</div>
    @endif
</div>

@if (!empty($user->children) && count($user->children) > 0)
    <ul>
        @foreach ($user->children as $child)
            <li>@include('user.tree_node', ['user' => $child])</li>
        @endforeach
    </ul>
@endif
