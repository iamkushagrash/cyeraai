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

/* ==== Wrapper ==== */
.tree-wrapper { 
    width: 100%; 
    padding: 10px; 
    overflow-x: auto; 
    text-align: center; 
}
.tree { 
    display: inline-block; 
    transform-origin: top center; 
    zoom: 0.8;   /* 👈 scale ke jagah zoom (better for layout) */
}

/* ==== Rows ==== */
.tree ul {
    padding-top: 15px;
    position: relative;
    display: flex;
    justify-content: space-evenly;
    flex-wrap: nowrap;      /* 👈 ek hi row me sab */
}

/* ==== List Items ==== */
.tree li {
    list-style: none;
    text-align: center;
    position: relative;
    padding: 15px 5px 0 5px;
    flex-shrink: 0;         /* 👈 shrink na ho */
}

/* ==== Connectors ==== */
.tree li::before, .tree li::after {
    content: '';
    position: absolute;
    top: 0;
    border-top: 1px solid gold;
    width: 50%;
    height: 12px;
}
.tree li::before { right: 50%; }
.tree li::after { left: 50%; border-left: 1px solid gold; }
.tree li:only-child::before, 
.tree li:only-child::after { display: none; }
.tree li:only-child { padding-top: 0; }
.tree ul ul::before {
    content: '';
    position: absolute;
    top: 0;
    left: 50%;
    border-left: 1px solid gold;
    height: 12px;
}

/* ==== Node Box ==== */
.user-node {
    border: 1px solid gold;
    padding: 6px;               
    border-radius: 8px;
    background: rgba(0,0,0,0.9);
    color: #FFD700;
    cursor: pointer;
    transition: 0.3s;
    min-width: 85px;            
    white-space: nowrap;
    font-size: 12px;             /* 👈 thoda bada font */
    position: relative;
}
.user-node:hover {
    transform: scale(1.05);
    box-shadow: 0px 0px 15px gold;
}
.user-node.active {
    border: 1px solid #fff;
    box-shadow: 0px 0px 20px #FFD700;
}

/* ==== Root Special ==== */
.user-node.root {
    font-size: 14px;
    font-weight: bold;
    min-width: 110px;
    padding: 8px;
    background: linear-gradient(145deg,#000,#222);
    box-shadow: 0 0 20px gold;
}

/* ==== Hide children initially ==== */
.tree ul ul { display: none; }

/* ==== Arrow icon ==== */
.toggle-arrow {
    font-size: 12px;
    margin-top: 2px;
    color: #FFD700;
    animation: bounce 1.2s infinite;
}
@keyframes bounce {
    0%, 100% { transform: translateY(0); }
    50% { transform: translateY(3px); }
}

/* ==== Responsive ==== */
@media(max-width:768px){
    .tree { zoom: 0.6; }  /* 👈 aur compact mobile */
    .user-node { min-width: 70px; font-size: 10px; padding: 4px; }
    .user-node.root { min-width: 90px; font-size: 12px; }
}
@media(max-width:480px){
    .tree { zoom: 0.5; }  /* 👈 sabse compact */
    .user-node { min-width: 60px; font-size: 9px; padding: 3px; }
    .user-node.root { min-width: 80px; font-size: 10px; }
}

/* ==== Status ==== */
.status-active { color:#3cd2a5; font-weight:bold; font-size:10px; }
.status-inactive { color:#FF0000; font-weight:bold; font-size:10px; }


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
    <div class="row mb-3">
  <div class="col-md-3">
    <label class="form-label">Search by UserID / Name</label>
    <input type="text" id="searchInput" class="form-control" placeholder="Enter UserID or Name">
  </div>
  <div class="col-md-2">
    <label class="form-label">Start Level</label>
    <select id="levelFilter" class="form-control">
      <option value="">All</option>
      @for($i=1;$i<=10;$i++)
        <option value="{{$i}}">Level {{$i}}</option>
      @endfor
    </select>
  </div>
  <div class="col-md-2">
    <label class="form-label">Status</label>
    <select id="statusFilter" class="form-control">
      <option value="">All</option>
      <option value="Active">Active</option>
      <option value="Inactive">Inactive</option>
    </select>
  </div>
  <div class="col-md-2 d-flex align-items-end">
    <button class="btn btn-warning w-100" onclick="applyFilters()">Apply Filters</button>
  </div>
</div>

					<div class="tree-wrapper">
						<div class="tree">
							<ul>
								<li>
									@include('user.tree_node', ['user' => $rootUser, 'isRoot'=>true])
								</li>
							</ul>
						</div>
					</div>
				</div>
			</div>
		</div>

		<a href="#" data-toggle="scroll-to-top" class="btn-scroll-top fade">
			<i class="fa fa-arrow-up"></i>
		</a>
	</div>

	<script src="{{asset('ctassets/js/vendor.min.js')}}"></script>
	<script src="{{asset('ctassets/js/app.min.js')}}"></script>

	<script>
	function toggleNode(el) {
    document.querySelectorAll('.user-node').forEach(div => div.classList.remove('active'));
    el.classList.add('active');

    let childUl = el.parentElement.querySelector("ul");
    if (childUl) childUl.style.display = (childUl.style.display === "flex") ? "none" : "flex";

    let name = el.dataset.name;
    let levelText = el.dataset.level;      // e.g. "Level 3"
    let levelNum = levelText.replace(/level-\s*/i, "").trim(); // ✅ just "3"

    speakText(`${name}, is on level ${levelNum}`);
}

function speakText(text) {
    let msg = new SpeechSynthesisUtterance(text);

    // Voice tuning
    msg.pitch = 1.05;   // thoda soft + natural (slightly higher than default 1)
    msg.rate = 1.20;    // thodi si fast, par unnatural na lage
    msg.volume = 1;     // full volume


    let voices = speechSynthesis.getVoices();

    // Prefer Indian Female Voice
    msg.voice = voices.find(v => v.name.includes("Microsoft Neerja")) // ✅ Realistic Indian English Female
            
          
           ;

    // Stop any ongoing speech and start new
    speechSynthesis.cancel();
    speechSynthesis.speak(msg);
}




	function showUserDetails(el) {
	    let html = `
	        <p><strong>User ID:</strong> ${el.dataset.userid}</p>
	        <p><strong>Name:</strong> ${el.dataset.name}</p>
	        <p><strong>Registration Date:</strong> ${el.dataset.doj}</p>
	        <p><strong>Level:</strong> ${el.dataset.level}</p>
	        <p><strong>Package($):</strong> ${el.dataset.package}</p>
	        <p><strong>Status:</strong> 
	            <span class="${el.dataset.status === 'Active' ? 'status-active' : 'status-inactive'}">${el.dataset.status}</span>
	        </p>
	    `;
	    document.getElementById('userDetails').innerHTML = html;
	    new bootstrap.Modal(document.getElementById('userModal')).show();
	}
  function applyFilters() {
    let searchVal = document.getElementById("searchInput").value.toLowerCase();
    let levelVal  = document.getElementById("levelFilter").value;
    let statusVal = document.getElementById("statusFilter").value;

    // Reset sabko visible + collapse children
    document.querySelectorAll(".user-node").forEach(node => {
        node.style.display = "block";
        let childUl = node.parentElement.querySelector("ul");
        if (childUl) childUl.style.display = "none"; 
    });

    if (!searchVal && !levelVal && !statusVal) {
        // ✅ No filter → pura tree normal khula hua dikhana
        document.querySelector(".tree > ul").style.display = "flex";
        return;
    }

    document.querySelectorAll(".user-node").forEach(node => {
        let name   = node.dataset.name.toLowerCase();
        let userid = node.dataset.userid.toLowerCase();
        let level  = node.dataset.level.replace(/[^0-9]/g,''); // sirf number
        let status = node.dataset.status;

        let matchSearch = !searchVal || name.includes(searchVal) || userid.includes(searchVal);
        let matchLevel  = !levelVal || (level == levelVal);
        let matchStatus = !statusVal || (status == statusVal);

        if (matchSearch && matchLevel && matchStatus) {
            node.style.display = "block";

            // ✅ Parent chain open karna
            let parent = node.parentElement.closest("ul");
            while (parent) {
                parent.style.display = "flex";
                parent = parent.parentElement.closest("ul");
            }
        } else {
            node.style.display = "none";
        }
    });
}
	</script>
</body>
</html>
