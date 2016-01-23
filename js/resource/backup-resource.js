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
                            action: 'dnui_delete_by_id_backup'
                        }
                    },
                    make: {
                        method: 'POST',
                        params: {
                            action: 'dnui_make_backup'

                        }
                    },
                    restore: {
                        method: 'POST',
                        params: {
                            action: 'dnui_restore_backup'
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
