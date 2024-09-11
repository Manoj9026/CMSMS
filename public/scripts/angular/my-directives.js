/**
 * Created by Akila on 5/12/2016.
 */

angular.module('myDirectives',['ngAnimate'])
.directive('selectBox', function() {
    return {
        restrict: 'EA',
        templateUrl: 'templates/select.html',
        controller: function($scope) {
            $scope.showSelect = false;
            $scope.selectValue = 20;
            $scope.getLimit = function(limit){
                $scope.selectValue = limit;
                $scope.showSelect = false;
            }}
    }
})
