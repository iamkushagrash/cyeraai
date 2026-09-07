<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

/*Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});*/

Route::post('/register', 'Api\V1\RegisterController@register');
Route::post('/getSponser','Api\V1\RegisterController@getSponsor');
Route::post('/forgotPassword','Api\V1\RegisterController@forgetPasswordMailSend');

Route::get('/appUpdate','Api\V1\SupportController@appUpd');


Route::post('/bal-check','Api\V1\IncomeDetailController@checkRemainingIncomeforMetaW');

Route::post('/withdraw-api','Api\V1\IncomeDetailController@requestWithdrawForMetawallet');

//Route::post('/wallet-add','Api\V1\DashboardPopupController@addWalletFromMetawallet');

Route::post('/bal-check2','Api\V1\DashboardPopupController@checkIncomeAndWalletforMetaW');

Route::post('/walletCreditDebit','Api\V1\DashboardPopupController@walletCreditWithdrawDebitBoth');



Route::group(['middleware' => ['auth:api']], function () {
    Route::get('/user/dashboard','Api\V1\UserDetailController@dashboard');
    Route::get('/user/userProfile','Api\V1\UserDetailController@userProfile');
    Route::post('/user/userUpdate','Api\V1\UserDetailController@updateUserDetails');
    Route::post('/user/changePassword','Api\V1\UserDetailController@updateUserPasswords');
    Route::get('/user/arbitrageURL','Api\V1\UserDetailController@arbitrageUrl');
    Route::get('/user/documentations','Api\V1\DocumentationController@index');
    Route::get('/user/todayData','Api\V1\UserDetailController@todayData');
    Route::get('/user/productWalletData','Api\V1\UserDetailController@productWalletData');

    //team
    Route::get('/user/directTeam','Api\V1\UserDetailController@directTeam');
    Route::get('/user/totalTeam','Api\V1\UserDetailController@getTotalTeam');
    Route::get('/user/teamDashboard','Api\V1\UserDetailController@getTotalTeamHeading');
    Route::post('/user/userDirectList','Api\V1\UserDetailController@userDirectList');

    //loan
    Route::get('/user/loanPageDetails', 'Api\V1\WalletTransferController@loanPage');
    Route::post('/user/getLoan', 'Api\V1\WalletTransferController@getUserLoan');
    Route::get('/user/loanRepayDetails', 'Api\V1\WalletTransferController@loanRepaymentPage');
    Route::post('/user/rapayLoan', 'Api\V1\WalletTransferController@repayUserLoan');
    Route::get('/user/repaymentHistory', 'Api\V1\IncomeDetailController@loanRepaymentHistory');

    //deposit
    Route::get('/user/depositDetails','Api\V1\TransactionDetailController@getPGTokenType');
    Route::post('/user/depositUSDT','Api\V1\TransactionDetailController@depositPGCurrency');
    /*Route::get('user/withdrawDetailCrypto','Api\V1\CryptoIncomeController@withdrawPageCrypto');
    Route::post('user/withdrawDetailCrypto','Api\V1\CryptoIncomeController@withdrawRequestCrypto');*/

    Route::get('/user/depositHistory', 'Api\V1\IncomeDetailController@userDepositHistory');

    //upgrade
    Route::get('/user/upgradePackageDetails', 'Api\V1\WalletTransferController@stakePage');
    Route::post('/user/getUser','Api\V1\WalletTransferController@getUserDetail');
    Route::post('/user/userPackageUpgrade', 'Api\V1\WalletTransferController@stakeCAIApi');
    Route::get('/user/myPackages', 'Api\V1\IncomeDetailController@userMyPackages');
    Route::get('/user/transactionHistory', 'Api\V1\IncomeDetailController@userTransactionHistory');

    //income reports
    Route::post('/user/StakingRewardReport', 'Api\V1\IncomeDetailController@userStakingReport');
    Route::post('/user/directBonusReport', 'Api\V1\IncomeDetailController@userDirectReport');
    Route::post('/user/stakingReferralReport', 'Api\V1\IncomeDetailController@userStakingReferralReport');
    Route::get('/user/stakingReferralReport/{txndate}', 'Api\V1\IncomeDetailController@userStakingReferralReportDate');
    Route::post('/user/teamDevelopmentReport', 'Api\V1\IncomeDetailController@usertTeamDevelopmemtReport');
    Route::get('/user/teamDevelopmentReport/{txndate}', 'Api\V1\IncomeDetailController@userTeamDevelopmemtReportDate');
    Route::post('/user/clubRewardReport', 'Api\V1\IncomeDetailController@userClubReport');
    Route::post('/user/lifetimeAchievementReward', 'Api\V1\IncomeDetailController@userLifetimeReport');

    //Withdrawal 
    Route::get('/user/wihdrawData','Api\V1\WalletTransferController@userWithraw');
    Route::post('/user/wihdrawRequest','Api\V1\WalletTransferController@withdrawRequest');
    Route::get('/user/withdrawHistory', 'Api\V1\IncomeDetailController@userWithdrawHistory');

    //Support 
    Route::get('/user/viewTicket','Api\V1\SupportController@viewUserTicket');
    Route::post('/user/createTicket','Api\V1\SupportController@userCreateTicket');
    Route::get('/user/ticketView/{id}','Api\V1\SupportController@viewTicketSingleUser');
    Route::post('/user/replyTicket','Api\V1\SupportController@postReplyUser');


    Route::get('/user/notifications', 'Api\V1\NotificationController@show');
    Route::get('/user/dashboardpopup', 'Api\V1\DashboardPopupController@show');

    Route::post('/user/newRegistration', 'Api\V1\SupportController@userNewRegistration');
    Route::post('/user/getSponsorIn','Api\V1\SupportController@getSponsorInside');

    Route::post('/user/logOut','Api\V1\UserDetailController@logOutUser');

});
