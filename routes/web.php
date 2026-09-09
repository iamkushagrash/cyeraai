<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return redirect('/login');
});
Route::get('/index', function () {
    return redirect('/login');
});
Route::get('/about', function () {
    return view('front2.about');
});
Route::get('/features', function () {
    return view('front2.features');
});
Route::get('/our-products', function () {
    return view('front2.our-products');
});
Route::get('/documentation', function () {
    return view('front2.documentation');
});
Route::get('/help', function () {
    return view('front2.help');
});
Route::get('/projects', function () {
    return view('front2.projects');
});
Route::get('/publications', function () {
    return view('front2.publications');
});

/*Route::get('/', function () {
    return view('frontnew.index');
});
Route::get('/about', function () {
    return view('frontnew.about');
});
Route::get('/features', function () {
    return view('frontnew.features');
});
Route::get('/our-products', function () {
    return view('frontnew.our-products');
});
Route::get('/documentation', function () {
    return view('frontnew.documentation');
});
Route::get('/help', function () {
    return view('frontnew.help');
});*/
/*Route::get('/', function () {
    return view('frontend.home');
});

Route::get('/about', function () {
    return view('frontend.about');
});

Route::get('/features', function () {
    return view('frontend.features');
});
Route::get('/projects', function () {
    return view('frontend.projects');
});
Route::get('/blockchain', function () {
    return view('frontend.blockchain');
});
Route::get('/metameet', function () {
    return view('frontend.metameet');
});
Route::get('/metaplay', function () {
    return view('frontend.metaplay');
});
Route::get('/cyerabrowser', function () {
    return view('frontend.cyerabrowser');
});
Route::get('/cyeracloud', function () {
    return view('frontend.cyeracloud');
});
Route::get('/metaswap', function () {
    return view('frontend.metaswap');
});
Route::get('/cyerachat', function () {
    return view('frontend.cyerachat');
});
Route::get('/cyeraai', function () {
    return view('frontend.cyeraai');
});
Route::get('/documentation', function () {
    return view('frontend.documentation');
});
Route::get('/help', function () {
    return view('frontend.help');
});*/

Route::get('/test', function () {
    return view('user.test');
});
Route::get('/terms', function () {
    return view('terms');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/Transaction/transactionStatus/{id}', 'CpsIncomeController@paymentStatus');

Route::get('/getSponsor/{sponsorid}', 'Auth\RegisterController@getSponsor');
Route::get('/register/{userid}', 'Auth\RegisterController@reffer');

Route::get('/bonanza', 'ProfileStoreController@bonanza');
Route::get('/first', 'ProfileStoreController@businessCalculation');


//Clear Cache
Route::get('/clear-cache', function () {
    $exitCode = Artisan::call('cache:clear');
    $exitCode = Artisan::call('config:clear');
    $exitCode = Artisan::call('view:clear');
    // return what you want
});


//Admin
Route::group(['middleware' => ['auth', 'adminverification']], function () {
    Route::get('/Main/Dashboard', 'HomeController@adminindexabhi');
    Route::get('/Main/DashboardToday', 'HomeController@adminindexabhitoday');
    Route::get('/Main/DashboardAllTime', 'HomeController@adminindexabhialltime');

    Route::get('/Main/UnpaidMembers', 'UserDetailsController@getAdminUnpaid');
    Route::get('/Main/PaidMembers', 'UserDetailsController@getAdminPaid');
    Route::get('/Main/AllMembers', 'UserDetailsController@getAdminAll');
    Route::post('/Main/AllMembers', 'UserDetailsController@getAdminAll');

    Route::get('/Main/LevelUsers', 'UserDetailsController@getAdminLevel');
    Route::get('/Main/BoosterUsers', 'UserDetailsController@getAdminBooster');
    Route::get('/Main/PowerUsers', 'UserDetailsController@getAdminPowerOpen');
    Route::get('/Main/LifetimeUsers', 'UserDetailsController@getAdminLifetimeOpen');
    Route::get('/Main/BlockedMembers', 'UserDetailsController@getAdminBlocked');

    Route::get('/Main/UserDirectTeam', 'AssetDetailController@userDirectPage');
    Route::post('/Main/UserDirectTeam', 'AssetDetailController@userDirectTeamList');
    Route::get('/Main/UserAllTeam', 'AssetDetailController@userAllTeamPage');
    Route::post('/Main/UserAllTeam', 'AssetDetailController@userAllTeamList');
    Route::post('/Main/UserAllTeamAjax', 'AssetDetailController@userAllTeamAjax');

    Route::get('/Main/User/{userid}', 'UserDetailsController@MemberEdit');
    Route::post('/Main/EditUser', 'UserDetailsController@MemberUpdate');
    Route::post('/Main/UserChangePassword', 'UserDetailsController@AdminUserPassword');
    Route::post('/Main/SearchUserId', 'UserDetailsController@searchUserbyUserId');
    Route::get('/Main/UserSearch', 'UserDetailsController@searchUserbyAdmin');

    Route::get('/Main/UserIncomeSearch', 'LevelDetailsController@searchUserforIncome');
    Route::post('/Main/UserIdIncomeSearch', 'LevelDetailsController@searchUserIncome');

    Route::get('/Main/UserOneClickSearch', 'LoanTransactionsController@searchUserforOneClick');
    Route::post('/Main/UserIdOneClickSearch', 'LoanTransactionsController@searchUserbyUserIdOneClick');
    Route::get('/Main/UserOneClick/{userid}', 'LoanTransactionsController@searchUserOneClick');
    Route::post('/Main/EditUserOneClick', 'LoanTransactionsController@MemberUpdateOneClick');

    Route::get('/Main/Lock/{userid}', 'UserDetailsController@MemberLock');
    Route::get('/Main/Unlock/{userid}', 'UserDetailsController@MemberUnlock');
    Route::post('/Main/UserPermissions/{userid}', 'UserDetailsController@updateUserPermission');

    Route::get('/Main/AdminUSDTuser', 'AccountDepositController@adminUSDTPage');
    Route::post('/Main/AdminUSDTuser', 'AccountDepositController@findUser');
    Route::post('/Main/TransferAdminUSDTToUser', 'AccountDepositController@transferUsdtToAdmin');

    Route::get('/Main/AdminUSDTReport', 'AccountDepositController@reportAdminUsdt');
    Route::post('/Main/AdminUSDTReport', 'AccountDepositController@reportAdminUsdt');
    Route::get('/Main/UserWalletBalance', 'AccountDepositController@userWalletBalance');

    Route::get('/Main/AdminSystemTopup', 'StackingDepositeController@showAdminDepositePage');
    Route::post('/Main/AdminSystemTopup', 'StackingDepositeController@findUserBogTopup');
    Route::post('/Main/AdminSystemTopupToUser', 'StackingDepositeController@transferBogTopupToAdmin');

    Route::get('/Main/AdminSystemTopupReport', 'StackingDepositeController@reportAdminSystemTopup');
    Route::post('/Main/AdminSystemTopupReport', 'StackingDepositeController@reportAdminSystemTopup');

    Route::get('/Main/AdminROITopup', 'StackingDepositeController@showAdminROItopPage');
    Route::post('/Main/AdminROITopup', 'StackingDepositeController@findUserROITopup');
    Route::post('/Main/AdminROIopupToUser', 'StackingDepositeController@transferROITopupToAdmin');

    Route::get('/Main/AdminROITopupReport', 'StackingDepositeController@reportAdminROITopup');
    Route::post('/Main/AdminROITopupReport', 'StackingDepositeController@reportAdminROITopup');

    Route::get('/Main/UserUSDTDeposit', 'BonusRewardController@userDepositHis');
    Route::post('/Main/UserUSDTDeposit', 'BonusRewardController@userDepositHis');

    Route::get('/Main/UserCyeraWalletDeposit', 'BonusRewardController@userCyeraWalletDepositHis');
    Route::post('/Main/UserCyeraWalletDeposit', 'BonusRewardController@userCyeraWalletDepositHis');

    Route::get('/Main/UserPackageHistory', 'BonusRewardController@userPeriodStaking');
    Route::post('/Main/UserPackageHistory', 'BonusRewardController@userPeriodStaking');

    Route::get('/Main/StakingIncomeReport', 'BonusRewardController@reportStaking');
    Route::post('/Main/StakingIncomeReport', 'BonusRewardController@reportStaking');
    Route::get('/Main/DirectIncomeReport', 'BonusRewardController@reportDirect');
    Route::post('/Main/DirectIncomeReport', 'BonusRewardController@reportDirect');
    Route::get('/Main/StakingReferralIncome', 'BonusRewardController@reportStakingReferral');
    Route::post('/Main/StakingReferralIncome', 'BonusRewardController@reportStakingReferral');
    Route::get('/Main/TeamDevelopmentIncome', 'BonusRewardController@reportTeamDevelopment');
    Route::post('/Main/TeamDevelopmentIncome', 'BonusRewardController@reportTeamDevelopment');
    Route::get('/Main/ClubIncome', 'BonusRewardController@reportClub');
    Route::post('/Main/ClubIncome', 'BonusRewardController@reportClub');
    Route::get('/Main/LifetimeReward', 'BonusRewardController@reportLifetime');
    Route::post('/Main/LifetimeReward', 'BonusRewardController@reportLifetime');

    Route::get('/Main/StakingIncomeReport36', 'BonusRewardController@reportStaking36');
    Route::post('/Main/StakingIncomeReport36', 'BonusRewardController@reportStaking36');
    Route::get('/Main/StakingIncomeReport30', 'BonusRewardController@reportStaking30');
    Route::post('/Main/StakingIncomeReport30', 'BonusRewardController@reportStaking30');
    Route::get('/Main/StakingIncomeReport18', 'BonusRewardController@reportStaking18');
    Route::post('/Main/StakingIncomeReport18', 'BonusRewardController@reportStaking18');
    Route::get('/Main/StakingIncomeReport15', 'BonusRewardController@reportStaking15');
    Route::post('/Main/StakingIncomeReport15', 'BonusRewardController@reportStaking15');
    Route::get('/Main/StakingIncomeReport8', 'BonusRewardController@reportStaking8');
    Route::post('/Main/StakingIncomeReport8', 'BonusRewardController@reportStaking8');
    Route::get('/Main/StakingIncomeReportProduct', 'BonusRewardController@reportStakingProduct');
    Route::post('/Main/StakingIncomeReportProduct', 'BonusRewardController@reportStakingProduct');

    Route::get('/Main/UserLoanReport', 'LoanTransactionsController@userLoanReport');
    Route::post('/Main/UserLoanReport', 'LoanTransactionsController@userLoanReport');
    Route::get('/Main/LoanRepaymentHistory', 'LoanTransactionsController@loanRepaymentReport');
    Route::post('/Main/LoanRepaymentHistory', 'LoanTransactionsController@loanRepaymentReport');

    Route::get('/Main/CompanyProfile', 'ProfileStoreController@companyProfilepage');
    Route::post('/Main/CompanyProfile', 'ProfileStoreController@editCompanyProfile');
    Route::post('/Main/StatusCompanyProfile', 'ProfileStoreController@editCompanyDepositStatus');

    Route::get('/Main/WithdrawRequests', 'TransactionDetailController@userWithdrawreq');
    Route::post('/Main/WithdrawRequests', 'TransactionDetailController@userWithdrawreq');
    Route::get('/Main/WithdrawRequestExcel', 'TransactionDetailController@WithdrawRequestsExcel');
    Route::get('/Main/WithdrawApprove/{paymentid}', 'TransactionDetailController@AdminwithdrawEdit');
    Route::post('/Main/WithdrawUpdate', 'TransactionDetailController@AdminwithdrawUpdateone');
    Route::get('/Main/WithdrawHistory', 'TransactionDetailController@userWithdrawHistory');
    Route::post('/Main/WithdrawHistory', 'TransactionDetailController@userWithdrawHistory');
    Route::get('/Main/WithdrawHistoryMW', 'TransactionDetailController@userWithdrawHistoryCyeraWallet');
    Route::post('/Main/WithdrawHistoryMW', 'TransactionDetailController@userWithdrawHistoryCyeraWallet');

    Route::get('/Main/WithdrawApproveByGateway/{paymentid}', 'TransactionDetailController@AdminwithdrawUpdateGateway');

    Route::get('/Main/SearchUserIdforTeamWithdrawalRequest', 'TransactionDetailController@searchUserforTeamWithdrawalRequest');
    Route::post('/Main/UserSearchforTeamWithdrawalRequest', 'TransactionDetailController@searchUserbyTeamWithdrawalRequest');

    Route::get('/Main/UserReadyToReleaseIncome', 'TransactionDetailController@getReadyToRelease');
    Route::get('/Main/PendingWithdrawOTP', 'TransactionDetailController@userPendingWithdrawOTP');

    Route::get('/Main/Support', 'SupportQueryController@adminsupport');
    Route::get('/Main/TicketView/{title}/{id}', 'SupportQueryController@viewTicketAdmin');
    Route::post('/Main/ReplyTicket', 'SupportQueryController@postReplyAdmin');

    Route::get('/Main/AdminLoanRemove', 'LevelDetailsController@showAdminLoanRemovePage');
    Route::post('/Main/AdminLoanRemove', 'LevelDetailsController@findUserLoanRemove');
    Route::post('/Main/AdminLoanRemoveToUser', 'LevelDetailsController@removeLoanOfUser');

    Route::get('/Main/ReduceUserWallet', 'LevelDetailsController@adminReduceWalletPage');
    Route::post('/Main/ReduceUserWallet', 'LevelDetailsController@findUserReduceWallet');
    Route::post('/Main/ReduceUserWalletByAdmin', 'LevelDetailsController@reduceUsdtByAdmin');
    Route::get('/Main/UserWalletReduceReport', 'LevelDetailsController@reportAdminReduceWallet');
    Route::post('/Main/UserWalletReduceReport', 'LevelDetailsController@reportAdminReduceWallet');


    Route::get('/Main/ReduceUserIncome', 'LevelDetailsController@adminReduceIncomePage');
    Route::post('/Main/ReduceUserIncome', 'LevelDetailsController@findUserReduceIncome');
    Route::post('/Main/ReduceUserIncomeByAdmin', 'LevelDetailsController@adminReduceUserIncome');
    Route::get('/Main/UserIncomeReduceHistory', 'LevelDetailsController@userIncomeReduceHistory');
    Route::post('/Main/UserIncomeReduceHistory', 'LevelDetailsController@userIncomeReduceHistory');

    //SilverSearch    
    Route::get('/Main/SearchSilverTeam', 'BonanzaDetailsController@userSearchSilverDowlinePage');
    Route::post('/Main/SearchSilverTeam', 'BonanzaDetailsController@userSearchSilverDowlineList');
    Route::get('/Main/SilverIncomeWithdrawal', 'BonanzaDetailsController@getSilverWithdrawalAndReady');
    Route::get('/Main/SearchUserIdforSilverTeamWithdrawal', 'BonanzaDetailsController@searchUserforSilverTeamWithdrawal');
    Route::post('/Main/UserSearchforSilverTeamWithdrawal', 'BonanzaDetailsController@searchUserbySilverTeamWithdrawal');

    //Searches
    Route::get('/Main/SearchUserIdforBusiness', 'BonanzaDetailsController@searchUserforBusiness');
    Route::post('/Main/UserSearchforBusiness', 'BonanzaDetailsController@searchUserbyBusiness');

    Route::get('/Main/SearchUserIdforTeamWithdrawal', 'BonanzaDetailsController@searchUserforTeamWithdrawal');
    Route::post('/Main/UserSearchforTeamWithdrawal', 'BonanzaDetailsController@searchUserbyTeamWithdrawal');

    Route::get('/Main/SearchUserIdforReadyWithdrawal', 'BonanzaDetailsController@searchUserforReadyWithdrawal');
    Route::post('/Main/SearchUserforReadyWithdrawal', 'BonanzaDetailsController@searchUserbyReadyWithdrawal');

    Route::get('/Main/SearchUserIdforTeamClubIncome', 'BonanzaDetailsController@searchUserforTeamClubIncome');
    Route::post('/Main/UserSearchforTeamClubIncome', 'BonanzaDetailsController@searchUserbyTeamClubIncome');

    Route::get('/Main/SearchUserIdforTeamRewardIncome', 'BonanzaDetailsController@searchUserforTeamRewardIncome');
    Route::post('/Main/UserSearchforTeamRewardIncome', 'BonanzaDetailsController@searchUserbyTeamRewardIncome');

    Route::get('/Main/SearchUserIdforTotalReport', 'BonanzaDetailsController@searchUserforTotalReport');
    Route::post('/Main/UserSearchforTotalReport', 'BonanzaDetailsController@searchUserbyTotalReport');


    //Send Userid to CyeraWallet
    Route::get('/Main/SearchUserIdforMW', 'LoanTransactionsController@searchUserforMWData');
    Route::post('/Main/UserSearchforMW', 'LoanTransactionsController@searchUserbyMWData');

});




//User
Route::group(['middleware' => ['auth', 'userverification']], function () {
    Route::get('/User/Dashboard', 'HomeController@userindex');
    Route::get('/User/Documentation', 'HomeController@documentation');
    Route::get('/User/ArbitrageDashboard', 'HomeController@userarbitrageindex');

    Route::get('/User/EditProfile', 'UserDetailsController@showEditData');
    Route::post('/User/EditProfile', 'UserDetailsController@userUpdate');
    Route::get('/User/resendProfileOtp', 'AssetDetailChangesController@resendProfileEditOtpWeb');

    Route::get('/User/ChangePassword', 'UserDetailsController@showChangePass');
    Route::post('/User/ChangePassword', 'UserDetailsController@UserChangePass');

    Route::get('/User/DirectTeam', 'UserDetailsController@getDirect');
    Route::get('/User/AllTeam', 'AssetDetailChangesController@getTotal');
    Route::get('/User/TeamSummary', 'UserDetailsController@userTeamSummary');
    Route::get('/User/Treeview', 'ArbitrageController@getTreeView');
    Route::get('/User/Treeview/children/{parentId}', 'ArbitrageController@getTreeChildren')->name('user.tree.children');

    //New Registration & Referral
    Route::get('/User/NewRegistration', 'AssetDetailController@userNewRegistrationPage');
    Route::post('/User/NewRegistration', 'AssetDetailController@userNewRegistration');
    Route::get('/User/Referral', 'AssetDetailController@userReferralPage');
    Route::get('/getSponsorNew/{sponsorid}', 'AssetDetailController@getSponsor');


    //Transaction using hash System
    /*Route::get('/User/BuyCAI', 'TransactionDetailController@showTransactionPage');
    Route::post('/User/BuyCAI', 'TransactionDetailController@submitTransaction');*/

    // Transaction using NP
    Route::get('/User/Deposit', 'CpsIncomeController@showPage');
    Route::post('/User/Deposit', 'CpsIncomeController@submitTransaction');

    // Deposit History
    Route::get('/User/DepositHistory', 'BonusRewardController@userDepositHistory');

    // Stack 
    Route::get('/User/Stake', 'WalletTransferController@stakePage');
    Route::post('/User/getUser', 'WalletTransferController@getUserDetail');
    Route::post('/User/Stake', 'WalletTransferController@stakemwt');
    Route::get('/User/StakingHistory', 'BonusRewardController@userUpgradeHistory');
    Route::get('/User/StakingTxnHistory', 'BonusRewardController@userUpgradeTxnHistory');

    // loan 
    Route::get('/User/getUserLoan', 'LoanDetailsController@loanPage');
    Route::post('/User/GetLoan', 'LoanDetailsController@getLoan');
    Route::get('/User/RepayLoan', 'LoanDetailsController@loanRepaymentPage');
    Route::post('/User/RepayLoan', 'LoanDetailsController@loanRepayment');
    Route::get('/User/RepaymentHistory', 'LoanDetailsController@userRepaymentHistory');

    //Income
    Route::get('/User/IncomeOverview', 'BonusRewardController@userIncomeOverview');
    Route::post('/User/IncomeOverview', 'BonusRewardController@userIncomeOverview');

    Route::get('/User/StakingReward', 'BonusRewardController@userStakingReport');
    Route::post('/User/StakingReward', 'BonusRewardController@userStakingReport');
    Route::get('/User/DirectBonus', 'BonusRewardController@userDirectReport');
    Route::post('/User/DirectBonus', 'BonusRewardController@userDirectReport');
    Route::get('/User/StakingReferralReward', 'BonusRewardController@userStakingReferralReward');
    Route::post('/User/StakingReferralReward', 'BonusRewardController@userStakingReferralReward');
    Route::get('/User/StakingReferralReward/{txndate}', 'BonusRewardController@userStakingReferralRewardDate');
    Route::get('/User/TeamDevelopmentReward', 'BonusRewardController@userTeamDevelopmentReward');
    Route::post('/User/TeamDevelopmentReward', 'BonusRewardController@userTeamDevelopmentReward');
    Route::get('/User/TeamDevelopmentReward/{txndate}', 'BonusRewardController@userTeamDevelopmentRewardDate');
    Route::get('/User/ClubReward', 'BonusRewardController@userClubReward');
    Route::post('/User/ClubReward', 'BonusRewardController@userClubReward');
    Route::get('/User/LifetimeAchievementReward', 'BonusRewardController@userLifetimeReward');
    Route::post('/User/LifetimeAchievementReward', 'BonusRewardController@userLifetimeReward');
    Route::get('/User/PoolIncome', 'PoolIncomeController@showPoolIncomePage');
    Route::get('/User/RankIncome', 'RankIncomeController@userRankIncome');
    Route::post('/User/RankIncome', 'RankIncomeController@userRankIncome');


    //Withdraw
    Route::get('/User/WithdrawRequest', 'WithdrawInfoController@withdrawPage');
    Route::post('/User/WithdrawRequest', 'WithdrawInfoController@withdrawRequest');
    Route::get('/User/WithdrawalHistory', 'BonusRewardController@withdrawHistoryUser');
    Route::get('/User/resendWithdrawOtp', 'AssetDetailChangesController@resendWithdrawOtpWeb');

    Route::post('/User/WithdrawLifetimeReward', 'WithdrawInfoController@withdrawLifetimeIncome');
    Route::post('/User/WithdrawClubReward', 'WithdrawInfoController@withdrawClubIncome');

    //Support
    Route::post('/User/CreateTicket', 'SupportQueryController@UserCreateTicket');
    Route::get('/User/ViewTicket', 'SupportQueryController@viewUserTicket');
    Route::get('/User/TicketView/{title}/{id}', 'SupportQueryController@viewTicketSingleUser');
    Route::post('/User/ReplyTicket', 'SupportQueryController@postReplyUser');


    //Route::get('/User/WinterBlastBonanza','HomeController@bonanzaList');

    Route::get('/User/SearchTeamBusiness', 'AssetDetailChangesController@SearchTeamBusinessPage');
    Route::post('/User/SearchUserTeamBusiness', 'AssetDetailChangesController@SearchUserTeamBusinessDate');


});








/*//SUBAdmin
Route::group(['middleware' => ['auth','subadminverification']], function () {
    Route::get('/Main/Dashboard', 'HomeController@adminindexabhi');

    Route::get('/Main/UnpaidMembers', 'UserDetailsController@getAdminUnpaid');
    Route::get('/Main/PaidMembers', 'UserDetailsController@getAdminPaid');
    Route::get('/Main/AllMembers', 'UserDetailsController@getAdminAll');
    Route::post('/Main/AllMembers', 'UserDetailsController@getAdminAll');

    Route::get('/Main/LevelUsers', 'UserDetailsController@getAdminLevel');
    Route::get('/Main/BoosterUsers', 'UserDetailsController@getAdminBooster');
    Route::get('/Main/PowerUsers', 'UserDetailsController@getAdminPowerOpen');
    Route::get('/Main/LifetimeUsers', 'UserDetailsController@getAdminLifetimeOpen');

    Route::get('/Main/UserDirectTeam', 'AssetDetailController@userDirectPage');
    Route::post('/Main/UserDirectTeam', 'AssetDetailController@userDirectTeamList');
    Route::get('/Main/UserAllTeam', 'AssetDetailController@userAllTeamPage');
    Route::post('/Main/UserAllTeam', 'AssetDetailController@userAllTeamList');

    Route::get('/Main/User/{userid}', 'UserDetailsController@MemberEdit');
    Route::post('/Main/EditUser', 'UserDetailsController@MemberUpdate');
    Route::post('/Main/UserChangePassword', 'UserDetailsController@AdminUserPassword');
    Route::post('/Main/SearchUserId', 'UserDetailsController@searchUserbyUserId');
    Route::get('/Main/UserSearch', 'UserDetailsController@searchUserbyAdmin');

    Route::get('/Main/UserIncomeSearch', 'LevelDetailsController@searchUserforIncome');
    Route::post('/Main/UserIdIncomeSearch', 'LevelDetailsController@searchUserIncome');

    Route::get('/Main/UserOneClickSearch', 'LoanTransactionsController@searchUserforOneClick');
    Route::post('/Main/UserIdOneClickSearch', 'LoanTransactionsController@searchUserbyUserIdOneClick');
    Route::get('/Main/UserOneClick/{userid}', 'LoanTransactionsController@searchUserOneClick');
    Route::post('/Main/EditUserOneClick', 'LoanTransactionsController@MemberUpdateOneClick');

    Route::get('/Main/Lock/{userid}', 'UserDetailsController@MemberLock');
    Route::get('/Main/Unlock/{userid}', 'UserDetailsController@MemberUnlock');
    Route::post('/Main/UserPermissions/{userid}', 'UserDetailsController@updateUserPermission');


    Route::get('/Main/AdminUSDTReport', 'AccountDepositController@reportAdminUsdt');
    Route::post('/Main/AdminUSDTReport', 'AccountDepositController@reportAdminUsdt');
    Route::get('/Main/UserWalletBalance', 'AccountDepositController@userWalletBalance');

    Route::get('/Main/AdminSystemTopupReport', 'StackingDepositeController@reportAdminSystemTopup');
    Route::post('/Main/AdminSystemTopupReport', 'StackingDepositeController@reportAdminSystemTopup');

    Route::get('/Main/AdminROITopupReport', 'StackingDepositeController@reportAdminROITopup');
    Route::post('/Main/AdminROITopupReport', 'StackingDepositeController@reportAdminROITopup');

    Route::get('/Main/UserUSDTDeposit', 'BonusRewardController@userDepositHis');
    Route::post('/Main/UserUSDTDeposit', 'BonusRewardController@userDepositHis');

    Route::get('/Main/UserPackageHistory', 'BonusRewardController@userPeriodStaking');
    Route::post('/Main/UserPackageHistory', 'BonusRewardController@userPeriodStaking');

    Route::get('/Main/StakingIncomeReport', 'BonusRewardController@reportStaking');
    Route::post('/Main/StakingIncomeReport', 'BonusRewardController@reportStaking');
    Route::get('/Main/DirectIncomeReport', 'BonusRewardController@reportDirect');
    Route::post('/Main/DirectIncomeReport', 'BonusRewardController@reportDirect');
    Route::get('/Main/StakingReferralIncome', 'BonusRewardController@reportStakingReferral');
    Route::post('/Main/StakingReferralIncome', 'BonusRewardController@reportStakingReferral');
    Route::get('/Main/TeamDevelopmentIncome', 'BonusRewardController@reportTeamDevelopment');
    Route::post('/Main/TeamDevelopmentIncome', 'BonusRewardController@reportTeamDevelopment');
    Route::get('/Main/ClubIncome', 'BonusRewardController@reportClub');
    Route::post('/Main/ClubIncome', 'BonusRewardController@reportClub');
    Route::get('/Main/LifetimeReward', 'BonusRewardController@reportLifetime');
    Route::post('/Main/LifetimeReward', 'BonusRewardController@reportLifetime');

    Route::get('/Main/StakingIncomeReport36', 'BonusRewardController@reportStaking36');
    Route::post('/Main/StakingIncomeReport36', 'BonusRewardController@reportStaking36');
    Route::get('/Main/StakingIncomeReport30', 'BonusRewardController@reportStaking30');
    Route::post('/Main/StakingIncomeReport30', 'BonusRewardController@reportStaking30');
    Route::get('/Main/StakingIncomeReport18', 'BonusRewardController@reportStaking18');
    Route::post('/Main/StakingIncomeReport18', 'BonusRewardController@reportStaking18');
    Route::get('/Main/StakingIncomeReport15', 'BonusRewardController@reportStaking15');
    Route::post('/Main/StakingIncomeReport15', 'BonusRewardController@reportStaking15');
    Route::get('/Main/StakingIncomeReport8', 'BonusRewardController@reportStaking8');
    Route::post('/Main/StakingIncomeReport8', 'BonusRewardController@reportStaking8');

    Route::get('/Main/UserLoanReport', 'LoanTransactionsController@userLoanReport');
    Route::post('/Main/UserLoanReport', 'LoanTransactionsController@userLoanReport');
    Route::get('/Main/LoanRepaymentHistory', 'LoanTransactionsController@loanRepaymentReport');
    Route::post('/Main/LoanRepaymentHistory', 'LoanTransactionsController@loanRepaymentReport');

    Route::get('/Main/CompanyProfile', 'ProfileStoreController@companyProfilepage');
    Route::post('/Main/CompanyProfile', 'ProfileStoreController@editCompanyProfile');
    Route::post('/Main/StatusCompanyProfile', 'ProfileStoreController@editCompanyDepositStatus');

    Route::get('/Main/WithdrawRequests', 'TransactionDetailController@userWithdrawreq');
    Route::get('/Main/WithdrawRequestExcel', 'TransactionDetailController@WithdrawRequestsExcel');
    //Route::get('/Main/WithdrawApprove/{paymentid}', 'TransactionDetailController@AdminwithdrawEdit');
    //Route::post('/Main/WithdrawUpdate', 'TransactionDetailController@AdminwithdrawUpdateone');
    Route::get('/Main/WithdrawHistory', 'TransactionDetailController@userWithdrawHistory');
    Route::post('/Main/WithdrawHistory', 'TransactionDetailController@userWithdrawHistory');

    Route::get('/Main/UserReadyToReleaseIncome', 'TransactionDetailController@getReadyToRelease');
    Route::get('/Main/PendingWithdrawOTP', 'TransactionDetailController@userPendingWithdrawOTP');

    Route::get('/Main/Support', 'SupportQueryController@adminsupport');
    Route::get('/Main/TicketView/{title}/{id}','SupportQueryController@viewTicketAdmin');
    Route::post('/Main/ReplyTicket','SupportQueryController@postReplyAdmin');

    Route::get('/Main/UserWalletReduceReport', 'LevelDetailsController@reportAdminReduceWallet');
    Route::post('/Main/UserWalletReduceReport', 'LevelDetailsController@reportAdminReduceWallet');

    Route::get('/Main/UserIncomeReduceHistory', 'LevelDetailsController@userIncomeReduceHistory');
    Route::post('/Main/UserIncomeReduceHistory', 'LevelDetailsController@userIncomeReduceHistory');

});*/