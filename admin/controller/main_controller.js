app.controller("Main_Controller",function($scope,$rootScope,$state,$localStorage){
  /*******************************************************/
  /*************This is use for check user login**********/
  /*******************************************************/
  $scope.user={};

  $scope.submit = function(){
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
  $scope.signOut = function(){
    localStorage.getItem('accessToken');
    //console.log(localStorage.getItem('accessToken')) ;
    $rootScope.loggedin = false;
    localStorage.setItem('accessToken','');
    $state.go('login');
  }
});
