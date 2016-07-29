app.controller("Main_Controller",function($scope,$rootScope,$state,$localStorage){
  /*******************************************************/
  /*************This is use for check user login**********/
  /*******************************************************/
  $scope.getUserDetails = function(){
   if(localStorage.getItem('accessToken')){
     $rootScope.loggedin = true;
 //     $rootScope.user_type = localStorage.getItem('userType');
 //     userService.getUserDetails(localStorage.getItem('accessToken')).then(function(pRes) {
 //         if(pRes.status == 200){
 //           $scope.profile = pRes.data.data;
 //         }
 //       },function(err) {
 //       console.log(">>>>>>>>>>>>>   ",err);
 //     })
   }
   else{
     $rootScope.loggedin = false;
   }
 }
 /*******************************************************/
 /*************This is use for  user login***************/
 /*******************************************************/
  $scope.login = function(){
      localStorage.setItem('accessToken','abc1234');
      console.log(localStorage.getItem('accessToken')) ;
      var uname = $scope.user.username;
      var password = $scope.user.password;
      if(uname == 'admin' && password == 'admin'){
          $rootScope.loggedin = true;
          $state.go('dashboard');
      }
      else{
          alert("error");
      }
  };
  /*******************************************************/
  /*************This is use for  user logout**************/
  /*******************************************************/
  $scope.open2 = function() {
   $scope.popup1.opened1 = true;
  };
  $scope.formats = ['dd-MMMM-yyyy', 'yyyy/MM/dd', 'dd.MM.yyyy', 'shortDate'];
  $scope.format = $scope.formats[0];
  $scope.altInputFormats = ['M!/d!/yyyy'];
  $scope.popup1 = {
    opened1: false
  };

  function getDayClass(data) {
    var date = data.date,
      mode = data.mode;
    if (mode === 'day') {
      var dayToCheck = new Date(date).setHours(0,0,0,0);

      for (var i = 0; i < $scope.events.length; i++) {
        var currentDay = new Date($scope.events[i].date).setHours(0,0,0,0);

        if (dayToCheck === currentDay) {
          return $scope.events[i].status;
        }
      }
    }
    return '';
  }
 $scope.open = function() {
  $scope.popup2.opened = true;
 };
 $scope.formats = ['dd-MMMM-yyyy', 'yyyy/MM/dd', 'dd.MM.yyyy', 'shortDate'];
 $scope.format = $scope.formats[0];
 $scope.altInputFormats = ['M!/d!/yyyy'];
 $scope.popup2 = {
   opened: false
 };

 function getDayClass(data) {
   var date = data.date,
     mode = data.mode;
   if (mode === 'day') {
     var dayToCheck = new Date(date).setHours(0,0,0,0);

     for (var i = 0; i < $scope.events.length; i++) {
       var currentDay = new Date($scope.events[i].date).setHours(0,0,0,0);

       if (dayToCheck === currentDay) {
         return $scope.events[i].status;
       }
     }
   }
   return '';
 }
});
