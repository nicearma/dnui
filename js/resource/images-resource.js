'use strict';

angular.module('dnuiPlugin').factory('ImagesResource',
    ['$resource',
        function ($resource) {
            return $resource(ajaxurl, [],
                {
                    get: {
                        method: 'GET',
                        params: {
                            action: 'dnui_get_image',
                            id: '@id'
                        }
                    },
                    count: {
                        method: 'GET',
                        params: {
                            action: 'dnui_count_image'
                        },
                        isArray: true
                    },
                    readByOptions: {
                        method: 'GET',
                        params: {
                            action: 'dnui_get_all_by_options_image',
                            numberPage: '@numberPage'
                        },
                        isArray: true
                    },
                    galleries: {
                        method: 'GET',
                        params: {
                            action: 'dnui_get_galleries_image'
                        }
                    },
                    shortcodes: {
                        method: 'GET',
                        params: {
                            action: 'dnui_get_shortcodes_image'
                        },
                        isArray: true
                    },
                    verifyUsedById: {
                        method: 'GET',
                        params: {
                            action: 'dnui_verify_status_by_id_image',
                            id: '@id'
                        }
                    },
                    deleteByIdAndSize: {
                        method: 'POST',
                        params: {
                            action: 'dnui_delete_by_id_and_size_image'
                        }
                    }
                }
            );
        }
    ]
);