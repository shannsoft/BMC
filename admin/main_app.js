var app = angular.module("bmc_app", ['ui.router', 'ui.bootstrap', 'ngResource', 'ngStorage', 'ngAnimate','datePicker']);
app.config(function($stateProvider, $urlRouterProvider) {
    $urlRouterProvider.otherwise('/login');
    $stateProvider
    // HOME STATES AND NESTED VIEWS ========================================
        .state('dashboard', {
            templateUrl: 'pages/dashboard.html',
            url: '/dashboard',
            controller:"Main_Controller",
            onEnter: function($localStorage, $state) {
               if (!$localStorage.user) {
                   $state.go('login');
               }
             }
        })
        .state('login', {
            templateUrl: 'pages/login.html',
            url: '/login',
            controller:"Main_Controller",
            onEnter: function($localStorage, $state) {
               if ($localStorage.user) {
                   $state.go('dashboard');
               }
             }
        })
        .state('newemployee',{
             templateUrl:'pages/newemployee.html',
             url:'/newemployee',
             controller:"Main_Controller",
             onEnter: function($localStorage, $state) {
               if (!$localStorage.user) {
                   $state.go('login');
               }
             }
        })
        .state('employeeList',{
             templateUrl:'pages/employee_list.html',
             url:'/employeeList',
             controller:"Emp_Controller",
             onEnter: function($localStorage, $state) {
               if (!$localStorage.user) {
                   $state.go('login');
               }
             }
        })
        .state('editEmployee',{
             templateUrl:'pages/editemployee.html',
             url:'/editemployee/:id',
             controller:"Emp_Controller"
        })
        .state('userProfile',{
             templateUrl:'pages/users/UserProfile.html',
             url:'/UserProfile',
             controller:"User_controller"
        })
        .state('retiredemployeelist',{
             templateUrl:"pages/retiredemployeelist.html",
             url:'/retiredemployeelist',
             controller:"Emp_Controller"
        })
});
app.constant('CONFIG', {
  'HTTP_HOST': '../server/api.php' //client staging
})
app.factory('Util', ['$rootScope',  '$timeout' , function( $rootScope, $timeout){
    var Util = {};
    $rootScope.alerts =[];
    Util.alertMessage = function(msgType, message){
        console.log(1212121);
        var alert = { type:msgType , msg: message };
        $rootScope.alerts.push( alert );
         $timeout(function(){
            $rootScope.alerts.splice($rootScope.alerts.indexOf(alert), 1);
         }, 5000);
    };
    return Util;
  }]);
