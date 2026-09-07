<!DOCTYPE html>
<html lang="en" data-bs-theme="dark">
<head>
	<meta charset="utf-8">
	<title>Registration</title>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="description" content="">
	<meta name="author" content="">
	<meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">
<!-- Open Graph Meta Tags -->
<meta property="og:title" content="Cyera AI – Decentralized Web3 Ecosystem" />
<meta property="og:description" content="Cyera AI is building the future of Web3 with blockchain, AI, gaming, cloud, communication, and more. Explore our powerful dApps ecosystem today." />
<!-- <meta property="og:image" content="{{asset('front/img/AI-dark.png')}}" /> -->
<meta property="og:url" content="https://cyera.ai/" />
<meta property="og:type" content="website" />

<!-- Twitter Meta Tags -->
<meta name="twitter:card" content="summary_large_image" />
<meta name="twitter:title" content="Cyera AI – Decentralized Web3 Ecosystem" />
<meta name="twitter:description" content="Join Cyera AI, a next-generation blockchain ecosystem combining AI, gaming, cloud, DeFi, and communication in Web3." />
<!-- <meta name="twitter:image" content="{{asset('front/img/AI-dark.png')}}" /> -->
	<link rel="icon" type="image/x-icon" href="{{asset('ctassets/img/favicon.ico')}}"/>
	<!-- ================== BEGIN core-css ================== -->
	<link href="{{asset('ctassets/css/vendor.min.css')}}" rel="stylesheet">
	<link href="{{asset('ctassets/css/app.min.css')}}" rel="stylesheet">
	<!-- ================== END core-css ================== -->
	<style type="text/css">
    #myVideo {
      position: fixed;
      right: 0;
      bottom: 0;
      min-width: 100%;
      min-height: 100%;
	   /* Combine all effects */
  filter: brightness(25%);
    }
    </style>
</head>
<body class='pace-top'>
	<video autoplay muted loop id="myVideo">
      <source src="{{asset('ctassets/video1.mp4')}}" type="video/mp4">
    </video>
	<!-- BEGIN #app -->
	<div id="app" class="app app-full-height app-without-header">
		<!-- BEGIN register -->
		<div class="register">
			<!-- BEGIN register-content -->
			<div class="register-content">
				@if (session('success'))
                        <div class="alert alert-success green">
                            {{ session('success') }}
                        </div>
                    @endif
                    @if (session('warning'))
                        <div class="alert alert-warning red">
                            {{ session('warning') }}
                        </div>
                    @endif
				@if(!session('success'))
				<form action="{{ route('register') }}" method="POST" name="form1" id="form1">
					@csrf
					<h1 class="text-center">Get started with Us</h1>
					<p class="text-inverse text-opacity-50 text-center">Register a new membership</p>
					
					<div class="mb-3">
						<label class="form-label">Sponsor ID <span class="text-danger">*</span></label>
						<input name="referrer" type="text" id="referrer" class="form-control form-control-lg bg-inverse bg-opacity-5 @error('referrer') is-invalid @enderror" @if(!empty($userid)) value="{{$userid}}" @else value="{{old('referrer')}}" @endif required autofocus placeholder="Sponsor Id">
						@error('referrer')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
					</div>
					<div class="mb-3">
						<label class="form-label">Sponsor Name <span class="text-danger">*</span></label>
						<input name="referrername" type="text" id="spname" disabled="disabled" class="form-control form-control-lg bg-inverse bg-opacity-5" placeholder="Sponsor Name" value="">
					</div>
					
					<div class="mb-3">
						<label class="form-label">Name <span class="text-danger">*</span></label>
						<input name="name" type="text" id="name" class="form-control form-control-lg bg-inverse bg-opacity-5 @error('name') is-invalid @enderror" placeholder="Full Name" value="{{ old('name') }}" required autocomplete="name" autofocus>
						@error('name')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
					</div>
					<div class="mb-3">
						<label class="form-label">Email Address <span class="text-danger">*</span></label>
						<input name="email" type="text" id="email" class="form-control form-control-lg bg-inverse bg-opacity-5 @error('email') is-invalid @enderror" value="{{ old('email') }}" required autocomplete="email" placeholder="Email Id">
						@error('email')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
					</div> 
					
					<div class="mb-3 row">
						<label class="form-label">Mobile No <span class="text-danger">*</span></label>
						<div class="col-md-4" style="padding-right:0;">
							<select name="countrycode" class="form-select form-select-lg bg-inverse bg-opacity-5">
								<option data-countryCode="SG" value="65">Singapore (+65)</option>
								    <option data-countryCode="IN" value="91">India (+91)</option>
	                                <option data-countryCode="GB" value="44">UK (+44)</option>
								    <option data-countryCode="US" value="1">USA (+1)</option>
								    <option data-countryCode="AE" value="971">United Arab Emirates (+971)</option>
								    <optgroup label="Other countries">
								        <option value="93">Afghanistan (+93)</option>
										<option value="355">Albania (+355)</option>
										<option value="213">Algeria (+213)</option>
										<option value="1684">American Samoa (+1684)</option>
										<option value="376">Andorra (+376)</option>
										<option value="244">Angola (+244)</option>
										<option value="1264">Anguilla (+1264)</option>
										<option value="1268">Antigua & Barbuda (+1268)</option>
										<option value="54">Argentina (+54)</option>
										<option value="374">Armenia (+374)</option>
										<option value="297">Aruba (+297)</option>
										<option value="61">Australia (+61)</option>
										<option value="43">Austria (+43)</option>
										<option value="994">Azerbaijan (+994)</option>

										<option value="1242">Bahamas (+1242)</option>
										<option value="973">Bahrain (+973)</option>
										<option value="880">Bangladesh (+880)</option>
										<option value="1246">Barbados (+1246)</option>
										<option value="375">Belarus (+375)</option>
										<option value="32">Belgium (+32)</option>
										<option value="501">Belize (+501)</option>
										<option value="229">Benin (+229)</option>
										<option value="1441">Bermuda (+1441)</option>
										<option value="975">Bhutan (+975)</option>
										<option value="591">Bolivia (+591)</option>
										<option value="387">Bosnia & Herzegovina (+387)</option>
										<option value="267">Botswana (+267)</option>
										<option value="55">Brazil (+55)</option>
										<option value="673">Brunei (+673)</option>
										<option value="359">Bulgaria (+359)</option>
										<option value="226">Burkina Faso (+226)</option>
										<option value="257">Burundi (+257)</option>

										<option value="855">Cambodia (+855)</option>
										<option value="237">Cameroon (+237)</option>
										<option value="1">Canada (+1)</option>
										<option value="238">Cape Verde (+238)</option>
										<option value="1345">Cayman Islands (+1345)</option>
										<option value="236">Central African Republic (+236)</option>
										<option value="235">Chad (+235)</option>
										<option value="56">Chile (+56)</option>
										<option value="86">China (+86)</option>
										<option value="57">Colombia (+57)</option>
										<option value="269">Comoros (+269)</option>
										<option value="243">Congo (DRC) (+243)</option>
										<option value="242">Congo (Republic) (+242)</option>
										<option value="682">Cook Islands (+682)</option>
										<option value="506">Costa Rica (+506)</option>
										<option value="385">Croatia (+385)</option>
										<option value="53">Cuba (+53)</option>
										<option value="357">Cyprus (+357)</option>
										<option value="420">Czech Republic (+420)</option>

										<option value="45">Denmark (+45)</option>
										<option value="253">Djibouti (+253)</option>
										<option value="1767">Dominica (+1767)</option>
										<option value="1809">Dominican Republic (+1809)</option>

										<option value="593">Ecuador (+593)</option>
										<option value="20">Egypt (+20)</option>
										<option value="503">El Salvador (+503)</option>
										<option value="240">Equatorial Guinea (+240)</option>
										<option value="291">Eritrea (+291)</option>
										<option value="372">Estonia (+372)</option>
										<option value="251">Ethiopia (+251)</option>

										<option value="500">Falkland Islands (+500)</option>
										<option value="298">Faroe Islands (+298)</option>
										<option value="679">Fiji (+679)</option>
										<option value="358">Finland (+358)</option>
										<option value="33">France (+33)</option>

										<option value="594">French Guiana (+594)</option>
										<option value="689">French Polynesia (+689)</option>

										<option value="241">Gabon (+241)</option>
										<option value="220">Gambia (+220)</option>
										<option value="995">Georgia (+995)</option>
										<option value="49">Germany (+49)</option>
										<option value="233">Ghana (+233)</option>
										<option value="350">Gibraltar (+350)</option>
										<option value="30">Greece (+30)</option>
										<option value="299">Greenland (+299)</option>
										<option value="1473">Grenada (+1473)</option>
										<option value="590">Guadeloupe (+590)</option>
										<option value="1671">Guam (+1671)</option>
										<option value="502">Guatemala (+502)</option>
										<option value="224">Guinea (+224)</option>
										<option value="245">Guinea-Bissau (+245)</option>
										<option value="592">Guyana (+592)</option>

										<option value="509">Haiti (+509)</option>
										<option value="504">Honduras (+504)</option>
										<option value="852">Hong Kong (+852)</option>
										<option value="36">Hungary (+36)</option>

										<option value="354">Iceland (+354)</option>
										<option value="91">India (+91)</option>
										<option value="62">Indonesia (+62)</option>
										<option value="98">Iran (+98)</option>
										<option value="964">Iraq (+964)</option>
										<option value="353">Ireland (+353)</option>
										<option value="972">Israel (+972)</option>
										<option value="39">Italy (+39)</option>

										<option value="1876">Jamaica (+1876)</option>
										<option value="81">Japan (+81)</option>
										<option value="962">Jordan (+962)</option>

										<option value="7">Kazakhstan (+7)</option>
										<option value="254">Kenya (+254)</option>
										<option value="686">Kiribati (+686)</option>
										<option value="850">North Korea (+850)</option>
										<option value="82">South Korea (+82)</option>
										<option value="965">Kuwait (+965)</option>
										<option value="996">Kyrgyzstan (+996)</option>

										<option value="856">Laos (+856)</option>
										<option value="371">Latvia (+371)</option>
										<option value="961">Lebanon (+961)</option>
										<option value="266">Lesotho (+266)</option>
										<option value="231">Liberia (+231)</option>
										<option value="218">Libya (+218)</option>
										<option value="423">Liechtenstein (+423)</option>
										<option value="370">Lithuania (+370)</option>
										<option value="352">Luxembourg (+352)</option>

										<option value="853">Macau (+853)</option>
										<option value="389">North Macedonia (+389)</option>
										<option value="261">Madagascar (+261)</option>
										<option value="265">Malawi (+265)</option>
										<option value="60">Malaysia (+60)</option>
										<option value="960">Maldives (+960)</option>
										<option value="223">Mali (+223)</option>
										<option value="356">Malta (+356)</option>
										<option value="692">Marshall Islands (+692)</option>
										<option value="596">Martinique (+596)</option>
										<option value="222">Mauritania (+222)</option>
										<option value="230">Mauritius (+230)</option>
										<option value="262">Mayotte (+262)</option>
										<option value="52">Mexico (+52)</option>
										<option value="691">Micronesia (+691)</option>
										<option value="373">Moldova (+373)</option>
										<option value="377">Monaco (+377)</option>
										<option value="976">Mongolia (+976)</option>
										<option value="382">Montenegro (+382)</option>
										<option value="1664">Montserrat (+1664)</option>
										<option value="212">Morocco (+212)</option>
										<option value="258">Mozambique (+258)</option>
										<option value="95">Myanmar (+95)</option>

										<option value="264">Namibia (+264)</option>
										<option value="674">Nauru (+674)</option>
										<option value="977">Nepal (+977)</option>
										<option value="31">Netherlands (+31)</option>
										<option value="599">Netherlands Antilles (+599)</option>
										<option value="687">New Caledonia (+687)</option>
										<option value="64">New Zealand (+64)</option>
										<option value="505">Nicaragua (+505)</option>
										<option value="227">Niger (+227)</option>
										<option value="234">Nigeria (+234)</option>
										<option value="683">Niue (+683)</option>
										<option value="672">Norfolk Island (+672)</option>
										<option value="1670">Northern Mariana Islands (+1670)</option>
										<option value="47">Norway (+47)</option>

										<option value="968">Oman (+968)</option>

										<option value="92">Pakistan (+92)</option>
										<option value="680">Palau (+680)</option>
										<option value="507">Panama (+507)</option>
										<option value="675">Papua New Guinea (+675)</option>
										<option value="595">Paraguay (+595)</option>
										<option value="51">Peru (+51)</option>
										<option value="63">Philippines (+63)</option>
										<option value="48">Poland (+48)</option>
										<option value="351">Portugal (+351)</option>
										<option value="1787">Puerto Rico (+1787)</option>

										<option value="974">Qatar (+974)</option>

										<option value="242">Reunion (+262)</option>
										<option value="40">Romania (+40)</option>
										<option value="7">Russia (+7)</option>
										<option value="250">Rwanda (+250)</option>

										<option value="590">Saint Barthelemy (+590)</option>
										<option value="290">Saint Helena (+290)</option>
										<option value="1869">Saint Kitts & Nevis (+1869)</option>
										<option value="1758">Saint Lucia (+1758)</option>
										<option value="1599">Saint Martin (+1599)</option>
										<option value="508">Saint Pierre & Miquelon (+508)</option>
										<option value="1784">Saint Vincent & Grenadines (+1784)</option>

										<option value="685">Samoa (+685)</option>
										<option value="378">San Marino (+378)</option>
										<option value="239">Sao Tome & Principe (+239)</option>
										<option value="966">Saudi Arabia (+966)</option>
										<option value="221">Senegal (+221)</option>
										<option value="381">Serbia (+381)</option>
										<option value="248">Seychelles (+248)</option>
										<option value="232">Sierra Leone (+232)</option>
										<option value="65">Singapore (+65)</option>
										<option value="421">Slovakia (+421)</option>
										<option value="386">Slovenia (+386)</option>
										<option value="677">Solomon Islands (+677)</option>
										<option value="252">Somalia (+252)</option>
										<option value="27">South Africa (+27)</option>
										<option value="34">Spain (+34)</option>
										<option value="94">Sri Lanka (+94)</option>
										<option value="249">Sudan (+249)</option>
										<option value="597">Suriname (+597)</option>
										<option value="268">Eswatini (+268)</option>
										<option value="46">Sweden (+46)</option>
										<option value="41">Switzerland (+41)</option>
										<option value="963">Syria (+963)</option>

										<option value="886">Taiwan (+886)</option>
										<option value="992">Tajikistan (+992)</option>
										<option value="255">Tanzania (+255)</option>
										<option value="66">Thailand (+66)</option>
										<option value="228">Togo (+228)</option>
										<option value="690">Tokelau (+690)</option>
										<option value="676">Tonga (+676)</option>
										<option value="1868">Trinidad & Tobago (+1868)</option>
										<option value="216">Tunisia (+216)</option>
										<option value="90">Turkey (+90)</option>
										<option value="993">Turkmenistan (+993)</option>
										<option value="1649">Turks & Caicos Islands (+1649)</option>
										<option value="688">Tuvalu (+688)</option>

										<option value="256">Uganda (+256)</option>
										<option value="380">Ukraine (+380)</option>
										<option value="971">United Arab Emirates (+971)</option>
										<option value="44">United Kingdom (+44)</option>
										<option value="1">United States (+1)</option>
										<option value="598">Uruguay (+598)</option>
										<option value="998">Uzbekistan (+998)</option>

										<option value="678">Vanuatu (+678)</option>
										<option value="379">Vatican City (+379)</option>
										<option value="58">Venezuela (+58)</option>
										<option value="84">Vietnam (+84)</option>
										<option value="1284">Virgin Islands (British) (+1284)</option>
										<option value="1340">Virgin Islands (US) (+1340)</option>

										<option value="681">Wallis & Futuna (+681)</option>
										<option value="967">Yemen (+967)</option>
										<option value="260">Zambia (+260)</option>
										<option value="263">Zimbabwe (+263)</option>
								    </optgroup>
							</select>
						</div>
						<div class="col-md-8">
							<input name="contact" type="text" maxlength="11" id="contact" class="form-control form-control-lg bg-inverse bg-opacity-5 @error('contact') is-invalid @enderror" value="{{ old('contact') }}" autocomplete="contact" placeholder="Mobile No">
						</div>
						
						@error('contact')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
					</div>
					<div class="mb-3">
						<label class="form-label">Password <span class="text-danger">*</span></label>
						<input name="password" type="password" id="password" class="form-control form-control-lg bg-inverse bg-opacity-5 @error('password') is-invalid @enderror" required autocomplete="new-password" placeholder="Enter Password">
						@error('password')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
					</div>
					<div class="mb-3">
						<label class="form-label">Confirm Password <span class="text-danger">*</span></label>
						<input type="password" id="password-confirm" name="password_confirmation" class="form-control form-control-lg bg-inverse bg-opacity-5" required autocomplete="new-password" placeholder="repeat password">
					</div>
					<!-- <div class="mb-3">
						<div class="form-check">
							<input class="form-check-input" type="checkbox" value="" id="customCheck1">
							<label class="form-check-label" for="customCheck1">I have read and agree to the <a href="#">Terms of Use</a> and <a href="#">Privacy Policy</a>.</label>
						</div>
					</div> -->
					<div class="mb-3">
						<button type="submit" class="btn btn-outline-theme btn-lg d-block w-100" onclick="this.disabled=true;this.value='Processing, please wait...';this.form.submit();">Sign Up</button>
					</div>
					<div class="text-inverse text-opacity-50 text-center">
						Already have an account? <a href="/login">Sign In</a>
					</div>
				</form>
				@endif
                @if (session('success'))
                <form action="#" name="form2" id="form2">
					
					<h1 class="text-center">Registration Successful</h1>
					<p class="text-inverse text-opacity-50 text-center"></p>
					
					<div class="mb-3">
						<label class="form-label">User ID : {{session('details.uniqueid') }}<span class="text-danger"></span></label>
						
					</div>
					<div class="mb-3">
						<label class="form-label">Email : {{session('details.username') }}<span class="text-danger"></span></label>
						
					</div>
					<div class="mb-3">
						<label class="form-label">Password : {{session('details.password') }}<span class="text-danger"></span></label>
						
					</div>
					<div class="mb-3">
						<a href="/register"><button type="button" class="btn btn-outline-theme btn-lg d-block w-100">Back</button></a>
					</div>
					<div class="text-inverse text-opacity-50 text-center">
						Already have an account? <a href="/login">Sign In</a>
					</div>
				</form>
                @endif
			</div>
			<!-- END register-content -->
		</div>
		<!-- END register -->

		<!-- BEGIN btn-scroll-top -->
		<a href="#" data-toggle="scroll-to-top" class="btn-scroll-top fade"><i class="fa fa-arrow-up"></i></a>
		<!-- END btn-scroll-top -->
	</div>
	<!-- END #app -->
	
	<!-- ================== BEGIN core-js ================== -->
	<script src="{{asset('ctassets/js/vendor.min.js')}}"></script>
	<script src="{{asset('ctassets/js/app.min.js')}}"></script>
	<!-- ================== END core-js ================== -->
	
	<script src="{{asset('theme/jquery-1.11.2.min.js')}}"></script>


    <script type="text/javascript">
        $(document).ready(function(){
            if($("#referrer").val()!="")
                $("#referrer").blur();
        });
        $("#referrer").on('blur',function(){
            $("#spdiv").hide();
            $.ajax({
                     type:'GET',
                     url:'/getSponsor/'+$("#referrer").val(),
                     dataType: "json",
                     success:function(data){
                        if(data.status==0){
                           $("#spdiv").show();
                           $("#spname").val(data.name);
                        }else{
                           $("#spdiv").hide();
                        }
                     }
                 });
        });
    </script>
	
</body>
</html>
