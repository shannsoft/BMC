var app = angular.module("bmc_app", ['ui.router', 'ui.bootstrap', 'ngResource', 'ngStorage', 'ngAnimate','datePicker']);
app.config(function($stateProvider, $urlRouterProvider) {
    $urlRouterProvider.otherwise('/login');
    $stateProvider
    // HOME STATES AND NESTED VIEWS ========================================
        .state('dashboard', {
            templateUrl: 'pages/dashboard.html',
            url: '/dashboard',
            onEnter: function($localStorage, $state) {
               if (!localStorage.getItem('accessToken')) {
                   $state.go('login');
               }
           },
            controller:"Main_Controller"
        })
        .state('login', {
            templateUrl: 'pages/login.html',
            url: '/login',
            onEnter: function($localStorage, $state) {
               if (localStorage.getItem('accessToken')) {
                   $state.go('dashboard');
               }
           },
          controller:"Main_Controller"
        })

        .state('newemployee',{
             templateUrl:'pages/newemployee.html',
             url:'/newemployee',
             onEnter: function($localStorage, $state) {
               if (!localStorage.getItem('accessToken')) {
                   $state.go('login');
               }
           },
             controller:"main_controller"

        })
        .state('existingemployee',{
             templateUrl:'pages/existingemployee.html',
             url:'/existingemployee',
             onEnter: function($localStorage, $state) {
               if (!localStorage.getItem('accessToken')) {
                   $state.go('login');
               }
           },
             controller:"main_controller"

        })
});
// app.constant('CONFIG', {
//   'HTTP_HOST': '../server/api1.php' //client staging
// })
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
