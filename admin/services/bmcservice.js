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
    // submitdetails: function(){
    //   var response = $http.get(CONFIG.HTTP_HOST+"?reqmethod=submitdetails" ,{
    //       headers: {'Accesstoken':$localStorage.user.accessToken}
    //   });
    //}
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
    // employeedistrict:function(){
    //   var response = $http.get(CONFIG.HTTP_HOST+"?reqmethod= getULBList&id=+id",{
    //      headers: {}
    //    });
    //   return response;
    // }
  };
});
