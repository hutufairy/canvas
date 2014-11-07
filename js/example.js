var exampleApp = angular.module('exampleApp', ['ngRoute', 'exampleServices']);
var exampleServices = angular.module('exampleServices', ['ngResource']);

exampleApp.config(['$routeProvider', function($routeProvider){
    $routeProvider.when('/', {templateUrl: 'views/list.php', controller: 'exampleListCtrl'})
    // .when('/:name', {templateUrl: function(urlattr){
    //         return 'views/' + urlattr.name + '.php';
    //     }, controller: 'exampleViewCtrl'})
    .otherwise({redirectTo: '/'});
}]);



exampleServices.factory('Example', function($resource){
    return $resource('json/:name.json', {}, {
        query: {method:'GET', params:{name:'list'}, isArray:true}
    });
});

exampleApp.controller('exampleListCtrl', function($scope, Example){
    $scope.examples = Example.query();
});