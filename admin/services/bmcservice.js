app.factory("ulbService", function ($http,CONFIG,$localStorage) {
  return{
    getEmployeeList : function(){
      var response = $http.get(CONFIG.HTTP_HOST+"?reqmethod=employee&operation=get",{
         headers: {'Accesstoken':$localStorage.user.accessToken}
       });
      return response;
    },
    loadEmployeebyID: function(data,option){
      var _serializedData = $.param({"reqmethod": 'employee', "operation":option, "employee_data" : data});
      var response = $http({
          method: 'POST',
          url: CONFIG.HTTP_HOST,
          data : _serializedData,
          headers: {'Content-Type': 'application/x-www-form-urlencoded','Accesstoken':$localStorage.user.accessToken}
      });
      return response;
    },
    manageEmployee: function (data,option) {
     var _serializedData = $.param({"reqmethod": 'employee',"operation":option, "employee_data":data});
     var response = $http({
         method: 'POST',
         url: CONFIG.HTTP_HOST,
         data : _serializedData,
        headers: {'Content-Type': 'application/x-www-form-urlencoded','Accesstoken':$localStorage.user.accessToken}
     });
     return response;
   }
  }
});
app.factory("employeeService", function ($http,CONFIG,$localStorage) {
  return{
    login: function (data) {
      var _serializedData = $.param({"reqmethod": 'login', "user_name":data.username,"password":data.password});
      var response = $http({
          method: 'POST',
          url: CONFIG.HTTP_HOST,
          data : _serializedData,
          headers: {
              'Content-Type': 'application/x-www-form-urlencoded'
          }
      });
      return response;
    },
    logout: function () {
      var response = $http.get(CONFIG.HTTP_HOST+"?reqmethod=logout",{
         headers: {'Accesstoken':$localStorage.user.accessToken}
       });
      return response;
    },
    desingnation: function(){
      var response = $http.get(CONFIG.HTTP_HOST+"?reqmethod=getDesignationList",{
         headers: {}
       });
      return response;
    },
    loaddistrict: function(){
      var response = $http.get(CONFIG.HTTP_HOST+"?reqmethod=getDistrictList",{
         headers: {}
       });
      return response;
    },
    getdistrictulb: function(id){
      var response = $http.get(CONFIG.HTTP_HOST+"?reqmethod= getULBList&district_id="+id,{
         headers: {}
       });
      return response;
    },
    submitdetails: function(data,option){
      var _serializedData = $.param({"reqmethod": 'employee', "operation":option, "employee_data" : data});
      var response = $http({
          method: 'POST',
          url: CONFIG.HTTP_HOST,
          data : _serializedData,
          headers: {'Content-Type': 'application/x-www-form-urlencoded','Accesstoken':$localStorage.user.accessToken}
      });

      return response;
    },
    loadRetiredEmployee:function(){
      var response = $http.get(CONFIG.HTTP_HOST + "?reqmethod=getEmployee&status=Retired",{
        headers:{'Accesstoken':$localStorage.user.accessToken}
      });
      return response;
    },
    getEmployeeListBystatus:function(status){
      var response = $http.get(CONFIG.HTTP_HOST + "?reqmethod=employeeList&status="+status,{
        headers:{'Accesstoken':$localStorage.user.accessToken}
      });
      return response;
    },
    acceptRetirement:function(id){
      var response = $http.get(CONFIG.HTTP_HOST + "?reqmethod=acceptRetirement&id="+id,{
        headers:{'Accesstoken':$localStorage.user.accessToken}
      });
      return response;
    },
    loadEmployeeDocs: function(id){
      var response = $http.get(CONFIG.HTTP_HOST+"?reqmethod=getEmployeeDocument&id="+id,{
         headers:{'Accesstoken':$localStorage.user.accessToken}
       });
      return response;
    },
    getDomentList: function(){
      var response = $http.get(CONFIG.HTTP_HOST+"?reqmethod=getDocumentList");
      return response;
    },
    updateEmployeeDoc:function(data){
      var _serializedData = $.param({"reqmethod": 'updateEmployeeDocument',"documents_data":data});
      var response = $http({
          method: 'POST',
          url: CONFIG.HTTP_HOST,
          data : _serializedData,
          headers: {'Content-Type': 'application/x-www-form-urlencoded','Accesstoken':$localStorage.user.accessToken}
      });
      return response;
    },
    loadEmployeebyStatus: function () {
    var response = $http.get(CONFIG.HTTP_HOST + "/?reqmethod=employeeCount", {
            headers:{'Accesstoken':$localStorage.user.accessToken}
    });
    return response;
    // console.log('response');
  },
  getEmpProfile : function(pension_id){
    var response = $http.get(CONFIG.HTTP_HOST+"?reqmethod=retiredDocuments&pension_id="+pension_id,{
       headers: {'Accesstoken':$localStorage.user.accessToken}
     });
    return response;
  },
  receiveDocument : function(pension_id){
    var response = $http.get(CONFIG.HTTP_HOST+"?reqmethod=receiveDocument&pension_id="+pension_id,{
       headers: {'Accesstoken':$localStorage.user.accessToken}
     });
    return response;
  },
  updateRetireDoc : function(obj){
    var _serializedData = $.param({"reqmethod": 'updateDocument',"document_data":obj});
    var response = $http({
        method: 'POST',
        url: CONFIG.HTTP_HOST,
        data : _serializedData,
        headers: {'Content-Type': 'application/x-www-form-urlencoded','Accesstoken':$localStorage.user.accessToken}
    });
    return response;
  }
};
});
app.factory("UserService", function($http,CONFIG,$localStorage){
  return{
    checkPassword: function (data) {
       var _serializedData = $.param({"reqmethod": 'checkPassword', "token":data.token,"password":data.password});
       var response = $http({
           method: 'POST',
           url: CONFIG.HTTP_HOST,
           data : _serializedData,
             headers: {'Content-Type': 'application/x-www-form-urlencoded','Accesstoken':$localStorage.user.accessToken}
       });
       return response;
    },
    updateProfile:function(data){
      var _serializedData = $.param({"reqmethod": 'updateProfile',"user_data":data});
      var response = $http({
          method: 'POST',
          url: CONFIG.HTTP_HOST,
          data : _serializedData,
        headers: {'Content-Type': 'application/x-www-form-urlencoded','Accesstoken':$localStorage.user.accessToken}
      });
      return response;
    },
    changepassword:function(data){
       var _serializedData = $.param({"reqmethod": 'changePassword', "password":data});
       var response = $http({
           method: 'POST',
           url: CONFIG.HTTP_HOST,
           data : _serializedData,
          headers: {'Content-Type': 'application/x-www-form-urlencoded','Accesstoken':$localStorage.user.accessToken}
       });
       return response;
    },
    checkPassword: function (data) {
        var _serializedData = $.param({"reqmethod": 'checkPassword', "password":data});
        var response = $http({
            method: 'POST',
            url: CONFIG.HTTP_HOST,
            data : _serializedData,
            headers: {'Content-Type': 'application/x-www-form-urlencoded','Accesstoken':$localStorage.user.accessToken}
        });
        return response;
    },
    applyToken: function (mail) {
        var _serializedData = $.param({"reqmethod": 'sendOtp', "toEmail":mail});
        var response = $http({
            method: 'POST',
            url: CONFIG.HTTP_HOST,
            data : _serializedData,
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        });
        return response;
    },
    forgotPassword: function (obj) {
        var _serializedData = $.param({"reqmethod": 'forgotPassword', "email":obj.user_email, "password":obj.cpass, "otp":obj.otp});
        var response = $http({
            method: 'POST',
            url: CONFIG.HTTP_HOST,
            data : _serializedData,
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        });
        return response;
    }
  };
});
