    <style>
        .chart-container {
            position: relative;
            height: 300px;
            width: 100%;
        }
        .transaction-link {
            transition: all 0.2s ease;
        }
        .transaction-link:hover {
            color: #f0b90b;
            transform: translateX(2px);
        }
        .pulse {
            animation: pulse 1s infinite;
        }
        @keyframes pulse {
            0% { opacity: 1; }
            50% { opacity: 0.5; }
            100% { opacity: 1; }
        }
    </style>
    <div class="max-w-7xl mx-auto">
        <h1 class="text-3xl font-semibold text-gray-700 mb-4" style="color:#fff !important;">Arbitrage Live Trading</h1>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
            <!-- Pie Chart -->
            <div class="--bs-default rounded-lg shadow p-4" style="border:1px solid #fff !important;">
                <h2 class="text-xl font-semibold text-gray-700 mb-4" style="color:#fff !important;">Token Distribution </h2>
                <div class="chart-container">
                    <canvas id="pieChart"></canvas>
                </div>
            </div>
            
            <!-- Doughnut Chart -->
            <div class="--bs-default rounded-lg shadow p-4" style="border:1px solid #fff !important;">
                <h2 class="text-xl font-semibold text-gray-700 mb-4" style="color:#fff !important;">Profit Share </h2>
                <div class="chart-container">
                    <canvas id="doughnutChart"></canvas>
                </div>
            </div>
            
            <!-- Cluster Column Chart -->
            <div class="--bs-default rounded-lg shadow p-4" style="border:1px solid #fff !important;">
                <h2 class="text-xl font-semibold text-gray-700 mb-4" style="color:#fff !important;">Volume Comparison</h2>
                <div class="chart-container">
                    <canvas id="columnChart"></canvas>
                </div>
            </div>
        </div>
        
        <!-- Live Data Section -->
        <div class="--bs-default rounded-lg shadow p-6 mb-6" style="border:1px solid #fff !important;">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl font-semibold text-gray-700" style="color:#fff !important;">Live Arbitrage Transactions</h2>
                <div class="flex items-center">
                    <span class="h-3 w-3 rounded-full bg-green-500 mr-2"></span>
                    <span class="text-sm text-gray-600" style="color:#fff !important;">Live</span>
                    <span class="ml-2 text-sm text-gray-500 pulse" style="color:#fff !important;">Updates on every new transaction</span>
                </div>
            </div>
            
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Token Pair</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Profit</th><!-- 
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Profit</th> -->
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Transaction Hash</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Time</th>
                        </tr>
                    </thead>
                    <tbody id="transactions-body" class="bg-white divide-y divide-gray-200">
                        <!-- Transactions will be inserted here -->
                    </tbody>
                </table>
            </div>
        </div>
        
        <!-- Token Info Cards -->
        <!-- <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8"> -->
            <!-- Cards will be inserted here -->
        <!-- </div> -->
    </div>

    
