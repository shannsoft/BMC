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
          $rootScope.loggedin =true;
          $state.go('dashboard');
      }
      else{
          alert("error");
      }
  };
  /*******************************************************/
  /*************This is use for  user logout**************/
  /*******************************************************/
  $scope.signOut = function(){
    localStorage.setItem('accessToken','');
    $rootScope.loggedin = false;
    $state.go('login');
  }
});
