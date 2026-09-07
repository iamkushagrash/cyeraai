<!DOCTYPE html>
<html lang="en" data-bs-theme="dark">
<head>
	<meta charset="utf-8">
	<title>Dashboard</title>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="description" content="">
	<meta name="author" content="">
	<meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">

	<link rel="icon" type="image/x-icon" href="{{asset('ctassets/img/favicon.ico')}}"/>
	
	<!-- ================== BEGIN core-css ================== -->
	<link href="{{asset('ctassets/css/vendor.min.css')}}" rel="stylesheet">
	<link href="{{asset('ctassets/css/app.min.css')}}" rel="stylesheet">
	<!-- ================== END core-css ================== -->
	
	<!-- ================== BEGIN page-css ================== -->
	<link href="{{asset('ctassets/plugins/jvectormap-next/jquery-jvectormap.css')}}" rel="stylesheet">
	<!-- ================== END page-css ================== -->


    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

	<style type="text/css">
	.blink_me {
	  animation: blinker 1s linear infinite;
	}

	@keyframes blinker {
	  50% {
	    opacity: 0;
	  }
	}
	#myVideo {
      position: fixed;
      right: 0;
      bottom: 0;
      min-width: 100%;
      min-height: 100%;
    }

	</style>
	<style>
    /*body {
      background: #111;
      color: #fff;
      font-family: Arial, sans-serif;
      margin: 0;
      padding: 20px;
    }*/

    .marquee-container {
      overflow: hidden;
      white-space: nowrap;
      border-top: 1px solid #444;
      border-bottom: 1px solid #444;
      padding: 10px 0;
    }

    .marquee {
      display: inline-block;
      animation: scroll-left 40s linear infinite;
      /*animation-delay: 0s;*/
    }

    @keyframes scroll-left {
      0% {
        transform: translateX(0); /* ðŸ‘ˆ Start with some items already visible */
      }
      100% {
        transform: translateX(-100%);
      }
    }

    .crypto-list {
      display: flex;
      list-style: none;
      padding: 0;
      margin: 0;
    }

    .crypto-list li {
      display: flex;
      align-items: center;
      margin-right: 50px;
      font-size: 16px;
    }

    .crypto-list img {
      width: 20px;
      height: 20px;
      margin-right: 8px;
    }
  </style>
</head>
<body>
	<video autoplay muted loop id="myVideo">
      <source src="{{asset('ctassets/myvideo2.mp4')}}" type="video/mp4">
    </video>
	<!-- BEGIN #app -->
	<div id="app" class="app" style="position:relative;">
		<!-- BEGIN #header -->
@include('user.topbaruser')
		<!-- END #header -->
		
		<!-- BEGIN #sidebar -->
@include('user.sidebaruser')
		<!-- END #sidebar -->
			
		<!-- BEGIN mobile-sidebar-backdrop -->
		<button class="app-sidebar-mobile-backdrop" data-toggle-target=".app" data-toggle-class="app-sidebar-mobile-toggled"></button>
		<!-- END mobile-sidebar-backdrop -->
		
		<!-- BEGIN #content -->
		<div id="content" class="app-content">
			<!-- BEGIN row -->
			<div class="row">
				@if (session('success'))
					<div class="alert alert-success">
						<strong>Success!</strong> {{ session('success') }}
					</div>
				@endif
	            @if (session('warning'))
					<div class="alert alert-danger">
						<strong>Alert!</strong> {{ session('warning') }}
					</div>
				@endif

				<div class="row">
					<div class="col-lg-12">
					<!-- BEGIN card -->
					<div class="card mb-3">
						<!-- BEGIN card-body -->
						<div class="card-body">
							<div class="marquee-container">
							  <div class="marquee">
							    <ul class="crypto-list">
							      <li><img src="{{asset('ctassets/img/user/btc.png')}}" alt="BTC"> BTC: <span id="btc-price">Loading</span></li>
							      <li><img src="{{asset('ctassets/img/user/eth.png')}}" alt="ETH"> ETH: <span id="eth-price">Loading</span></li>
							      <li><img src="{{asset('ctassets/img/user/xrp.png')}}" alt="XRP"> XRP: <span id="xrp-price">Loading</span></li>
							      <li><img src="{{asset('ctassets/img/user/ada.png')}}" alt="ADA"> ADA: <span id="ada-price">Loading</span></li>
							      <li><img src="{{asset('ctassets/img/user/doge.png')}}" alt="DOGE"> DOGE: <span id="doge-price">Loading</span></li>
							      <li><img src="{{asset('ctassets/img/user/eos.png')}}" alt="EOS"> EOS: <span id="eos-price">Loading</span></li>
							      <li><img src="{{asset('ctassets/img/user/ltc.png')}}" alt="LTC"> LTC: <span id="ltc-price">Loading</span></li>
							      <li><img src="{{asset('ctassets/img/user/dash.png')}}" alt="DASH"> DASH: <span id="dash-price">Loading</span></li>
							    </ul>
							  </div>
							</div>
						</div>
						<!-- END card-body -->

						<!-- BEGIN card-arrow -->
							<div class="card-arrow">
								<div class="card-arrow-top-left"></div>
								<div class="card-arrow-top-right"></div>
								<div class="card-arrow-bottom-left"></div>
								<div class="card-arrow-bottom-right"></div>
							</div>
						<!-- END card-arrow -->
					</div>
					<!-- END card -->
				
					</div>
				</div>


				<div class="row">

				<!-- BEGIN col-3 -->
				<div class="col-xl-3 col-lg-6">
					<!-- BEGIN card -->
					<div class="card mb-3">
						<!-- BEGIN card-body -->
						<div class="card-body">
							<!-- BEGIN title -->
							<div class="d-flex fw-bold small mb-3">
								<span class="flex-grow-1">BITCOIN</span>
								<a href="#" data-toggle="card-expand" class="text-inverse text-opacity-50 text-decoration-none"><i class="bi bi-fullscreen"></i></a>
							</div>
							<!-- END title -->
							<!-- BEGIN stat-lg -->
							<div class="row align-items-center mb-2">
								<div class="col-7">
									<h3 class="mb-0"><span id="btc-price-2"> 0</span></h3>
								</div>
								<div class="col-5">
									<i class="fab fa-btc" style="font-size: 2.5rem;"></i>
								</div>
							</div>
							<!-- END stat-lg -->
							
						</div>
						<!-- END card-body -->
						
						<!-- BEGIN card-arrow -->
						<div class="card-arrow">
							<div class="card-arrow-top-left"></div>
							<div class="card-arrow-top-right"></div>
							<div class="card-arrow-bottom-left"></div>
							<div class="card-arrow-bottom-right"></div>
						</div>
						<!-- END card-arrow -->
					</div>
					<!-- END card -->
				</div>
				<!-- END col-3 -->
				
				<!-- BEGIN col-3 -->
				<div class="col-xl-3 col-lg-6">
					<!-- BEGIN card -->
					<div class="card mb-3">
						<!-- BEGIN card-body -->
						<div class="card-body">
							<!-- BEGIN title -->
							<div class="d-flex fw-bold small mb-3">
								<span class="flex-grow-1">ETHEREUM</span>
								<a href="#" data-toggle="card-expand" class="text-inverse text-opacity-50 text-decoration-none"><i class="bi bi-fullscreen"></i></a>
							</div>
							<!-- END title -->
							<!-- BEGIN stat-lg -->
							<div class="row align-items-center mb-2">
								<div class="col-7">
									<h3 class="mb-0"><span id="btc-price-3"> 0</span></h3>
								</div>
								<div class="col-5">
									<i class="fab fa-ethereum" style="font-size: 2.5rem;"></i>
								</div>
							</div>
							<!-- END stat-lg -->
							
						</div>
						<!-- END card-body -->
						
						<!-- BEGIN card-arrow -->
						<div class="card-arrow">
							<div class="card-arrow-top-left"></div>
							<div class="card-arrow-top-right"></div>
							<div class="card-arrow-bottom-left"></div>
							<div class="card-arrow-bottom-right"></div>
						</div>
						<!-- END card-arrow -->
					</div>
					<!-- END card -->
				</div>
				<!-- END col-3 -->

				<!-- BEGIN col-3 -->
				<div class="col-xl-3 col-lg-6">
					<!-- BEGIN card -->
					<div class="card mb-3">
						<!-- BEGIN card-body -->
						<div class="card-body">
							<!-- BEGIN title -->
							<div class="d-flex fw-bold small mb-3">
								<span class="flex-grow-1">OMNI</span>
								<a href="#" data-toggle="card-expand" class="text-inverse text-opacity-50 text-decoration-none"><i class="bi bi-fullscreen"></i></a>
							</div>
							<!-- END title -->
							<!-- BEGIN stat-lg -->
							<div class="row align-items-center mb-2">
								<div class="col-7">
									<h3 class="mb-0"><span id="btc-price-4"> 0</span></h3>
								</div>
								<div class="col-5">
									<i class="fab fa-opera" style="font-size: 2.5rem;"></i>
								</div>
							</div>
							<!-- END stat-lg -->
							
						</div>
						<!-- END card-body -->
						
						<!-- BEGIN card-arrow -->
						<div class="card-arrow">
							<div class="card-arrow-top-left"></div>
							<div class="card-arrow-top-right"></div>
							<div class="card-arrow-bottom-left"></div>
							<div class="card-arrow-bottom-right"></div>
						</div>
						<!-- END card-arrow -->
					</div>
					<!-- END card -->
				</div>
				<!-- END col-3 -->
				
				
				</div>



<div class="bg-white rounded-lg shadow p-6 mb-6" style="display:none;">
            <h2 class="text-xl font-semibold text-gray-700 mb-4">Latest Transaction Hash (All Contracts)</h2>
            <div id="latest-transaction-hash" class="text-lg text-gray-800 break-hash">
                Fetching latest transaction...
            </div>
        </div>
				<div class="row">
					{!! $arb !!}
				</div>
				
				


				

				

			</div>
			<!-- END row -->
		</div>
		<!-- END #content -->
		
		<!-- BEGIN btn-scroll-top -->
		<a href="#" data-toggle="scroll-to-top" class="btn-scroll-top fade"><i class="fa fa-arrow-up"></i></a>
		<!-- END btn-scroll-top -->
	</div>
	<!-- END #app -->
	
	<!-- ================== BEGIN core-js ================== -->
	<script src="{{asset('ctassets/js/vendor.min.js')}}"></script>
	<script src="{{asset('ctassets/js/app.min.js')}}"></script>
	<!-- ================== END core-js ================== -->
	
	<!-- ================== BEGIN page-js ================== -->
	<script src="{{asset('ctassets/plugins/jvectormap-next/jquery-jvectormap.min.js')}}"></script>
	<script src="{{asset('ctassets/plugins/jvectormap-content/world-mill.js')}}"></script>
	<script src="{{asset('ctassets/plugins/apexcharts/dist/apexcharts.min.js')}}"></script>
	<script src="{{asset('ctassets/js/demo/dashboard.demo.js')}}"></script>
	<!-- ================== END page-js ================== -->
	
	<script>
        const BSC_API_KEY = 'NJ8AVE24AK1N9MQ6B88JVKAGPKRBGJMZ13'; // Replace with your BSCScan API Key if needed
        const CONTRACT_ADDRESSES = [
            '0x1A0A18AC4BECDDbd6389559687d1A73d8927E416', // Original (MUSIC)
            '0x2170Ed0880ac9A755fd29B2688956BD959F933F8', // ETH
            '0x55d398326f99059fF775485246999027B3197955', // USDT
            //'0x2A0Cb7165b067a68C48fB9fF6cD4dC4CB43C43C4', // USDC (Added based on common stablecoins)
            '0x1D2F0da169ceB9fC7B3144628dB156f3F6c60dBE', // DAI
            '0xbb4CdB9CBd36B01bD1cBaEBF2De08d9173bc095c', // WBNB
            //'0x76A797A59Ba2C17726896976B7B3747BfD1d220f', // CAKE (PancakeSwap Token)
            //'0x8965349fb649A33a30cbFDa057D8eC2C48AbE2A2', // BUSD
        ];
        const BSC_API_BASE_URL = `https://api.etherscan.io/v2/api?chainid=56&module=account&action=tokentx&page=1&offset=20&sort=desc&apikey=${BSC_API_KEY}&address=`;
        const UPDATE_INTERVAL = 15000; // Update every 15 seconds

        // Tokens for color mapping and display names (expanded to include new addresses)
        const tokens = [
            { symbol: 'BNB', name: 'Binance Coin', color: '#F0B90B', address: '0xbb4CdB9CBd36B01bD1cBaEBF2De08d9173bc095c' }, // Using WBNB address for color
            { symbol: 'USDT', name: 'Tether', color: '#26A17B', address: '0x55d398326f99059fF775485246999027B3197955' },
            { symbol: 'MUSIC', name: 'Gala Music', color: '#FF5C8D', address: '0x1A0A18AC4BECDDbd6389559687d1A73d8927E416' },
            { symbol: 'DAI', name: 'Dai Stablecoin', color: '#F5AC37', address: '0x1D2F0da169ceB9fC7B3144628dB156f3F6c60dBE' },
            { symbol: 'ADA', name: 'Cardano', color: '#0033AD' }, // Address not in list, color only
            { symbol: 'DOT', name: 'Polkadot', color: '#E6007A' }, // Address not in list, color only
            { symbol: 'ETH', name: 'Ethereum', color: '#627EEA', address: '0x2170Ed0880ac9A755fd29B2688956BD959F933F8' },
            { symbol: 'BTCB', name: 'Bitcoin BEP2', color: '#F7931A' }, // Address not in list, color only
            { symbol: 'WBNB', name: 'Wrapped BNB', color: '#F0B90B', address: '0xbb4CdB9CBd36B01bD1cBaEBF2De08d9173bc095c' },
            { symbol: 'BUSD', name: 'Binance USD', color: '#F0B90B', address: '0x8965349fb649A33a30cbFDa057D8eC2C48AbE2A2' },
            { symbol: 'USDC', name: 'USD Coin', color: '#2775CE', address: '0x2A0Cb7165b067a68C48fB9fF6cD4dC4CB43C43C4' },
            { symbol: 'CAKE', name: 'PancakeSwap Token', color: '#D1871C', address: '0x76A797A59Ba2C17726896976B7B3747BfD1d220f' }
        ];

        // --- Chart Initialization (Using Demo Data) ---
        const pieCtx = document.getElementById('pieChart')?.getContext('2d');
        const doughnutCtx = document.getElementById('doughnutChart')?.getContext('2d');
        const columnCtx = document.getElementById('columnChart')?.getContext('2d');
        let pieChart, doughnutChart, columnChart;

        if (pieCtx) {
            pieChart = new Chart(pieCtx, {
                type: 'pie',
                data: {
                    labels: tokens.slice(0, 6).map(t => t.symbol), // Demo with first 6 tokens
                    datasets: [{
                        data: Array(6).fill(100/6),
                        backgroundColor: tokens.slice(0, 6).map(t => t.color),
                        borderWidth: 1
                    }]
                },
                options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { position: 'right' } } }
            });
        }

        if (doughnutCtx) {
            doughnutChart = new Chart(doughnutCtx, {
                type: 'doughnut',
                data: {
                    labels: tokens.slice(0, 6).map(t => t.symbol),
                    datasets: [{
                        data: Array(6).fill(100/6),
                        backgroundColor: tokens.slice(0, 6).map(t => t.color),
                        borderWidth: 1
                    }]
                },
                options: { responsive: true, maintainAspectRatio: false, cutout: '70%', plugins: { legend: { position: 'right' } } }
            });
        }

        if (columnCtx) {
            columnChart = new Chart(columnCtx, {
                type: 'bar',
                data: {
                    labels: tokens.slice(0, 6).map(t => t.symbol),
                    datasets: [
                        { label: 'Volume', data: Array(6).fill(50), backgroundColor: tokens.slice(0, 6).map(t => t.color), borderWidth: 1 },
                        { label: 'Trades', data: Array(6).fill(30), backgroundColor: tokens.slice(0, 6).map(t => `${t.color}80`), borderWidth: 1 }
                    ]
                },
                options: { responsive: true, maintainAspectRatio: false, scales: { y: { beginAtZero: true } }, plugins: { legend: { position: 'top' } } }
            });
        }

        // Function to update charts with random data (for demo purposes)
        function updateCharts() {
            if (pieChart) {
                pieChart.data.datasets[0].data = pieChart.data.labels.map(() => Math.random() * 20 + 5);
                pieChart.update();
            }
            if (doughnutChart) {
                doughnutChart.data.datasets[0].data = doughnutChart.data.labels.map(() => Math.random() * 10 + 1);
                doughnutChart.update();
            }
            if (columnChart) {
                columnChart.data.datasets[0].data = columnChart.data.labels.map(() => Math.random() * 50 + 10);
                columnChart.data.datasets[1].data = columnChart.data.labels.map(() => Math.random() * 30 + 5);
                columnChart.update();
            }
        }

        // --- Transaction Fetching and Display ---
        const transactionsBody = document.getElementById('transactions-body');
        const latestTransactionHashDiv = document.getElementById('latest-transaction-hash');
        let globalLastTransactionTimestamp = 0; // To track the newest transaction across all contracts

        async function fetchTransactions(contractAddress) {
            const url = `${BSC_API_BASE_URL}${contractAddress}`;
            try {
                const response = await axios.get(url);
                if (response.data.status === "1" && response.data.result && Array.isArray(response.data.result)) {
                    // Add the contract address to each transaction object for later use
                    return response.data.result.map(tx => ({ ...tx, fetchedContractAddress: contractAddress }));
                } else if (response.data.message === "NOTOK") {
                    console.error(`BSCScan API Error for ${contractAddress}:`, response.data.result);
                } else {
                    console.warn(`Unexpected API response structure for ${contractAddress}:`, response.data);
                }
            } catch (error) {
                console.error(`Error fetching transactions for ${contractAddress}:`, error);
            }
            return []; // Return empty array on error or no results
        }

        async function updateAllTransactions() {
            console.log("Fetching transactions from multiple contracts...");
            // Only show loading message if the table is currently empty or showing a previous error/no data message
             if (transactionsBody.rows.length <= 1 || transactionsBody.rows[0].cells[0].innerText.includes('Loading') || transactionsBody.rows[0].cells[0].innerText.includes('No token transactions') || transactionsBody.rows[0].cells[0].innerText.includes('Error fetching')) {
                 transactionsBody.innerHTML = `<tr><td colspan="4" class="px-6 py-4 text-center text-sm text-gray-500">Loading transactions...</td></tr>`;
             }
            /*if (latestTransactionHashDiv.innerText.includes('Fetching') || latestTransactionHashDiv.innerText.includes('No transactions') || latestTransactionHashDiv.innerText.includes('Error fetching')) {
                 latestTransactionHashDiv.innerHTML = 'Fetching latest transaction...';
            }*/


            const fetchPromises = CONTRACT_ADDRESSES.map(address => fetchTransactions(address));
            const resultsArrays = await Promise.all(fetchPromises);

            // Flatten the array of arrays into a single array of transactions
            let allTransactions = resultsArrays.flat();

            // Sort all transactions by timestamp in descending order
            allTransactions.sort((a, b) => parseInt(b.timeStamp) - parseInt(a.timeStamp));

            // Keep only the latest 20 unique transactions (based on hash)
            const uniqueTransactions = [];
            const seenHashes = new Set();
            for (const tx of allTransactions) {
                if (!seenHashes.has(tx.hash)) {
                    uniqueTransactions.push(tx);
                    seenHashes.add(tx.hash);
                }
                if (uniqueTransactions.length >= 20) break; // Limit to 20 unique transactions
            }


            if (uniqueTransactions.length > 0) {
                // Update latest transaction hash display
                const latestTx = uniqueTransactions[0];
                 latestTransactionHashDiv.innerHTML = `
                    <a href="https://bscscan.com/tx/${latestTx.hash}" target="_blank" class="transaction-link text-blue-600 hover:text-blue-800">
                        ${latestTx.hash}
                    </a>`;
                 globalLastTransactionTimestamp = parseInt(latestTx.timeStamp); // Update global timestamp marker


                transactionsBody.innerHTML = ''; // Clear existing transactions

                uniqueTransactions.forEach(tx => {
                    const row = transactionsBody.insertRow();
                    row.className = 'hover:bg-gray-50';

                    const tokenSymbol = tx.tokenSymbol || 'Unknown';
                    const tokenDecimal = parseInt(tx.tokenDecimal) || 18;
                    const amount = (parseFloat(tx.value) / Math.pow(10, tokenDecimal)).toFixed(6); // Format amount
                    const timeAgo = new Date(parseInt(tx.timeStamp) * 1000).toLocaleString();
                    // Find token color based on symbol or the contract address from the fetched data
                    const tokenInfo = tokens.find(t => t.symbol.toUpperCase() === tokenSymbol.toUpperCase() || (t.address && t.address.toUpperCase() === tx.contractAddress.toUpperCase()));
                    const tokenColor = tokenInfo?.color || '#cccccc';
                    const tokenName = tokenInfo?.name || tx.tokenName || tokenSymbol;


                    const shortHash = `${tx.hash.substring(0, 10)}...${tx.hash.length > 18 ? tx.hash.substring(tx.hash.length - 8) : ''}`; // Truncate hash


                    row.innerHTML = `
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-8 w-8 rounded-full mr-2" style="background-color: ${tokenColor}" title="${tokenName}"></div>
                                <div class="text-sm font-medium text-gray-900">${tokenSymbol}</div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-900">${amount}</td>
                        <td class="px-6 py-4 text-sm text-gray-500 break-hash">
                            <a href="https://bscscan.com/tx/${tx.hash}" target="_blank" class="transaction-link text-blue-600 hover:text-blue-800" title="${tx.hash}">
                                ${shortHash}
                            </a>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500">${timeAgo}</td>
                    `;
                });
            } else {
                transactionsBody.innerHTML = `<tr><td colspan="4" class="px-6 py-4 text-center text-sm text-gray-500">No token transactions found for these addresses.</td></tr>`;
                 if (latestTransactionHashDiv.innerText.includes('Fetching')) {
                     latestTransactionHashDiv.innerHTML = 'No transactions found.';
                 }
            }
        }

        // --- Token Info Cards (Demo Data) ---
        function updateTokenCards() {
            const cardsContainer = document.getElementById('token-cards-container');
            if (!cardsContainer) return;
            cardsContainer.innerHTML = ''; // Clear previous cards

            // Display cards for tokens that have a defined address in the 'tokens' array
            tokens.filter(token => token.address).slice(0, 8).forEach(token => { // Display up to 8 cards for tokens with addresses
                const priceChange = (Math.random() * 10 - 5).toFixed(2);
                const isPositive = parseFloat(priceChange) >= 0;

                const card = document.createElement('div');
                card.className = 'bg-white rounded-lg shadow p-4 border border-gray-200'; // Added border
                card.innerHTML = `
                    <div class="flex items-center mb-3">
                        <div class="flex-shrink-0 h-10 w-10 rounded-full mr-3" style="background-color: ${token.color}"></div>
                        <div>
                            <h3 class="font-semibold text-gray-800">${token.symbol}</h3>
                            <p class="text-xs text-gray-500 truncate" title="${token.name}">${token.name}</p>
                        </div>
                    </div>
                    <div class="flex justify-between items-end mt-2">
                        <div>
                            <p class="text-xl font-bold text-gray-800">$${(Math.random() * 500 + 0.1).toFixed(2)}</p>
                            <p class="text-sm ${isPositive ? 'text-green-600' : 'text-red-600'}">
                                ${isPositive ? '▲' : '▼'} ${Math.abs(priceChange)}%
                            </p>
                        </div>
                        <div class="text-xs text-gray-500 text-right">
                            ${(Math.random() * 1000 + 100).toFixed(0)} trades<br> (24h demo)
                        </div>
                    </div>
                `;
                cardsContainer.appendChild(card);
            });
        }


        // --- Initial Load and Interval ---
        function initializeDashboard() {
            console.log("Initializing Dashboard...");
            updateCharts(); // Initial chart draw (demo data)
            updateTokenCards(); // Initial token cards (demo data)
            updateAllTransactions(); // Initial transaction fetch

            // Set interval to update data
            setInterval(() => {
                console.log("Periodic update triggered.");
                updateCharts(); // Update charts (demo data)
                updateAllTransactions(); // Fetch new transactions
                updateTokenCards(); // Update token cards (demo data)
            }, UPDATE_INTERVAL);
        }

        // Wait for the DOM to be fully loaded before initializing
        document.addEventListener('DOMContentLoaded', initializeDashboard);


    </script>

    <script>
        // List of 10 cryptocurrency pairs to check (in USDT)
        const coins = ['BTCUSDT', 'ETHUSDT', 'OMNIUSDT', 'LTCUSDT', 'XRPUSDT', 'ADAUSDT', 'EOSUSDT', 'DOGEUSDT', 'DASHUSDT'];//'LTCUSDT', 'XRPUSDT', 'ADAUSDT', 'EOSUSDT', 'DOGEUSDT', 'ARDRUSDT' , 'XAIUSDT', 'DASHUSDT'

        // Array to store the prices of the coins
        let coinPrices = [];

        // Function to fetch prices for all coins
        function fetchCoinPrices() {
            /*const errorMessage = document.getElementById('error-message');
            errorMessage.textContent = ''; // Clear any previous error message
            errorMessage.style.display = 'none'; // Hide error message*/

            // Clear the coinPrices array before fetching new data
            coinPrices = [];

            // Fetching prices for each coin and storing them in the coinPrices array
            Promise.all(coins.map((coin, index) => 
                fetch(`https://api.binance.com/api/v3/ticker/price?symbol=${coin}`)
                    .then(response => response.json())
                    .then(data => {
                        coinPrices[index] = { name: coin.replace('USDT', ''), price: parseFloat(data.price).toFixed(2) };
                    })
                    .catch(error => {
                    	console.log("there is some error");
                        /*errorMessage.textContent = 'Failed to fetch data. Please try again later.';*/
                    })
            ))
            .then(() => {
                // Once all prices are fetched, update the HTML elements with the coin prices
                updatePricesInHTML();
            });
        }

        // Function to update prices in the HTML using the coinPrices array
        function updatePricesInHTML() {
            // Get the individual price elements by their IDs and update their content
            document.getElementById('btc-price').textContent = '$' + (coinPrices[0]?.price || 'N/A');
            document.getElementById('eth-price').textContent = '$' + (coinPrices[1]?.price || 'N/A');
            //document.getElementById('omni-price').textContent = '$' + (coinPrices[2]?.price || 'N/A');
            document.getElementById('ltc-price').textContent = '$' + (coinPrices[3]?.price || 'N/A');
            document.getElementById('xrp-price').textContent = '$' + (coinPrices[4]?.price || 'N/A');
            document.getElementById('ada-price').textContent = '$' + (coinPrices[5]?.price || 'N/A');
            document.getElementById('eos-price').textContent = '$' + (coinPrices[6]?.price || 'N/A');
            document.getElementById('doge-price').textContent = '$' + (coinPrices[7]?.price || 'N/A');
            document.getElementById('dash-price').textContent = '$' + (coinPrices[8]?.price || 'N/A');

            // Also update the new span for BTC price under the new div
            document.getElementById('btc-price-2').innerHTML = '$' + (coinPrices[0]?.price || 'N/A');
            document.getElementById('btc-price-3').textContent = '$' + (coinPrices[1]?.price || 'N/A');
            document.getElementById('btc-price-4').textContent = '$' + (coinPrices[2]?.price || 'N/A');
            //document.getElementById('dash-price-2').textContent = '$' + (coinPrices[10]?.price || 'N/A');

        }

        // Call the function to fetch coin prices
        fetchCoinPrices();

        // Optional: Update prices every 30 seconds
        setInterval(fetchCoinPrices, 30000);

        function simulateRealTimeUpdatesprice() {
            //setTimeout(fetchCoinPrices, 10000);
        }
    </script>

@if(sizeof($data['plans']))
	@if($data['plans']->first()->arbitrage==1)
		@if(strtotime('+ 24 Hours',strtotime($data['plans']->first()->arbitrage_date))>=strtotime(date('Y-m-d H:i:s')))
		<?php 
			$time=gmdate('H:i:s',strtotime('+ 24 Hours',strtotime($data['plans']->first()->arbitrage_date))-strtotime(date('Y-m-d H:i:s')));
			//dd($time);
		?>
		<script>
		  // Set your start time here as a string
		  const startTime = "{{$time}}"; // HH:MM:SS format

		  // Convert "HH:MM:SS" to total seconds
		  function timeStringToSeconds(timeStr) {
		    const [hours, minutes, seconds] = timeStr.split(':').map(Number);
		    return hours * 3600 + minutes * 60 + seconds;
		  }

		  let timeLeft = timeStringToSeconds(startTime);

		  function updateTimer() {
		    const hours = String(Math.floor(timeLeft / 3600)).padStart(2, '0');
		    const minutes = String(Math.floor((timeLeft % 3600) / 60)).padStart(2, '0');
		    const seconds = String(timeLeft % 60).padStart(2, '0');

		    document.getElementById('timer').textContent = `${hours}:${minutes}:${seconds}`;

		    if (timeLeft > 0) {
		      timeLeft--;
		    } else {
		      clearInterval(timerInterval);
		    }
		  }

		  // Start the timer
		  updateTimer();
		  const timerInterval = setInterval(updateTimer, 1000);
		</script>
										
		@endif
	@endif
@endif

	
</body>
</html>
