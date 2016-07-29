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
               if (!localStorage.getItem('accessToken')) {
                   $state.go('login');
               }
             }
        })
        .state('login', {
            templateUrl: 'pages/login.html',
            url: '/login',
            controller:"Main_Controller",
            onEnter: function($localStorage, $state) {
               if (localStorage.getItem('accessToken')) {
                   $state.go('dashboard');
               }
             }
        })
        .state('newemployee',{
             templateUrl:'pages/newemployee.html',
             url:'/newemployee',
             controller:"Main_Controller",
             onEnter: function($localStorage, $state) {
               if (!localStorage.getItem('accessToken')) {
                   $state.go('login');
               }
             }
        })
        .state('existingemployee',{
             templateUrl:'pages/existingemployee.html',
             url:'/existingemployee',
             controller:"Main_Controller",
             onEnter: function($localStorage, $state) {
               if (!localStorage.getItem('accessToken')) {
                   $state.go('login');
               }
             }
        })
});
app.constant('CONFIG', {
  'HTTP_HOST': '../server/api.php' //client staging
})
// app.factory('Util', ['$rootScope',  '$timeout' , function( $rootScope, $timeout){
//     var Util = {};
//     $rootScope.alerts =[];
//     Util.alertMessage = function(msgType, message){
//         console.log(1212121);
//         var alert = { type:msgType , msg: message };
//         $rootScope.alerts.push( alert );
//         $timeout(function(){
//             $rootScope.alerts.splice($rootScope.alerts.indexOf(alert), 1);
//         }, 5000);
//     };
//     return Util;
// }]);
//
// app.directive('fileModel', ['$parse', function ($parse) {
//     return {
//        restrict: 'A',
//        link: function(scope, element, attrs) {
//           var model = $parse(attrs.fileModel);
//           var modelSetter = model.assign;
//
//           element.bind('change', function(){
//              scope.$apply(function(){
//                 modelSetter(scope, element[0].files[0]);
//              });
//           });
//        }
//     };
 // }]);
