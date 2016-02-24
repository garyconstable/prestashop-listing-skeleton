
/* ----------------------------------------------------------------
App Filters
-----------------------------------------------------------------*/
 
angular.module('webAppFilters', [])

.filter('unsafe', function($sce) {
    return function(val) {
        return $sce.trustAsHtml(val);
    };
})