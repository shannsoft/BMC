
app.controller("Emp_Controller",function($scope,$rootScope,$state,$localStorage,ulbService,$stateParams,$window,Util,employeeService){
  $scope.employeeDoc = {};
  $scope.currentTab = 'empprofile';
  $scope.changeTab = function(tab){
  $scope.currentTab = tab;
  }
  /*******************************************************/
  /*************This is use for employee List*************/
  /*******************************************************/
  $scope.loadEmpList = function(){
    ulbService.getEmployeeList().then(function(pRes) {
      if(pRes.data.statusCode == 200){
        $scope.emp_list = pRes.data.data;
      }
    })
  }
  /*******************************************************/
  /*****This is use for get pending count on each ulb*****/
  /*******************************************************/
  $scope.ulbPendingCount = function(){
    ulbService.ulbPendingCount().then(function(pRes) {
      if(pRes.data.statusCode == 200){
        $scope.ulbPendingCount = pRes.data.data;
      }
    })
  }
  /*******************************************************/
  /*****This is use for get pending pensionby  ulb********/
  /*******************************************************/
  $scope.pendingPensionByULB = function(){
    ulbService.pendingPensionByULB($stateParams.id).then(function(pRes) {
      if(pRes.data.statusCode == 200){
        $scope.ulbPendingList = pRes.data.data;
        console.log($scope.ulbPendingList);
      }
    })
  }
  /*******************************************************/
  /********This is use for employee List by status********/
  /*******************************************************/
  $scope.loadEmployeeListByStatus = function(){
    $scope.status = $stateParams.status;
    employeeService.getEmployeeListBystatus($stateParams.status).then(function(pRes) {
      if(pRes.data.statusCode == 200){
        $scope.employee_list = pRes.data.data;
        $scope.loaddistrict();
      }
    })
  }
  /*******************************************************/
  /********This is use for employee List by status********/
  /*******************************************************/
  $scope.acceptRetirement = function(id){
    employeeService.acceptRetirement(id).then(function(pRes) {
      if(pRes.data.statusCode == 200){
        $state.go('employeeDocuments',{id:id});
      }
    })
  }
  /*****This is redirect employee to edit employee page***/
  $scope.goToEdit = function(id){
    console.log(id);
    $state.go('editEmployee',{id:id})
  }
  /******This is use for loading editemployee page along with employee data for further edit******/
  $scope.loadEmployeebyID = function(){
    var obj ={
      "id":$stateParams.id
    }
    ulbService.loadEmployeebyID(obj,'get').then(function(pRes){
      if(pRes.data.statusCode == 200){
       $scope.employee = pRes.data.data[0];
       $scope.getdistrictulb($scope.employee.district_id);
      }
      console.log(pRes);
    })
     $scope.loadEmployeebyID = function(){
        var obj ={
          "id":$stateParams.id
        }
        ulbService.loadEmployeebyID(obj,'get').then(function(pRes){
          if(pRes.data.statusCode == 200){
           $scope.employee = pRes.data.data[0];
           $scope.getdistrictulb($scope.employee.district_id);
          }
          console.log(pRes);
        })
      }
  }
  /***********This is for update employee data ***********/
  $scope.updateEmployee = function(id){
      var dor = moment($scope.employee.dob).add(60,"years").format("YYYY-MM-DD")
      var obj = {
      "id":id,
      "name":$scope.employee.name,
      "desingnation":$scope.employee.designation_id,
      "village":$scope.employee.village,
      "city":$scope.employee.city,
      "post":$scope.employee.post,
      "ps":$scope.employee.police_station,
      "district":$scope.employee.district_id,
      "pin":$scope.employee.pin,
      "mobile":$scope.employee.mobile,
      "email":$scope.employee.email,
      "ulb_id":$localStorage.user.ulb_id,
      "dob":moment($scope.employee.dob).format("YYYY-MM-DD"),
      "doj":moment($scope.employee.doj).format("YYYY-MM-DD"),
      "dor":dor,
      "status":$scope.employee.emp_status,
    }
    ulbService.manageEmployee(obj,'update').then(function(pRes){
      console.log(pRes);
      if(pRes.data.statusCode == 200){
        Util.alertMessage('success', pRes.data.message);
      }
    })
  }
  /******This is for delete employee from employee list********/
  $scope.deleteEmployee = function(id){
    deleteUser = $window.confirm('Are you sure you want to delete this employee?');
    if(deleteUser){
      var obj = {
       "id":id
      }
      ulbService.manageEmployee(obj,'delete').then(function(pRes) {
       $scope.loadEmpList();
     })
    }
 }
 $scope.loadRetiredEmployee = function(){
   $scope.status = localStorage.getItem('status');
   employeeService.loadRetiredEmployee().then(function(pRes){
     console.log(pRes);
     $scope.employee_list = pRes.data.data;
   })
 };
 $scope.loadEmpdocument = function(id){
   $state.go('employeeDocuments',{id:id});
 }
 $scope.loadEmployeeDocs = function(){
  employeeService.getDomentList().then(function(response){
    if(response.data.statusCode == 200){
      $scope.documentList = response.data.data;
      employeeService.loadEmployeeDocs($stateParams.id).then(function(pRes){
        if(pRes.data.statusCode == 200){
          var documentList = [];
          if(pRes.data.data.length > 0){
            $scope.employeeDoc = pRes.data.data[0];
            documentList = pRes.data.data[0].documents.split(',');
          }
          angular.forEach($scope.documentList,function(item){
            item.is_selected = false;
            if(documentList.length > 0){
              angular.forEach(documentList,function(id){
                if(id == item.id){
                  item.is_selected = true;
                }
              })
            }
          });
        }
      });
    }
  });
}
/***********This is used for updating  employee document**************/
 $scope.updateEmployeeDoc = function(){
    var ref_date = moment($scope.employeeDoc.ref_date).format("YYYY-MM-DD");
    var documents = [];
    angular.forEach($scope.documentList,function(item){
      if(item.is_selected == true){
        documents.push(item.id);
      }
    });
    var pension_id =  ($scope.employeeDoc.id) ? $scope.employeeDoc.id : '';
    var dod =  ($scope.employeeDoc.dod) ? $scope.employeeDoc.dod : null;
    var obj = {
      "pension_id" : pension_id,
      "emp_id" : $stateParams.id,
      "pension_type":$scope.employeeDoc.pension_type,
      "category" :$scope.employeeDoc.pension_category,
      "documents":  documents.toString() ,
      "ref_no"  :$scope.employeeDoc.ref_no,
      "ref_date" :ref_date,
      "dod" :dod,
      "remarks" :$scope.employeeDoc.remarks,
      "nominee" :$scope.employeeDoc.nominee,
      "relation" :$scope.employeeDoc.relation,
    }
    employeeService.updateEmployeeDoc(obj) .then(function(pRes){
      $scope.updatedData = pRes.data.data;
      console.log($scope.updatedData);
      $scope.is_coverPage = true;
      $scope.selectedDoc = [];
      documents_list = $scope.updatedData.documents.split(',');
      angular.forEach($scope.documentList,function(documents){
      angular.forEach(documents_list,function(item){
          if(item == documents.id){
            $scope.selectedDoc.push(documents.document_name);
          }
        })
      });
    })

 }
 /***************************************************************/
 /*******This is use print the cover page document***************/
 /***************************************************************/
 $scope.printCoverPage  = function(div){
   //document.getElementById('title').innerHTML = $scope.updatedData.ulb;
   var docHead = document.head.outerHTML;
   document.getElementById('title').innerHTML = 'Odisha-eMunicipality';
   var printContents = document.getElementById(div).outerHTML;
   var winAttr = "location=yes, statusbar=no, menubar=no, titlebar=no, toolbar=no,dependent=no, width=865, height=600, resizable=yes, screenX=200, screenY=200, personalbar=no, scrollbars=yes";
   var newWin = window.open("", "_blank", winAttr);
   var writeDoc = newWin.document;
   writeDoc.open();
   writeDoc.write('<!doctype html><html>' + docHead + '<body onLoad="window.print()">' + printContents + '</body></html>');
   writeDoc.close();
   newWin.focus();
 }
 /***************************************************************/
 /**This is use to accept retired documents for central office***/
 /***************************************************************/
 $scope.loadReciveDocs = function(pension_id){
   employeeService.receiveDocument(pension_id).then(function(response){
     if(response.data.statusCode == 200){
       $state.go('reciveDocuments',{pension_id:pension_id});
     }
   });
 }
 /************************************************************/
 /**This is use load retired emp_details for central office***/
 /************************************************************/
 $scope.loadEmpProfile = function(){
   employeeService.getEmpProfile($stateParams.pension_id).then(function(pRes) {
     if(pRes.data.statusCode == 200){
       $scope.retired_employee = pRes.data.data;
       console.log($scope.retired_employee);
       var ducument_list = $scope.retired_employee.documents.split(',');
       employeeService.getDomentList().then(function(response){
         $scope.receivedDocument = [];
         if(response.data.statusCode == 200){
           $scope.documentList = response.data.data;
           angular.forEach($scope.documentList,function(doc){
             angular.forEach(ducument_list,function(id){
               if(id == doc.id){
                 $scope.receivedDocument.push(doc.document_name);
               }
             });
           });
         }
       });
     }
   })
 }
 /*******************************************************/
 /**This is use update the dcocument for central office**/
 /*******************************************************/
 $scope.updateRetireDoc = function(){
   var dep_date = moment($scope.retired_employee.department_ref_date).format("YYYY-MM-DD");
   var sec_date = moment($scope.retired_employee.section_ref_date).format("YYYY-MM-DD");
   var dod = moment($scope.retired_employee.dod).format("YYYY-MM-DD");
   var obj = {
     "pension_id"   : $scope.retired_employee.pension_id,
     "history_id"   : $scope.retired_employee.history_id,
     "send_to"      : $scope.retired_employee.send_to,
     "dep_ref_no"   : $scope.retired_employee.department_ref_no,
     "dep_ref_date" : dep_date,
     "sec_ref_no"   : $scope.retired_employee.section_ref_no,
     "sec_ref_date" : sec_date,
     "file_no"      : $scope.retired_employee.file_no,
     "remarks"      : $scope.retired_employee.remarks,
     "status"       : $scope.retired_employee.status,
     "pension_type" : $scope.retired_employee.pension_type,
     "category"     : $scope.retired_employee.pension_category,
     "nominee"      : $scope.retired_employee.nominee,
     "relation"     : $scope.retired_employee.relation
   }
   if(moment(dod, "YYYY-MM-DD", true).isValid())
    obj.dod = moment($scope.retired_employee.dod).format("YYYY-MM-DD")
    console.log(obj);
   employeeService.updateRetireDoc(obj).then(function(response){
     if(response.data.statusCode = 200){
       if($scope.retired_employee.send_to == "ULB"){
         if($scope.retired_employee.status == 'Rejected')
          $state.go('audit-objection',{pension_id:$scope.retired_employee.pension_id});
         else if($scope.retired_employee.status == 'Sactioned')
          $state.go('saction-letter',{pension_id:$scope.retired_employee.pension_id});
       }
       else
        Util.alertMessage('success', response.data.message);
     }
     else{
        Util.alertMessage('danger', response.data.message);
     }
   })
 }
 /*******************************************************/
 /****This is use load the employee by section office****/
 /*******************************************************/
 $scope.loadEmployeeBySection = function(){
   $scope.section = $stateParams.section;
   employeeService.sectionEmployee($stateParams.section).then(function(res){
     if(res.data.statusCode = 200){
       $scope.sectionEmpList = res.data.data;
     }
   },function(err){

   });
 }
});
app.controller("Main_Controller",function($scope,$rootScope,$state,$localStorage,employeeService,Util,$cookieStore,UserService){
  $scope.loginPage = function(){
    $scope.user = {};
    if($cookieStore.get('username')){
      $scope.user.username = $cookieStore.get('username');
      $scope.user.password = $cookieStore.get('password');
    }
  }
  /*******************************************************/
  /*************This is use for check user login**********/
  /*******************************************************/
  $scope.getUserDetails = function(){
   if($localStorage.user){
     $rootScope.logedIn_user = $localStorage.user;
     $rootScope.loggedin = true;
     $rootScope.roll_id = localStorage.getItem('roll_id');
   }
   else{
     $rootScope.logedIn_user = {};
     $rootScope.loggedin = false;
   }
 }
 /*******************************************************/
 /*************This is use for  user login***************/
 /*******************************************************/
  $scope.login = function(){
    employeeService.login($scope.user).then(function(pRes) {
      if(pRes.data.statusCode == 200){
        if($scope.user.remember){
          $cookieStore.put('username', $scope.user.username);
          $cookieStore.put('password', $scope.user.password);
        }
        $localStorage.user = {
          "district_id" : pRes.data.data.district_id,
          "district"    : pRes.data.data.district,
          "email"       : pRes.data.data.email,
          "mobile"      : pRes.data.data.mobile,
          "id"          : pRes.data.data.id,
          "roll_id"     : pRes.data.data.roll_id,
          "roll_type"   : pRes.data.data.roll_type,
          "ulb_id"      : pRes.data.data.ulb_id,
          "ulb"         : pRes.data.data.ulb_name,
          "address"     : pRes.data.data.address,
          "user_name"   : pRes.data.data.user_name,
          "accessToken" : pRes.data.data.token,
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
      if(pRes.data.statusCode == 200){
        $rootScope.loggedin = false;
        delete $localStorage.user;
        setTimeout(function () {
          $state.go("login");
        }, 500);
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
  $scope.open3 = function() {
   $scope.popup3.opened = true;
  };
  $scope.formats = ['dd-MMMM-yyyy', 'yyyy/MM/dd', 'dd.MM.yyyy', 'shortDate'];
  $scope.format = $scope.formats[0];
  $scope.altInputFormats = ['M!/d!/yyyy'];
  $scope.popup1 = {
    opened1: false
  };
  $scope.popup3 = {
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
     $scope.designationList = pRes.data.data;
     }
  })
 }
 /**binds district and ulb in district and ulb dropdown in newemployee page**/
 $scope.loaddistrict = function(){
   employeeService.loaddistrict().then(function(pRes){
     if(pRes.data.statusCode == 200){
       $scope.district = pRes.data.data;
       console.log($scope.district);
     }
   })
   $scope.current_ulb = $localStorage.user.ulb;
 }
 $scope.getdistrictulb = function(emp_district){
   employeeService.getdistrictulb(parseInt(emp_district)).then(function(pRes){
     $scope.ulblist = pRes.data.data;
   })
 }
 /************ This is for submitting employee details*************/
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
      "ulb_id":$localStorage.user.ulb_id,
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
      else {
        Util.alertMessage('danger', 'Error with add employee');
      }
    })
 }
 /***********This code is loading employee according to their status***************/
 $scope.loadEmployeebyStatus = function(){
   employeeService.loadEmployeebyStatus().then(function(pRes){
     $scope.empstatus = pRes.data.data;
   })
 }

 /*******************************************************/
 /*************This is use for  user logout**************/
 /*******************************************************/
 $scope.password = {};
 $scope.applyOTP = function(){
   $scope.is_forgot = true;
   UserService.applyToken($scope.password.user_email).then(function(res){
     if(res.data.statusCode == 200){
        $scope.is_token = true;
        $scope.is_forgot = false;
        Util.alertMessage('success', res.data.message);
      }
      else{
        Util.alertMessage('danger', res.data.message);
      }
   })
 }
 $scope.forgotPassword = function(){
   UserService.forgotPassword($scope.password).then(function(res){
     console.log(res);
     if(res.data.statusCode == 200){
        Util.alertMessage('success', res.data.message);
        setTimeout(function () {
          $state.go('login');
        }, 6000);
      }
      else{
        Util.alertMessage('danger', res.data.message);
      }
   })
 }

});
app.controller("User_controller",function($scope,$localStorage,$rootScope,UserService,Util){
/************ This is for userprofile***************/
  $scope.currentTab = 'myprofile';
  $scope.changeTab = function(tab){
  $scope.currentTab = tab;
  }
  /******* This is for update myprofile in user profile**********/
  $scope.updateMyProfile = function(){
    var obj = {
      'user_name':$scope.profile.user_name,
      'email':  $scope.profile.email,
      'mobile': $scope.profile.mobile,
      'address':$scope.profile.address
     }
     UserService.updateProfile(obj).then(function(pRes) {
         if(pRes.data.statusCode == 200){
           Util.alertMessage('success', pRes.data.message);
         }
       },function(err) {
       console.log(">>>>>>>>>>>>>   ",err);
     })
  }
  /********* This is for loading userdetails in user profile page***********/
  $scope.loadUserdetails = function(){
     $scope.userProfile = $localStorage.user;
  }
  /********This is for loading user profile according to thir id************/
  $scope.loadUserbyID = function(){
    $scope.profile = $localStorage.user
  }
  /******* This is for changepassword in userprofile page*************/
  $scope.changePassword = function(){
    UserService.changepassword($scope.password.confirm).then(function(pRes){
      if(pRes.data.statusCode == 200){
        Util.alertMessage('success', pRes.data.message);
      //  $scope.password = {};
      console.log(pRes);
      }
    })
  }
  /****** This is for checking currentpassword in user profile page*************/
  $scope.checkCurrentPassword = function(pwd){
    console.log(pwd);
    UserService.checkPassword(pwd).then(function(pRes) {
      console.log(pRes);
      $scope.is_correct_pwd = (pRes.data.statusCode == 200) ? true : false;
    })
  }
});
