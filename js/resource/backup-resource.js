'use strict';

angular.module('dnuiPlugin').factory('BackupResource',
    ['$resource',
        function ($resource) {
            return $resource(ajaxurl, [],
                {
                    get: {
                        method: 'GET',
                        params: {
                            action: 'dnui_get_all_backup'
                        }
                    },
                    deleteAll: {
                        method: 'POST',
                        params: {
                            action: 'dnui_delete_all_backup'
                        }
                    },
                    deleteById: {
                        method: 'POST',
                        params: {
                            action: 'dnui_delete_by_id_backup',
                            id: '@id'
                        }
                    },
                    make: {
                        method: 'GET',
                        params: {
                            action: 'dnui_make_backup',
                            id: '@id',
                            sizeName: '@sizeName'
                        }
                    },
                    restore: {
                        method: 'POST',
                        params: {
                            action: 'dnui_restore_backup',
                            id: '@id'
                        }
                    },
                    makeBackupFolder: {
                        method: 'POST',
                        params: {
                            action: 'dnui_make_backup_folder_backup'
                        }
                    },
                    existsBackupFolder: {
                        method: 'POST',
                        params: {
                            action: 'dnui_exists_backup_folder_backup'
                        }
                    }
                }
            );
        }
    ]
);
