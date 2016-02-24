/* ----------------------------------------------------------------
App Directives
-----------------------------------------------------------------*/

var webAppDirectives = angular.module('webAppDirectives', []);
/*
webAppDirectives.directive("scroll",  ['$window', 'sharedProperties',
    function($window, sharedProperties) {
        
        return function(scope, element, attrs) {
        angular.element($window).bind("scroll", function() {
            
            if( sharedProperties.get('canScroll') === true){
                var pos = this.pageYOffset;
                sharedProperties.set('windowScroll', pos);
                scope.$apply();
            }
            
        });
    };
}]);
*/