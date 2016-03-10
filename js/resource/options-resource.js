'use strict';

angular.module('dnuiPlugin').factory('OptionsResource',
    ['$resource',
        function ($resource) {
            return $resource(ajaxurl, [],
                {
                    get: {
                        method: 'GET',
                        params: {
                            action: 'dnui_get_options'
                        }
                    },
                    update: {
                        method: 'POST',
                        params: {
                            action: 'dnui_update_options'
                        }
                    },
                    getSizes: {
                        method: 'GET',
                        params: {
                            action: 'dnui_get_sizes'
                        },
                        isArray:true
                    },
		            restore: {
                        method: 'POST',
                        params: {
                            action: 'dnui_restore_options'
                        }
                    },
                    haveWC:{
                        method:'GET',
                        params: {
                            action: 'dnui_have_wc_options'
                        }
                    }

                }
            );
        }
    ]
);
