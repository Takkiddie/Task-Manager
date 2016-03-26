angular.module('taskmanager', ['ui.bootstrap', 'ngRoute', 'angularSpinners']);


angular.module('taskmanager').config(function ($routeProvider, $locationProvider) {
    $routeProvider
    //.ignoreCase()
    .when('/tasks/',
    {
        title: 'View Tasks',
        controller: 'TasksController',
        templateUrl: 'views/tasks.html'
    })
    /* 404 page*/
    .otherwise(
    {
        title: '/404',
        templateUrl: 'views/404.html'
    });

    $locationProvider.html5Mode(true);
});
