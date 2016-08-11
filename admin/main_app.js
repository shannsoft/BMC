var app = angular.module("bmc_app", ['ui.router', 'ui.bootstrap', 'ngResource', 'ngStorage', 'ngAnimate','datePicker','ngCookies']);
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
        .state('forgot-password', {
            templateUrl: 'pages/forgotPassword.html',
            url: '/forgot-password',
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
        .state('sectionDataList',{
             templateUrl:'pages/sectionDataList.html',
             url:'/sectionDataList/:section',
             controller:"Emp_Controller",
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
        .state('employeeList-bystatus',{
             templateUrl:'pages/employeeStatus.html',
             url:'/employeeList-bystatus/:status',
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
             controller:"Emp_Controller",
             onEnter: function($localStorage, $state) {
               if (!$localStorage.user) {
                   $state.go('login');
               }
             }
        })
        .state('userProfile',{
             templateUrl:'pages/users/UserProfile.html',
             url:'/UserProfile',
             controller:"User_controller",
             onEnter: function($localStorage, $state) {
               if (!$localStorage.user) {
                   $state.go('login');
               }
             }
        })
        .state('retiredemployeelist',{
             templateUrl:"pages/retiredemployeelist.html",
             url:'/retiredemployeelist',
             controller:"Emp_Controller",
             onEnter: function($localStorage, $state) {
               if (!$localStorage.user) {
                   $state.go('login');
               }
             }
        })
        .state('employeeDocuments',{
             templateUrl:'pages/employeeDocuments.html',
             url:'/employeeDocuments/:id',
             controller:"Emp_Controller",
             onEnter: function($localStorage, $state) {
               if (!$localStorage.user) {
                   $state.go('login');
               }
             }
        })
        .state('reciveDocuments',{
             templateUrl:'pages/reciveDocuments.html',
             url:'/reciveDocuments/:pension_id',
             controller:"Emp_Controller",
             onEnter: function($localStorage, $state) {
               if (!$localStorage.user) {
                   $state.go('login');
               }
             }
        })
        .state('sectionEmployeeDetails',{
             templateUrl:'pages/sectionEmployeeDetails.html',
             url:'/sectionEmployeeDetails/:pension_id',
             controller:"Emp_Controller",
             onEnter: function($localStorage, $state) {
               if (!$localStorage.user) {
                   $state.go('login');
               }
             }
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
