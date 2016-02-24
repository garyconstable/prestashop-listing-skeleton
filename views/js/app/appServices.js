/* ----------------------------------------------------------------
App Service
-----------------------------------------------------------------*/

var webAppServices = angular.module('webAppServices', ['ngResource']);
/*
webAppServices.factory('Feeds', ['$resource',
    function($resource){
        return $resource('/some/url/', {}, {
            query: {method:'GET', params:{}, isArray:true}
        });
}]);
*/