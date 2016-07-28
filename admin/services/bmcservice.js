// app.factory("userService", function ($http,CONFIG) {
//   return{
//     login: function (data) {
//       var _serializedData = $.param();
//       var response = $http({
//           method: 'POST',
//           url: CONFIG.HTTP_HOST,
//           data : _serializedData,
//           headers: {
//               'Content-Type': 'application/x-www-form-urlencoded'
//           }
//       });
//       return response;
//     },
