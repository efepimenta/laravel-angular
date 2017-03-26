angular.module('app.services')
    .service('Project', ['$resource', 'appConfig',
        function ($resource, appConfig) {
            return $resource(appConfig.baseUrl + '/project/:idProject', {
                idProject: '@idProject'
            }, {
                query: {
                    method: 'GET',
                    isArray: true
                },
                show: {
                    method: 'GET',
                    isArray: false
                },
                update: {
                    method: 'PUT'
                },
                delete_project: {
                    method: 'DELETE'
                }
            });
        }
    ]);