<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EconomicPlan;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\PestController;
use App\Http\Controllers\SwotController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\PlansController;
use App\Http\Controllers\VideoController;
use App\Http\Controllers\DeleteController;
use App\Http\Controllers\NoticeController;
use App\Http\Controllers\PaypalController;
use App\Http\Controllers\PestelController;
use App\Http\Controllers\PorterController;
use App\Http\Controllers\ActionsController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\DownloadController;
use App\Http\Controllers\FrontendController;
use App\Http\Controllers\MckinseyController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MarketingController;
use App\Http\Controllers\SubscribeController;
use App\Http\Controllers\SuperAdminController;
use App\Http\Controllers\EconomicPlanController;
use App\Http\Controllers\FinancialEvaluationController;

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

Route::get('/', [FrontendController::class,'home']);

Route::prefix("super-admin")->group(function () {
    Route::get("/", [AuthController::class, "superAdminLogin"])->name(
        "super-admin-login"
    );
    Route::post("/auth", [AuthController::class, "superAdminAuthenticate"]); 

}); 
Route::resource('video',VideoController::class); 
Route::get('video/{id}/destroy',[VideoController::class,'destroy'])->name('video.destroy');
Route::get("/super-admin/dashboard", [
    SuperAdminController::class,
    "dashboard",
]);

Route::get("/super-admin/update-schema", [
    SuperAdminController::class,
    "updateSchema",
]);

Route::get("/subscription-plans", [SuperAdminController::class, "saasPlans"]);
Route::get("/subscription-plan", [
    SuperAdminController::class,
    "createSaasPlan",
]);

Route::get("/payment-gateways", [
    SuperAdminController::class,
    "paymentGateways",
]);
Route::get("/configure-payment-gateway", [
    SuperAdminController::class,
    "configurePaymentGateway",
]);

Route::get('/validate-paypal-subscription', [DashboardController::class, 'validatePaypalSubscription']);
Route::post('/paypal-ipn', [SuperAdminController::class, "paypalIpn"]);

Route::post("/save-subscription-plan", [
    SuperAdminController::class,
    "subscriptionPlanPost",
]);
Route::get("/edit-workspace", [
    SuperAdminController::class,
    "editWorkspace",
]);
Route::post("/save-workspace", [
    SuperAdminController::class,
    "saveWorkspace",
]);
Route::post("/configure-gateway", [
    SuperAdminController::class,
    "configurePaymentGatewayPost",
]);
Route::get("/user-profile", [SuperAdminController::class, "userProfile"]);
Route::get("/super-admin-profile", [
    SuperAdminController::class,
    "adminProfile",
]);
Route::get("/super-admin-setting", [
    SuperAdminController::class,
    "adminSetting",
]);
Route::get("/workspaces", [SuperAdminController::class, "workspaces"]);
Route::get("/add-user", [SuperAdminController::class, "addUser"]);
Route::get("/delete-workspace/{id}", [
    SuperAdminController::class,
    "deleteWorkspace",
]);


Route::get("/add-investor", [ContactController::class, "addInvestor"]);
Route::get("/investors", [ContactController::class, "investorList"])->name('investors.index');
Route::get("/view-investor", [ContactController::class, "investorView"]);
Route::post("/save-investor", [ContactController::class, "investorPost"]);

Route::get("/fixedInvested/add", [\App\Http\Controllers\FixedInvestedCapitalController::class, "create"])->name('fixedInvested.create');
Route::get("/fixedInvested", [\App\Http\Controllers\FixedInvestedCapitalController::class, "index"])->name('fixedInvested.index');
Route::get("/fixedInvested/view", [\App\Http\Controllers\FixedInvestedCapitalController::class, "show"])->name('fixedInvested.show');
Route::get("/fixedInvested/delete", [\App\Http\Controllers\FixedInvestedCapitalController::class, "destroy"])->name('fixedInvested.destroy');
Route::get("/fixedInvested/destroyCost", [\App\Http\Controllers\FixedInvestedCapitalController::class, "destroyCost"])->name('fixedInvested.destroyCost');
Route::post("/fixedInvested/save", [\App\Http\Controllers\FixedInvestedCapitalController::class, "store"])->name('fixedInvested.store');
Route::get("/fixedInvested/show", [\App\Http\Controllers\FixedInvestedCapitalController::class, "show"])->name('fixedInvested.show');

Route::get("/workingInvested/add", [\App\Http\Controllers\WorkingInvestedCapitalController::class, "create"])->name('workingInvested.create');
Route::get("/workingInvested", [\App\Http\Controllers\WorkingInvestedCapitalController::class, "index"])->name('workingInvested.index');
Route::get("/workingInvested/view", [\App\Http\Controllers\WorkingInvestedCapitalController::class, "show"])->name('workingInvested.show');
Route::get("/workingInvested/delete", [\App\Http\Controllers\WorkingInvestedCapitalController::class, "destroy"])->name('workingInvested.destroy');
Route::get("/workingInvested/destroyCost", [\App\Http\Controllers\WorkingInvestedCapitalController::class, "destroyCost"])->name('workingInvested.destroyCost');
Route::post("/workingInvested/save", [\App\Http\Controllers\WorkingInvestedCapitalController::class, "store"])->name('workingInvested.store');
Route::get("/workingInvested/show", [\App\Http\Controllers\WorkingInvestedCapitalController::class, "show"])->name('workingInvested.show');
Route::get("/investors/show", [\App\Http\Controllers\ContactController::class, "show"])->name('investors.show');

Route::get("/investingCapitalPlanning", [\App\Http\Controllers\InvestingCapitalPlanning::class, "index"])->name('investingCapitalPlanning.index');


Route::get("/email-setting", [SuperAdminController::class, "emailSetting"]);
Route::post("/save-email-setting", [SuperAdminController::class, "saveEmailSetting"]);

Route::get("/delete-user/{id}", [SuperAdminController::class, "deleteUser"]);
Route::get("/users", [SuperAdminController::class, "users"]);
Route::get("/emails", [SuperAdminController::class, "newsletterEmail"]);
Route::get("/activate", [SuperAdminController::class, "activateLicense"]);
Route::post("/activate-license", [SettingController::class, "activateLicensePost"]);
Route::get("/subscribe", [SubscribeController::class, "subscribe"]);
Route::get("/cancel-subscription", [SubscribeController::class, "cancelSubscription"]);
Route::post("/payment-stripe", [SubscribeController::class, "paymentStripe"]);
//Route::get("/", [AuthController::class, "login"])->name("login");
Route::get("/login", [AuthController::class, "login"])->name("login");
Route::get("/signup", [AuthController::class, "signup"]);

Route::get("/forgot-password", [AuthController::class, "forgotPassword"]);
Route::get("/password-reset", [AuthController::class, "passwordReset"]);
Route::get("/calendar/{action?}", [PlansController::class, "calendarAction"]);
Route::get("/notes", [ActionsController::class, "notes"]);
Route::get("/swot-list", [SwotController::class, "swotList"]);
Route::get("/write-swot", [SwotController::class, "writeSwot"]);
Route::get("/view-swot", [SwotController::class, "viewSwot"]);

Route::get("/write-pest", [PestController::class, "writePest"]);
Route::get("/pest-list", [PestController::class, "pestList"]);
Route::get("/view-pest", [PestController::class, "viewPest"]);
Route::get("/write-pestle", [PestelController::class, "writePestel"]);
Route::get("/pestle-list", [PestelController::class, "pestelList"]);
Route::get("/view-pestle", [PestelController::class, "viewPestel"]);

Route::get("/porter-models", [PorterController::class, "porterList"]);
Route::get("/new-porter", [PorterController::class, "newPorter"]);
Route::get("/view-porter", [PorterController::class, "viewPorter"]);
Route::post("/save-porter-model", [PorterController::class, "porterPost"]);


Route::get("/mckinsey-models", [MckinseyController::class, "mckinseyModels"]);
Route::get("/new-mckinsey-model", [MckinseyController::class, "NewMckinseyModels"]);
Route::get("/view-mckinsey-model", [MckinseyController::class, "ViewMckinseyModel"]);
Route::post("/save-mckinsey-model", [MckinseyController::class, "MckinseyModelPost"]);


Route::get("/add-task", [ActionsController::class, "addTask"]);
Route::get("/add-note", [ActionsController::class, "addNote"]);
Route::get("/view-note", [ActionsController::class, "viewNote"]);
Route::get("/projects", [ProjectController::class, "projects"]);
Route::get("/create-project", [ProjectController::class, "createProject"]);
Route::get("/logout", [AuthController::class, "logout"]);
Route::get("/view-project", [ProjectController::class, "projectView"]);

Route::get("/view-project-discussion", [
    ProjectController::class,
    "projectViewDiscussion",
]);
Route::get("/view-project-tasks", [
    ProjectController::class,
    "projectViewTasks",
]);
Route::get("/view-project-files", [
    ProjectController::class,
    "projectViewFiles",
]);
Route::get("/user-edit/{id}", [ProfileController::class, "userEdit"]);
Route::get("/download/{id}", [DownloadController::class, "download"]);
Route::get("/dashboard", [DashboardController::class, "dashboard"]);
Route::get("/new-user", [ProfileController::class, "newUser"]);
Route::get("/documents", [DocumentController::class, "documents"]);
Route::get("/profile", [ProfileController::class, "profile"]);
Route::get("/staff", [ProfileController::class, "staff"]);
Route::get("/settings", [SettingController::class, "settings"]);
Route::get("/billing", [SettingController::class, "billing"]);
Route::get("/delete/{action}/{id}", [DeleteController::class, "delete"]);

Route::get('subscriptions', [\App\Http\Controllers\SubscriptionController::class, 'index'])->name('subscription.index');
Route::get('subscriptions-all', [\App\Http\Controllers\SubscriptionController::class, 'showAll'] )->name('all_subscriptions');

Route::post("/save-reset-password", [
    AuthController::class,
    "resetPasswordPost",
]);
Route::post("/post-new-password", [AuthController::class, "newPasswordPost"]);
Route::post("/user-change-password", [
    ProfileController::class,
    "userChangePasswordPost",
]);
Route::post("/login", [AuthController::class, "loginPost"]);
Route::post("/super-admin/login", [
    AuthController::class,
    "superAdminLoginPost",
]);
Route::post("/signup", [AuthController::class, "signupPost"]);

Route::post("/save-note", [ActionsController::class, "notePost"]);
Route::post("/save-swot", [SwotController::class, "swotPost"]);
Route::post("/save-pest", [PestController::class, "pestPost"]);
Route::post("/save-pestel", [PestelController::class, "pestelPost"]);

Route::post("/save-project", [ProjectController::class, "projectPost"]);
Route::post("/save-project-message", [
    ProjectController::class,
    "projectMessagePost",
]);

Route::post("/document", [DocumentController::class, "documentPost"]);
Route::post("/settings", [SettingController::class, "settingsPost"]);
Route::post("/profile", [ProfileController::class, "profilePost"]);
Route::post("/save-event", [PlansController::class, "eventPost"]);
Route::post("/user-post", [ProfileController::class, "userPost"]);
Route::get("/business-plans", [PlansController::class, "businessPlans"]);
Route::get("/write-business-plan", [
    PlansController::class,
    "writeBusinessPlans",
]);

Route::get("/view-business-plan", [PlansController::class, "viewBusinessPlan"]);
Route::get("/view-business-model", [
    PlansController::class,
    "viewBusinessModel",
]);

Route::get("/business-models", [PlansController::class, "businessModels"]);
Route::get("/design-business-model", [PlansController::class, "businessModel"]);

Route::get("/marketing-plans", [MarketingController::class, "marketingPlans"]);
Route::get("/write-marketing-plan", [MarketingController::class, "writeMarketingPlan"]);
Route::get("/view-marketing-plan", [MarketingController::class, "ViewMarketingPlan"]);

Route::post("/save-marketing-plan", [MarketingController::class, "marketingPlanPost"]);

Route::get("/startup-canvases", [PlansController::class, "startupCanvases"]);
Route::get("/design-startup-canvas", [PlansController::class, "startupCanvas"]);
Route::get("/view-startup-canvas", [PlansController::class, "viewStartupCanvas"]);
Route::post("/save-startup-canvas", [PlansController::class, "startupCanvasPost"]);


Route::get("/brainstorming", [PlansController::class, "brainStorm"]);
Route::get("/brainstorming-list", [PlansController::class, "brainStormList"]);
Route::post("/save-canvas", [PlansController::class, "saveCanvas"]);
Route::post("/business-plan-post", [
    PlansController::class,
    "businessPlanPost",
]);

Route::post("/business-model-post", [
    PlansController::class,
    "businessModelPost",
]);

Route::get("/", [FrontendController::class, "home"]);

Route::get("/pricing", [FrontendController::class, "pricing"]);
Route::get('/subscribe', [FrontendController::class,'subscribe'])->middleware('auth');

Route::get('/privacy', [FrontendController::class,'privacy']);
Route::get("/termsandconditions", [FrontendController::class, "termsCondition"]);
Route::get("/cookie-policy", [FrontendController::class, "cookiePolicy"]);
Route::get("/contact", [FrontendController::class, "contact"]);


Route::post("/save-email", [FrontendController::class, "emailSave"]);


Route::get('/landingpage', [SuperAdminController::class,'landingPage']);
Route::get('/pricingpage', [SuperAdminController::class,'pricingPage']);
Route::get('/termspage', [SuperAdminController::class,'termsPage']);
Route::get('/cookiepage', [SuperAdminController::class,'cookiePage']);
Route::get('/privacypage', [SuperAdminController::class,'privacyPage']);
Route::get('/contactpage', [SuperAdminController::class,'contactPage']);
Route::get('/footer', [SuperAdminController::class,'footer']);


Route::get("/blog", [FrontendController::class, "blogs"]);
Route::get("/blog/{slug}", [FrontendController::class, "viewArticle"]);

Route::get("/write-blog", [BlogController::class, "writeBlog"]);
Route::get("/blogs", [BlogController::class, "blogs"]);
Route::get("/view-blog", [BlogController::class, "viewBlog"]);
Route::post("/save-blog", [BlogController::class, "blogPost"]);

Route::get("/write-notice", [NoticeController::class, "writeNotice"]);
Route::get("/notice-list", [NoticeController::class, "noticeList"]);

Route::post("/save-notice", [NoticeController::class, "noticePost"]);

Route::post('/save-cookie-section', [SuperAdminController::class,'saveCookie']);

Route::post('/save-footer-section', [SuperAdminController::class,'footerSection']);
Route::post('/save-calltoaction-section', [SuperAdminController::class,'calltoactionSection']);

Route::post('/save-hero-section', [SuperAdminController::class,'heroSection']);
Route::post('/save-feature1-section', [SuperAdminController::class,'feature1Section']);
Route::post('/save-feature2-section', [SuperAdminController::class,'feature2Section']);
Route::post('/save-partner-section', [SuperAdminController::class,'partnerSection']);

Route::post('/save-story1-section', [SuperAdminController::class,'story1Section']);
Route::post('/save-story2-section', [SuperAdminController::class,'story2Section']);

Route::post('/save-newsletter-section', [SuperAdminController::class,'newsletterSection']);
Route::post('/save-number-section', [SuperAdminController::class,'numberSection']);
Route::post('/save-privacy-section', [SuperAdminController::class,'savePrivacy']);
Route::post('/save-terms-section', [SuperAdminController::class,'saveTerms']);


Route::post("/settings/{action}", [SettingController::class, "settingsStore"]);

// openai routes for super admin crud
Route::post('/openai-settings' , [SettingController::class, 'openAiSaveSettings'])->name('openai.save');


Route::prefix("admin")
    ->name("admin.")
    ->group(function () {
        Route::get("/tasks/{action}", [
            TaskController::class,
            "tasksAction",
        ])->name("tasks");
        Route::post("/tasks/{action}", [
            TaskController::class,
            "tasksSave",
        ])->name("tasks.save");
        Route::post("/tasksGoals/save", [
            TaskController::class,
            "tasksGoalSave",
        ])->name("tasksGoals.save");
        Route::get("/tasks-show", [
            TaskController::class,
            "show",
        ])->name("tasks.show");

        Route::get("/task-list", [TaskController::class, "taskList"]);

        Route::get("/delete/{action}/{id}", [
            DeleteController::class,
            "delete",
        ])->name("delete");
    });

    //economic plan routes 
    Route::get('/economic-plan' , [EconomicPlanController::class , 'index'])->name('economiccPlan.index');
    Route::post('/economic-plan-save' , [EconomicPlanController::class , 'create'])->name('economiccPlan.create');

    //finanical evaluation routes
    Route::get('/financial-evaluation' , [FinancialEvaluationController::class , 'index'])->name('financial_evaluation.index');
    Route::post('/financial-evaluation' , [FinancialEvaluationController::class , 'create'])->name('financial_evaluation.create');




Route::get("/kanban", [TaskController::class, "kanban"]);
Route::get("/gantt", [TaskController::class, "gantt"]);
Route::post("/todo/set-status", [TaskController::class, "setStatus"]);

Route::get("/update", function (){
    \App\Supports\UpdateSupport::updateSchema();
});
Route::get("/project-revenue-planning", [\App\Http\Controllers\ProjectRevenuePlanningController::class, "index"])->name('project-revenue-planning.index');
Route::get("/revenueForecast", [\App\Http\Controllers\ProjectRevenuePlanningController::class, "revenueForecast"])->name('revenueForecast');
Route::post("/addNewRevenueSource", [\App\Http\Controllers\ProjectRevenuePlanningController::class, "addNewRevenueSource"])->name('addNewRevenueSource');
Route::get("/project-revenue-planning-data", [\App\Http\Controllers\ProjectRevenuePlanningController::class, "getProjectRevenuePlanningData"])->name('project-revenue-planning.getData');
Route::get("/project-revenue-planning/create", [\App\Http\Controllers\ProjectRevenuePlanningController::class, "create"])->name('project-revenue-planning.create');
Route::post("/project-revenue-planning", [\App\Http\Controllers\ProjectRevenuePlanningController::class, "store"])->name('project-revenue-planning.store');
Route::post("/project-revenue/saveYearlyIncreasingPercentage", [\App\Http\Controllers\ProjectRevenuePlanningController::class, "saveYearlyIncreasingPercentage"])->name('project-revenue-planning.saveYearlyIncreasingPercentage');
Route::get("/project-revenue-planning/{projectRevenuePlanning}/edit", [\App\Http\Controllers\ProjectRevenuePlanningController::class, "edit"])->name('project-revenue-planning.edit');
Route::get("/deleteRevenueSource/{projectRevenueSource}", [\App\Http\Controllers\ProjectRevenuePlanningController::class, "deleteRevenueSource"])->name('project-revenue-planning.deleteRevenueSource');
Route::put("/project-revenue-planning/{projectRevenuePlanning}", [\App\Http\Controllers\ProjectRevenuePlanningController::class, "update"])->name('project-revenue-planning.update');
Route::delete("/project-revenue-planning/{projectRevenuePlanning}/destroy", [\App\Http\Controllers\ProjectRevenuePlanningController::class, "destroy"])->name('project-revenue-planning.destroy');

Route::get("/planning_cost_assumptions", [\App\Http\Controllers\PlanningCostAssumptionsController::class, "index"]);
Route::post("/planning_cost_assumptions", [\App\Http\Controllers\PlanningCostAssumptionsController::class, "store"])->name('planning_cost_assumptions.store');

Route::get("/planning_financial_assumptions", [\App\Http\Controllers\PlanningFinancialAssumptionsController::class, "index"]);
Route::post("/planning_financial_assumptions", [\App\Http\Controllers\PlanningFinancialAssumptionsController::class, "store"])->name('planning_financial_assumptions.store');

Route::get("/planning_revenue_operating_assumptions", [\App\Http\Controllers\PlanningRevenueOperatingAssumptionController::class, "index"]);
Route::post("/planning_revenue_operating_assumptions", [\App\Http\Controllers\PlanningRevenueOperatingAssumptionController::class, "store"])->name('planning_revenue_operating_assumptions.store');
Route::get("/getPlanningRevenueOperatingAssumptions", [\App\Http\Controllers\FinncialReportController::class, "getPlanningRevenueOperatingAssumptions"])->name('planning_revenue_operating_assumptions.get');
Route::get("/getIncomeList", [\App\Http\Controllers\FinncialReportController::class, "getIncomeList"])->name('incomeList.get');
Route::get("/getStatementOfCashFlows", [\App\Http\Controllers\FinncialReportController::class, "getStatementOfCashFlows"])->name('statementOfCashFlows.get');
Route::get("/capital_investment_model", [\App\Http\Controllers\FinncialReportController::class, "capital_investment_model"])->name('capital_investment_model.get');
Route::get("/textReport", [\App\Http\Controllers\FinncialReportController::class, "textReport"])->name('textReport.get'); 


Route::get('/myPlan', [\App\Http\Controllers\MyPlanController::class, 'index'])->name('myPlan.index');



Route::get('paypal/{package}', [PayPalController::class, 'createTransaction'])->name('paypal');
Route::get('paypal-process/{package}', [PayPalController::class, 'processTransaction'])->name('paypalProcessTransaction');
Route::get('paypal-success', [PayPalController::class, 'successTransaction'])->name('paypalSuccessTransaction');
Route::get('paypal-cancel', [PayPalController::class, 'cancelTransaction'])->name('paypalCancelTransaction');

Route::middleware('auth')->prefix('packages')->group(function (){
    Route::get('/{package}', [\App\Http\Controllers\PackageController::class,'show'])->name('packages.details');
});

Route::get('/user/package', [\App\Http\Controllers\PackageController::class, 'showUserPackage'])->name('user.package');
Route::get('/user/notifications', [\App\Http\Controllers\UserNotificationController::class,'index']);
Route::post('/user/notifications/{notification}', [\App\Http\Controllers\UserNotificationController::class,'readNotification'])->name('user.read_notification');
Route::get('/admin/notifications', [\App\Http\Controllers\AdminNotificationController::class,'index']);
Route::post('/admin/notifications/{notification}', [\App\Http\Controllers\AdminNotificationController::class,'readNotification'])->name('admin.read_notification');

Route::post('/admin/active/subscription/{subscription}', [\App\Http\Controllers\SubscriptionController::class,'activeSubscription'])->name('admin.active_subscription');
Route::get('/admin/subscriptions/{subscription}', [\App\Http\Controllers\SubscriptionController::class,'show'])->name('admin.subscriptions.details');

Route::post('/transfer-bank', [\App\Http\Controllers\TransferBankController::class, 'store'])->name('user.transfer_bank');

Route::prefix('user/chat')->middleware('auth')->group(function (){
    Route::get('/', [\App\Http\Controllers\UserChatController::class,'index'])->name('user.chat.index');
    Route::post('/', [\App\Http\Controllers\UserChatController::class, 'send'])->name('user.chat.send');
});

Route::prefix('admin/chat')->middleware('auth')->group(function (){
    Route::get('/', [\App\Http\Controllers\AdminChatController::class, 'index'])->name('admin.chat.index');
    Route::post('/', [\App\Http\Controllers\AdminChatController::class,'send'])->name('admin.chat.send');
    Route::get('/{chat}', [\App\Http\Controllers\AdminChatController::class,'getChat'])->name('admin.get.chat');
    Route::post('/disable/{chat}', [\App\Http\Controllers\AdminChatController::class, 'disableChat'])->name('admin.chat.disable');
});

Route::post('set-offer_price_yearly', function (\Illuminate\Http\Request $request){
    $value = $request->input('value');
    session()->put('offer_price_yearly', $value);
    return response()->json(['success' => true]);
})->name('offer_price_yearly');

Route::get('click-pay', [\App\Http\Controllers\ClickPayController::class,'pay'])->name('click_pay');
Route::post('click-pay-success', [\App\Http\Controllers\ClickPayController::class, 'clickPaySuccess'])->name('click_pay.success');
Route::post('click-pay-fail', [\App\Http\Controllers\ClickPayController::class, 'clickPayFail'])->name('click_pay.fail');

Route::get('user/videos', [\App\Http\Controllers\UserVideoController::class,'index'])->name('user_video');
Route::get('user/videos/{video}', [\App\Http\Controllers\UserVideoController::class,'show'])->name('user.video.show');

Route::get('/pay-online/{package}', [\App\Http\Controllers\PayController::class, 'payOnline'])->name('user.pay_online');
Route::get('/pay-bank/{package}', [\App\Http\Controllers\PayController::class, 'payWithBank'])->name('user.pay_bank');

Route::view('payment-successfully', 'package.payment_successfully')->name('payment_successfully');
