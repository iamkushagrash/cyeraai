@php $isRoot = $isRoot ?? false; @endphp

<div class="user-node {{ $isRoot ? 'root' : '' }}">
    <strong>{{ $user->name }}</strong>
    <small>{{ $user->userid }}</small>
    <small>Pkg: {{ round($user->package,0) }}</small>
    <small>Biz: {{ round($user->teamtotal,0) }}</small>

    <span class="{{ $user->status=='Active'?'status-active':'status-inactive' }}">
        {{ $user->status }}
    </span>

    <div class="toggle-arrow"
         onclick="loadChildren(event,this,{{ $user->id }})">▼</div>
</div>

<ul class="children-container" data-loaded="0"></ul>
