<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------

$route['default_controller'] = "home";
*/
$route['default_controller'] = "home";
$route['404_override'] = '';

$route['user'] = "user/user"; 
$route['login'] = "Home/signin"; 
$route['loans'] = "loans/overview"; 
$route['reports'] = "reports/overview";
$route['client'] = "client/overview";
$route['settings'] = "settings/overview";
$route['product'] = "product/overview";
$route['forms/:any/:any'] = "loans/info/form";
$route['forms/comaker/:num/:num'] = "loans/info/form/$1/$2";

$route['settings/user/roles'] = "settings/roles";
$route['settings/user/roles/:num'] = "settings/roles";
$route['settings/user/roles/edit/:num'] = "settings/roles/edit";
$route['settings/user/roles/edit/:num/:num'] = "settings/roles/edit";

//SETTINGS CHARGES
$route['settings/charges'] = "settings/charges";
$route['settings/charges/action/:any'] = "settings/charges/action";

//PROFILE
$route['profile/overview/:num'] = "settings/user/profile";
$route['profile/rights/:num'] = "settings/user/rights";
$route['profile/rights/:num/:num'] = "settings/user/rights";
$route['profile/branch/:num'] = "settings/user/branch";
$route['profile/branch/:num/:num'] = "settings/user/branch";

$route['profile/create'] = "settings/user/create";

//USER MODULES
$route['settings/user/modules'] = "settings/modules";
$route['settings/user/modules/:num'] = "settings/modules";
$route['settings/user/modules/manage/:num'] = "settings/modules/manage";
$route['settings/user/modules/manage/:num/:num'] = "settings/modules/manage";
$route['settings/holidays/:num'] = "settings/holidays";

//USER BRANCH
$route['settings/user/branch'] = "settings/branch";
$route['settings/user/branch/:num'] = "settings/branch";

//CASH
$route['cash'] = "cash/overview"; 
$route['cash/branches'] = "cash/branch";
$route['cash/branches/bankAccount/:num/:num'] = "cash/branch/bankAccount/$1/$1";
$route['cash/branches/:num'] = "cash/branch";
$route['cash/branches/details'] = "cash/branch/details";
$route['cash/branches/details/:num'] = "cash/branch/details";
$route['cash/branches/transactions/:num'] = "cash/branch/details";

$route['cash/banks'] = "cash/banks";
$route['cash/banks/:num'] = "cash/banks";

$route['cash/daily'] = "cash/daily";
$route['cash/daily/:num'] = "cash/daily";
$route['cash/daily/createtransaction'] = "cash/daily/createtransaction";
$route['cash/daily/transaction/:num'] = "cash/daily/transaction";
$route['cash/daily/transaction/:num/bank/:num'] = "cash/daily/transaction";
$route['cash/daily/lock/:num'] = "cash/daily/lock";
$route['cash/collections'] = "cash/collections";
$route['cash/collections/:num'] = "cash/collections";
$route['cash/daily/update/collection/:num'] = "cash/daily/update";
$route['cash/daily/update/disbursement/:num'] = "cash/daily/update";
$route['cash/daily/update/adjustment/:num'] = "cash/daily/update";
$route['cash/consolidated'] = "cash/consolidated";
$route['cash/daily/update/recap/:num'] = "cash/daily/update";
$route['cash/daily/br/:num'] = "cash/daily";
$route['cash/page'] = "cash/page";
$route['cash/addtransaction'] = "cash/daily/addtransaction";

//LOAD PAGE
$route['load/page/'] = "load/page";

//CLIENT MGMT
$route['client/:num'] = "client/overview";
$route['client/addnew'] = "client/addnew";
$route['client/getClients'] = "client/overview/getClients";
$route['client/page'] = "client/page";
$route['client/page/profile'] = "client/page";
$route['client/profile/:num'] = "client/profile";
$route['client/profile/updateinfo'] = "client/profile/updateinfo";
$route['client/profile/:num/loan/:num'] = "client/profile/loan";
$route['client/profile/:num/loan'] = "client/profile/loan";
$route['client/loan/:num'] = "client/loan";
$route['client/profile/:num/pension/:num'] = "client/profile/pension";
$route['client/profile/:num/addpension'] = "client/profile/addpension";
$route['search-client'] = "client/search";
$route['search-result'] = "client/search/result";


//PRODUCT
$route['product/details/:num'] = "product/overview/details";
$route['product/interest'] = "product/interest";
//LOANS
$route['loans/new'] = "loans/newapplication";
$route['loans/detail/(:num)'] = "loans/overview/details";
$route['loans/action/(:any)'] = "loans/overview/action";
$route['loans/form/(:any)/(:num)'] = "loans/overview/form";
$route['loans/popup/(:any)'] = "loans/info/popup";
$route['loans/status/(:any)'] = "loans/overview/status";
$route['loans/new/cancel'] = "loans/overview/newapplication";
$route['loans/fees'] = "loans/overview/feedetails";
$route['loancount/(:any)'] = "loans/overview/loancount/$1";
//loan Forms
$route['loans/setup/update/requirements'] = "loans/setup/updaterequirements";

$route['branch_accounts'] = "reports/branch_accounts";
$route['update_branch_accounts/:num'] = "reports/branch_accounts/update";
$route['branch_account_post'] = "reports/branch_accounts/update_post";
$route['loans_granted'] = "reports/Loansreport/granted";
$route['monthlyaccounts'] = "reports/accounts/sched";

$route['delete_pension/:num'] = "collateral/pensioninfo/delete";

//CIC
$route['cic'] = "reports/cic_report";
$route['reports/crb/:num'] = "reports/accounts/crb/$1";
$route['cicloan'] = "reports/cic_report/loans";
//MY Account
$route['account'] = "account/account";

$route['testing'] = "testing";



// routes for pensioninfo. Jan 11-2017
$route['manage-pensioninfo']="collateral/PensioninfoController/ManagePensioninfo";
$route['change-status-pensioninfo/(:num)']="collateral/PensioninfoController/changeStatusPensioninfo/$1";
$route['edit-pensioninfo/(:num)']="collateral/PensioninfoController/editPensioninfo/$1";
$route['edit-pensioninfo-post']="collateral/PensioninfoController/editPensioninfoPost";
$route['delete-pensioninfo/(:num)']="collateral/PensioninfoController/deletePensioninfo/$1";
$route['add-pensioninfo']="collateral/PensioninfoController/addPensioninfo";
$route['add-pensioninfo-post']="collateral/PensioninfoController/addPensioninfoPost";
$route['view-pensioninfo/(:num)']="collateral/PensioninfoController/viewPensioninfo/$1";
$route['view-clientLoans/(:num)']="collateral/PensioninfoController/viewClientLoans/$1";
// end of pensioninfo routes


// routes for collaterals.
$route['manage-collaterals']="collateral/CollateralsController/ManageCollaterals";
$route['change-status-collaterals/(:num)']="collateral/CollateralsController/changeStatusCollaterals/$1";
$route['edit-collaterals/(:num)']="collateral/CollateralsController/editCollaterals/$1";
$route['edit-collaterals-post']="collateral/CollateralsController/editCollateralsPost";
$route['delete-collaterals/(:num)']="collateral/CollateralsController/deleteCollaterals/$1";
$route['add-collaterals']="collateral/CollateralsController/addCollaterals";
$route['add-collaterals-post']="collateral/CollateralsController/addCollateralsPost";
$route['view-collaterals/(:num)']="collateral/CollateralsController/viewCollaterals/$1";
// end of collaterals routes


//MAY 4 2017
// routes for collateral_file_desc.
$route['manage-collateral_file_desc']="collateral/Collateral_file_descController/ManageCollateral_file_desc";
$route['change-status-collateral_file_desc/(:num)']="collateral/Collateral_file_descController/changeStatusCollateral_file_desc/$1";
$route['edit-collateral_file_desc/(:num)']="collateral/Collateral_file_descController/editCollateral_file_desc/$1";
$route['edit-collateral_file_desc-post']="collateral/Collateral_file_descController/editCollateral_file_descPost";
$route['delete-collateral_file_desc/(:num)']="collateral/Collateral_file_descController/deleteCollateral_file_desc/$1";
$route['add-collateral_file_desc']="collateral/Collateral_file_descController/addCollateral_file_desc";
$route['add-collateral_file_desc-post']="collateral/Collateral_file_descController/addCollateral_file_descPost";
$route['view-collateral_file_desc/(:num)']="collateral/Collateral_file_descController/viewCollateral_file_desc/$1";
// end of collateral_file_desc routes


// routes for vendors.
$route['manage-vendors']="settings/VendorsController/ManageVendors";
$route['change-status-vendors/(:num)']="settings/VendorsController/changeStatusVendors/$1";
$route['edit-vendors/(:num)']="settings/VendorsController/editVendors/$1";
$route['edit-vendors-post']="settings/VendorsController/editVendorsPost";
$route['delete-vendors/(:num)']="settings/VendorsController/deleteVendors/$1";
$route['add-vendors']="settings/VendorsController/addVendors";
$route['add-vendors-post']="settings/VendorsController/addVendorsPost";
$route['view-vendors/(:num)']="settings/VendorsController/viewVendors/$1";
// end of vendors routes


// routes for collection.
$route['manage-collection/(:num)']="cash/CollectionController/ManageCollection/$1";
$route['change-status-collection/(:num)']="cash/CollectionController/changeStatusCollection/$1";
$route['edit-collection/(:num)']="cash/CollectionController/editCollection/$1";
$route['edit-collection-post']="cash/CollectionController/editCollectionPost";
$route['delete-collection/(:num)']="cash/CollectionController/deleteCollection/$1";
$route['add-collection']="cash/CollectionController/addCollection";
$route['add-collection-post']="cash/CollectionController/addCollectionPost";
$route['view-collection/(:num)']="cash/CollectionController/viewCollection/$1";
$route['client-loans'] = "cash/CollectionController/loans";
$route['client-collaterals'] = "cash/CollectionController/collateral";
$route['plcollect/(:num)'] = "cash/CollectionController/pl/$1";
$route['loan-for-collection'] = "cash/CollectionController/LoanforCollection";
$route['loandue/(:num)'] = "cash/CollectionController/LoanDue/$1";
$route['chooseloan'] = "cash/CollectionController/chosenloan";
$route['other_ar'] = "cash/CollectionController/other_ar";
$route['post_or'] = "cash/CollectionController/post_or";
$route['collect/(:any)'] = "cash/CollectionController/collect/$1";
$route['forpayment'] = "cash/CollectionController/post_temp";
$route['collect_temp/(:any)'] = "cash/CollectionController/details_temp/$1";
$route['printstatement/(:any)'] = "cash/CollectionController/print_statement/$1";
// end of collection routes

//routes for disbursement/
$route['manage-disbursement/(:num)']="cash/Disbursements/manageDisbursement/$1";
$route['edit-cv/(:num)']="cash/Disbursements/edit/$1";

//routes for disbursement/
$route['manage-jv/(:num)']="cash/Adjustments/manageJv/$1";
$route['check-pn/(:num)/(:num)'] = "cash/CollectionController/LoanExist/$1/$2";

//routes print
$route['print/loansgranted'] = 'reports/Loansreport/printgranted';
$route['print/dcrr'] = 'loans/Overview/print_dcrr';
$route['uid'] = 'reports/overview/uid';
// routes for CV
$route['prepare-cv/(:num)'] = "cash/Disbursements/prepcvforloan/$1";

//routes for Accounting
$route['delete_group/(:num)'] = "reports/Accounts/delete_group/$1";
$route['delete_account/(:num)'] = "reports/Accounts/delete_account/$1";
$route['branch_access/(:num)'] = "reports/Accounts/update_access/$1";

//routes for transactions/
$route['manage-transaction'] = "TransactionController";
$route['view-transaction/(:num)'] = "TransactionController/view/$1";
$route['create-transaction'] = "TransactionController/create_transaction";
$route['view-gl/(:num)'] = "TransactionController/view_gl/$1";
$route['verify-jv/(:num)'] = "TransactionController/verify_jv/$1";
$route['view-cv/(:num)'] = "TransactionController/view_cv/$1";
$route['edit-cv/(:num)'] = "TransactionController/edit_cv/$1";
$route['editpost-cv/(:num)'] = "TransactionController/editcv_post/$1";
$route['print_report/(:num)'] = "TransactionController/print_report/$1";
$route['reverse/(:num)'] = "TransactionController/reverse/$1";
$route['computedaily/(:num)'] = "TransactionController/computedaily/$1";


$route['view-ledger/(:num)'] = "reports/Accounts/view_ledger/$1";
$route['manage-ledger/(:num)'] = "reports/Accounts/view_ledger/$1";
$route['trans-ledger/(:num)/(:num)'] = "reports/Accounts/trans_ledger/$1/$2";


//SETTINGS REPORTS
$route['manage-reports'] = "ReportsController/manageReports";
$route['report-settings'] = "ReportsController";
$route['add-reportpost'] = "ReportsController/add_reportPost";
$route['change-status-report/(:num)'] = "ReportsController/changeStatus/$1";
$route['edit-report/(:num)'] = "ReportsController/editReport/$1";
$route['view-report/(:num)'] = "ReportsController/viewReport/$1";
$route['edit-reportPost'] = "ReportsController/editReportPost/$1";
$route['add-accountpost/(:num)'] = "ReportsController/addAcct/$1";
$route['manage-report-acct/(:num)'] = "ReportsController/ManageAccount/$1";

$route['reports/trialbalance/(:num)'] = "ReportsController/trialbalance/$1";
$route['reports/loanschedule'] = "ReportsController/loanschedule";
$route['print/loanschedule'] = "ReportsController/printloansched";


/* End of file routes.php */
/* Location: ./application/config/routes.php */