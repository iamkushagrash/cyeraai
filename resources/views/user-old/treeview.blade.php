<!DOCTYPE html>
<html lang="en" data-bs-theme="dark">
<head>
	<meta charset="utf-8">
	<title>Team Tree View</title>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="icon" type="image/x-icon" href="{{asset('ctassets/img/favicon.ico')}}"/>
	<link href="{{asset('ctassets/css/vendor.min.css')}}" rel="stylesheet">
	<link href="{{asset('ctassets/css/app.min.css')}}" rel="stylesheet">

<style>
#myVideo {
	position: fixed;
	right: 0;
	bottom: 0;
	min-width: 100%;
	min-height: 100%;
}
.tree-wrapper { width:100%; padding:10px; overflow-x:auto; text-align:center; }
.tree { display:inline-block; zoom:0.8; }
.tree ul { padding-top:15px; display:flex; justify-content:space-evenly; }
.tree li { list-style:none; position:relative; padding:15px 5px 0; flex-shrink:0; }

.tree li::before,.tree li::after{
	content:''; position:absolute; top:0; border-top:1px solid gold; width:50%;
}
.tree li::before{ right:50%; }
.tree li::after{ left:50%; border-left:1px solid gold; }

.tree ul ul::before{
	content:''; position:absolute; top:0; left:50%;
	border-left:1px solid gold; height:12px;
}

.user-node{
	border:1px solid gold;
	padding:6px;
	border-radius:8px;
	background:rgba(0,0,0,0.9);
	color:#FFD700;
	cursor:pointer;
	min-width:85px;
	font-size:12px;
}
.user-node.root{
	font-size:14px;
	font-weight:bold;
	min-width:110px;
	box-shadow:0 0 20px gold;
}
.toggle-arrow{
	font-size:12px;
	margin-top:3px;
	color:#FFD700;
	cursor:pointer;
	animation:bounce 1.2s infinite;
}
@keyframes bounce{0%,100%{transform:translateY(0)}50%{transform:translateY(3px)}}
.status-active{color:#3cd2a5;font-size:10px;font-weight:bold}
.status-inactive{color:#ff4d4d;font-size:10px;font-weight:bold}
</style>
</head>

<body>
<video autoplay muted loop id="myVideo">
	<source src="{{asset('ctassets/myvideo2.mp4')}}" type="video/mp4">
</video>

<div id="app" class="app" style="position:relative;">
	@include('user.topbaruser')
	@include('user.sidebaruser')

	<div id="content" class="app-content">
		<ul class="breadcrumb">
			<li class="breadcrumb-item"><a href="/User/Dashboard">DASHBOARD</a></li>
			<li class="breadcrumb-item active">Team Tree View</li>
		</ul>

		<h1 class="page-header">Team Tree View</h1>
		<hr class="mb-4">

		<div class="card">
			<div class="card-body">

				<div class="tree-wrapper">
					<div class="tree">
						<ul>
							<li>
								@include('user.tree_node', ['user'=>$rootUser,'isRoot'=>true])
							</li>
						</ul>
					</div>
				</div>

			</div>
		</div>
	</div>
</div>

<script src="{{asset('ctassets/js/vendor.min.js')}}"></script>
<script src="{{asset('ctassets/js/app.min.js')}}"></script>

<script>
/* 🔥 AJAX CHILD LOADER */
function loadChildren(e, arrow, parentId){
	e.stopPropagation();

	let li = arrow.closest("li");
	let container = li.querySelector(".children-container");

	if(container.dataset.loaded === "1"){
		container.style.display =
			container.style.display === "none" ? "flex" : "none";
		return;
	}

	arrow.innerHTML = "⏳";

	fetch(`/User/Treeview/children/${parentId}`)
	.then(res => res.text())
	.then(html => {
		container.innerHTML = html;
		container.style.display = "flex";
		container.dataset.loaded = "1";
		arrow.innerHTML = "▼";
	})
	.catch(()=> arrow.innerHTML="⚠");
}
</script>

</body>
</html>
