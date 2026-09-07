@php $isRoot = $isRoot ?? false; @endphp

<div class="user-node {{ $isRoot ? 'root' : '' }}">
	<strong>{{ $user->name }}</strong><br>
	<small>ID: {{ $user->userid }}</small><br>
	<small>DOJ: {{ $user->doj }}</small><br>
	<small>Package: {{ round($user->package,2) }}</small><br>
	<small>Business: {{ round($user->teamtotal,2) }}</small><br>

	<span class="{{ $user->status=='Active'?'status-active':'status-inactive' }}">
		{{ $user->status }}
	</span>

	<div class="toggle-arrow"
	     onclick="loadChildren(event,this,{{ $user->id }})">▼</div>
</div>

<ul class="children-container" data-loaded="0" style="display:none"></ul>
