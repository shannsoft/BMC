app.controller("Main_Controller",function($scope,$rootScope,$state,$localStorage,employeeService,Util){
  /*******************************************************/
  /*************This is use for check user login**********/
  /*******************************************************/
  $scope.getUserDetails = function(){
   if($localStorage.user){
     $scope.logedIn_user = $localStorage.user;
     $rootScope.loggedin = true;
     $rootScope.roll_id = localStorage.getItem('roll_id');
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
           "accessToken": pRes.data.data.token,
        }
       localStorage.setItem("roll_id",pRes.data.data.roll_id);
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
  /*******************************************************/
  /*************This is use for  user logout**************/
  /*******************************************************/
  $scope.signOut = function(){
    employeeService.logout().then(function(pRes) {
      if(pRes.status == 200){
        $rootScope.loggedin = false;
        delete $localStorage.user;
        $state.go("login");
      }
    },
    function(err) {
      console.log(">>>>>>>>>>>>>   ",err);
    })
  }
  /***************This is for showing calender pop up*******************/
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
 /****binds employee desingnation in newemployee page**/
 $scope.desingnation = function(){
   employeeService.desingnation().then(function(pRes) {
     if(pRes.status == 200){
     $scope.employeelist = pRes.data.data;
     }

  //  console.log(pRes);
  })
 }
 /**binds district and ulb in district and ulb dropdown in newemployee page**/
 $scope.loaddistrict = function(){
   employeeService.loaddistrict().then(function(pRes){
     if(pRes.status == 200){
     $scope.district = pRes.data.data;
     }
     //console.log(pRes);
   })
 }
 $scope.getdistrictulb = function(emp_district){
   //console.log(emp_district);
   employeeService.getdistrictulb(parseInt(emp_district)).then(function(pRes){
     $scope.ulblist = pRes.data.data;
    // console.log(pRes);
   })
 }
 $scope.submitdetails = function(){
    var dor = moment($scope.employee.dob).add(60,"years").format("YYYY-MM-DD")
    var obj = {
      "name":$scope.employee.emp_name,
      "desingnation":$scope.employee.emp_designation,
      "village":$scope.employee.emp_village,
      "city":$scope.employee.emp_city,
      "post":$scope.employee.emp_po,
      "ps":$scope.employee.emp_ps,
      "district":$scope.employee.emp_district,
      "pin":$scope.employee.emp_pin,
      "mobile":$scope.employee.mobile,
      "email":$scope.employee.email,
      "ulb_id":$scope.employee.emp_ulb,
      "dob":moment($scope.employee.dob).format("YYYY-MM-DD"),
      "doj":moment($scope.employee.doj).format("YYYY-MM-DD"),
      "dor":dor,
      "status":$scope.employee.emp_status,
    }
    employeeService.submitdetails(obj,'create').then(function(pRes){
      if(pRes.data.statusCode == 200){
        Util.alertMessage('success', pRes.data.message);
        console.log(pRes);
      }
      // else {
      //   alertMessage('danger');
      // }
    })
 }
});
