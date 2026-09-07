<?php

namespace App\Http\Controllers;


use GuzzleHttp\Client;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Mail\VerificationEmail;
use App\UserDetails;
use App\User;
use DB;
use Session;
use Illuminate\Support\Facades\Crypt;
use App\Http\Controllers\SupportQueryController;
class ArbitrageController extends Controller
{
    // Load Blade view
    public function index()
    {
        return view('frontend.arbitrageforapi');
    }

    // API: fetch latest transactions
public function latestTransactions()
{
    $alchemyApiKey = env('ALCHEMY_API_KEY', 'your-alchemy-key');
    $alchemyApiUrl = "https://eth-mainnet.g.alchemy.com/v2/{$alchemyApiKey}";
    $client = new Client();

    // 1️⃣ Get latest block
    $responseBlock = $client->post($alchemyApiUrl, [
        'json' => [
            "jsonrpc" => "2.0",
            "method" => "eth_blockNumber",
            "params" => [],
            "id" => 1
        ]
    ]);

    $blockData = json_decode($responseBlock->getBody(), true);
    $latestBlockHex = $blockData['result'] ?? '0x0';

    // 2️⃣ Get transactions from latest block
    $responseTx = $client->post($alchemyApiUrl, [
        'json' => [
            "jsonrpc" => "2.0",
            "method" => "eth_getBlockByNumber",
            "params" => [$latestBlockHex, true],
            "id" => 2
        ]
    ]);

    $blockDataTx = json_decode($responseTx->getBody(), true);
    $transactions = $blockDataTx['result']['transactions'] ?? [];
    $timestamp = hexdec($blockDataTx['result']['timestamp'] ?? time());

    // 3️⃣ Main ERC-20 tokens with decimals
    $mainTokens = [
        '0xc02aaa39b223fe8d0a0e5c4f27ead9083c756cc2' => ['symbol'=>'WETH','decimals'=>18],
        '0xa0b86991c6218b36c1d19d4a2e9eb0ce3606eb48' => ['symbol'=>'USDC','decimals'=>6],
        '0xdac17f958d2ee523a2206206994597c13d831ec7' => ['symbol'=>'USDT','decimals'=>6],
        '0x6b175474e89094c44da98b954eedeac495271d0f' => ['symbol'=>'DAI','decimals'=>18],
        '0x2260fac54972f10b80d9c8c9d120a2b02b514a60' => ['symbol'=>'WBTC','decimals'=>8]
    ];

    $formatted = [];

    foreach ($transactions as $tx) {
        $to = strtolower($tx['to'] ?? '');
        $input = $tx['input'] ?? '';

        // Only ERC-20 transfers
        if (isset($mainTokens[$to]) && substr($input,0,10) === '0xa9059cbb') { // transfer()
            $recipient = '0x' . substr($input, 34, 40);
            $amountHex = '0x' . substr($input, 74);
            $token = $mainTokens[$to];
            $amount = hexdec($amountHex) / (10 ** $token['decimals']);

            $formatted[] = [
                'symbol' => $token['symbol'],
                'amount' => $amount,
                'from' => $tx['from'],
                'to' => $recipient,
                'hash' => $tx['hash'],
                'timestamp' => $timestamp
            ];
        }
    }

    return response()->json($formatted);
}


    public function getTreeView()
    {
        $userId = Session::get('user.id');

        $rootUser = DB::table('users as u')
            ->join('user_details as ud', 'u.id', '=', 'ud.userid')
            ->select(
                'u.id',
                'u.usersname as name',
                'u.uuid as userid',
                DB::raw('DATE_FORMAT(u.doj,"%d-%m-%Y") as doj'),
                'ud.current_self_investment as package',
                'ud.current_investment as teamtotal',
                DB::raw('CASE WHEN ud.userstatus=1 THEN "Active" ELSE "Inactive" END as status')
            )
            ->where('ud.id', $userId)
            ->first();

        return view('user.treeview', compact('rootUser'));
    }

    public function getTreeChildren($parentId)
    {
        $children = DB::table('users as u')
            ->join('user_details as ud', 'u.id', '=', 'ud.userid')
            ->select(
                'u.id',
                'u.usersname as name',
                'u.uuid as userid',
                DB::raw('DATE_FORMAT(u.doj,"%d-%m-%Y") as doj'),
                'ud.current_self_investment as package',
                'ud.current_investment as teamtotal',
                DB::raw('CASE WHEN ud.userstatus=1 THEN "Active" ELSE "Inactive" END as status')
            )
            ->where('ud.sponsorid', $parentId)
            ->get();

        return view('user.tree_children', compact('children'));
    }



}
