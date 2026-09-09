<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title></title>
</head>
<body>
	<style>
		
		html {
		  height: 100%;
		}

		body {  
		  height: 100%;
		  background: url("https://wallpapercave.com/wp/6SLzBEY.jpg") no-repeat left top;
		  background-size: cover;  
		  overflow: hidden;
			
		  display: flex;
		  flex-flow: column wrap;
		  justify-content: center;
		  align-items: center;
		}

		.text h1{
		  color: #011718;
			margin-top: -200px;
		  font-size: 15em;
			text-align: center;
			text-shadow: -5px 5px 0px rgba(0,0,0,0.7), -10px 10px 0px rgba(0,0,0,0.4), -15px 15px 0px rgba(0,0,0,0.2);
			font-family: monospace;
		  font-weight: bold;
		}

		.text h2{
		  color: black;
		  font-size: 5em;
			text-shadow: -5px 5px 0px rgba(0,0,0,0.7);
			text-align: center;
			margin-top: -150px;
			font-family: monospace;
		  font-weight: bold;
		}
		.text h3{
		  color: white;
			margin-left: 30px;
		  font-size: 2em;
			text-shadow: -5px 5px 0px rgba(0,0,0,0.7);
			margin-top: -40px;
			font-family: monospace;
		  font-weight: bold;
		}
		.torch {
		  margin: -150px 0 0 -150px;
		  width: 200px;
		  height: 200px;
		  box-shadow: 0 0 0 9999em #000000f7;
			opacity: 1;
		  border-radius: 50%;
		  position: fixed;
			background: rgba(0,0,0,0.3);
		  
		  &:after {
		    content: '';
		    display: block;
		    border-radius: 50%;
		    width: 100%;
		    height: 100%;
		    top: 0px;
		    left: 0px;
		    box-shadow: inset 0 0 40px 2px #000,
					0 0 20px 4px rgba(13,13,10,0.2);  
		  }
		}
		.error__nav {
		  max-width: 600px;
		  margin: 40px auto 0;
		  text-align: center;
		  margin-top: 20px;
		}
		.e-nav__link {
		  height: 45px;
		  line-height: 45px;
		  width: 170px;
		  display: inline-block;
		  vertical-align: top;
		  margin: 0 15px;
		  border: 1px solid #181828;
		  color: #181828;
		  text-decoration: none;
		  font-family: 'Montserrat', sans-serif;
		  text-transform: uppercase;
		  font-size: 11px;
		  letter-spacing: .1rem;
		  position: relative;
		  overflow: hidden;
		}

		.e-nav__link:before {
		  content: '';
		  height: 200px;
		  background: #212121;
		  position: absolute;
		  top: 70px;
		  right: 70px;
		  width: 260px;
		  -webkit-transition: all .3s;
		  transition: all .3s;
		  -webkit-transform: rotate(50deg);
		          transform: rotate(50deg);
		}

		.e-nav__link:after {
		  -webkit-transition: all .3s;
		  transition: all .3s;
		  z-index: 999;
		  position: relative;
		}

		.e-nav__link:after {
		  content: "Home Page";
		}

		.e-nav__link:hover:before {
		  top: -60px;
		  right: -50px;
		}

		.e-nav__link:hover {
		  color: #fff;
		}

		.e-nav__link:nth-child(2):hover:after {
		  color: #fff;
		}
	</style>

	<div class="text">
	  <h1>404</h1>
		<h2>Uh, Ohh</h2>
	  <h3>Sorry we cant find what you are looking for 'cuz its so dark in here</h3>
	</div>
	<div class="torch"></div>
	<div class="error__nav e-nav">
      <a href="/"class="e-nav__link"></a>
    </div>
<script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
	<script>
		$(document).mousemove(function (event) {
		  $('.torch').css({
		    'top': event.pageY,
		    'left': event.pageX
		  });
		});
	</script>
</body>
</html>