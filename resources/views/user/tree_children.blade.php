<!-- children.blade.php -->
@foreach($children as $child)
<li>
    @include('user.tree_node',['user'=>$child])
</li>
@endforeach