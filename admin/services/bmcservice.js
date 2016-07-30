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
    }
  };
});
