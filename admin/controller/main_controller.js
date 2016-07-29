app.controller("Main_Controller",function($scope,$rootScope,$state,$localStorage,employeeService){
  /*******************************************************/
  /*************This is use for check user login**********/
  /*******************************************************/
  $scope.getUserDetails = function(){
   if($localStorage.user.accessToken){
     $scope.logedIn_user = $localStorage.user;
     $rootScope.loggedin = true;
   }
   else{
     $scope.logedIn_user = {};
     $rootScope.loggedin = false;
   }
 }
 /*******************************************************/
 /*************This is use for  user login***************/
 /*******************************************************/
  $scope.login = function(user){
    employeeService.login(user).then(function(pRes) {
      if(pRes.data.statusCode == 200){
        $localStorage.user ={
          "district_id" : pRes.data.data.district_id,
           "email"      : pRes.data.data.email,
           "id"         : pRes.data.data.id,
           "roll_id"    : pRes.data.data.roll_id,
           "ulb_id"     : pRes.data.data.ulb_id,
           "user_name"  : pRes.data.data.user_name,
           "accessToken": pRes.data.data.token
        }
        $scope.getUserDetails();
        $state.go("dashboard");
      }
      else{
          Util.alertMessage('danger', pRes.data.message);
      }
    },
    function(err) {
      console.log(">>>>>>>>>>>>>   ",err);
    })
  }
  $scope.signOut = function(){
    employeeService.logout(localStorage.getItem('accessToken')).then(function(pRes) {
      if(pRes.status == 200){
        console.log(pRes.data.message);
        $rootScope.loggedin = false;
        localStorage.setItem('accessToken','');
        $state.go("login");
      }
    },
    function(err) {
      console.log(">>>>>>>>>>>>>   ",err);
    })
  }
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
