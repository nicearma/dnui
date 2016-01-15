/*
 This is from: https://angular-ui.github.io/bootstrap/
 */

angular.module('ui.bootstrap.pagination', ["template/pagination/pagination.html","template/pagination/pager.html"])
    .controller('UibPaginationController', ['$scope', '$attrs', '$parse', function($scope, $attrs, $parse) {
        var self = this,
            ngModelCtrl = { $setViewValue: angular.noop }, // nullModelCtrl
            setNumPages = $attrs.numPages ? $parse($attrs.numPages).assign : angular.noop;

        this.init = function(ngModelCtrl_, config) {
            ngModelCtrl = ngModelCtrl_;
            this.config = config;

            ngModelCtrl.$render = function() {
                self.render();
            };

            if ($attrs.itemsPerPage) {
                $scope.$parent.$watch($parse($attrs.itemsPerPage), function(value) {
                    self.itemsPerPage = parseInt(value, 10);
                    $scope.totalPages = self.calculateTotalPages();
                });
            } else {
                this.itemsPerPage = config.itemsPerPage;
            }

            $scope.$watch('totalItems', function() {
                $scope.totalPages = self.calculateTotalPages();
            });

            $scope.$watch('totalPages', function(value) {
                setNumPages($scope.$parent, value); // Readonly variable

                if ( $scope.page > value ) {
                    $scope.selectPage(value);
                } else {
                    ngModelCtrl.$render();
                }
            });
        };

        this.calculateTotalPages = function() {
            var totalPages = this.itemsPerPage < 1 ? 1 : Math.ceil($scope.totalItems / this.itemsPerPage);
            return Math.max(totalPages || 0, 1);
        };

        this.render = function() {
            $scope.page = parseInt(ngModelCtrl.$viewValue, 10) || 1;
        };

        $scope.selectPage = function(page, evt) {
            if (evt) {
                evt.preventDefault();
            }

            var clickAllowed = !$scope.ngDisabled || !evt;
            if (clickAllowed && $scope.page !== page && page > 0 && page <= $scope.totalPages) {
                if (evt && evt.target) {
                    evt.target.blur();
                }
                ngModelCtrl.$setViewValue(page);
                ngModelCtrl.$render();
            }
        };

        $scope.getText = function(key) {
            return $scope[key + 'Text'] || self.config[key + 'Text'];
        };

        $scope.noPrevious = function() {
            return $scope.page === 1;
        };

        $scope.noNext = function() {
            return $scope.page === $scope.totalPages;
        };
    }])

    .constant('uibPaginationConfig', {
        itemsPerPage: 10,
        boundaryLinks: false,
        directionLinks: true,
        firstText: 'First',
        previousText: 'Previous',
        nextText: 'Next',
        lastText: 'Last',
        rotate: true
    })

    .directive('uibPagination', ['$parse', 'uibPaginationConfig', function($parse, paginationConfig) {
        return {
            restrict: 'EA',
            scope: {
                totalItems: '=',
                firstText: '@',
                previousText: '@',
                nextText: '@',
                lastText: '@',
                ngDisabled:'='
            },
            require: ['uibPagination', '?ngModel'],
            controller: 'UibPaginationController',
            controllerAs: 'pagination',
            templateUrl: function(element, attrs) {
                return attrs.templateUrl || 'template/pagination/pagination.html';
            },
            replace: true,
            link: function(scope, element, attrs, ctrls) {
                var paginationCtrl = ctrls[0], ngModelCtrl = ctrls[1];

                if (!ngModelCtrl) {
                    return; // do nothing if no ng-model
                }

                // Setup configuration parameters
                var maxSize = angular.isDefined(attrs.maxSize) ? scope.$parent.$eval(attrs.maxSize) : paginationConfig.maxSize,
                    rotate = angular.isDefined(attrs.rotate) ? scope.$parent.$eval(attrs.rotate) : paginationConfig.rotate;
                scope.boundaryLinks = angular.isDefined(attrs.boundaryLinks) ? scope.$parent.$eval(attrs.boundaryLinks) : paginationConfig.boundaryLinks;
                scope.directionLinks = angular.isDefined(attrs.directionLinks) ? scope.$parent.$eval(attrs.directionLinks) : paginationConfig.directionLinks;

                paginationCtrl.init(ngModelCtrl, paginationConfig);

                if (attrs.maxSize) {
                    scope.$parent.$watch($parse(attrs.maxSize), function(value) {
                        maxSize = parseInt(value, 10);
                        paginationCtrl.render();
                    });
                }

                // Create page object used in template
                function makePage(number, text, isActive) {
                    return {
                        number: number,
                        text: text,
                        active: isActive
                    };
                }

                function getPages(currentPage, totalPages) {
                    var pages = [];

                    // Default page limits
                    var startPage = 1, endPage = totalPages;
                    var isMaxSized = angular.isDefined(maxSize) && maxSize < totalPages;

                    // recompute if maxSize
                    if (isMaxSized) {
                        if (rotate) {
                            // Current page is displayed in the middle of the visible ones
                            startPage = Math.max(currentPage - Math.floor(maxSize/2), 1);
                            endPage   = startPage + maxSize - 1;

                            // Adjust if limit is exceeded
                            if (endPage > totalPages) {
                                endPage   = totalPages;
                                startPage = endPage - maxSize + 1;
                            }
                        } else {
                            // Visible pages are paginated with maxSize
                            startPage = ((Math.ceil(currentPage / maxSize) - 1) * maxSize) + 1;

                            // Adjust last page if limit is exceeded
                            endPage = Math.min(startPage + maxSize - 1, totalPages);
                        }
                    }

                    // Add page number links
                    for (var number = startPage; number <= endPage; number++) {
                        var page = makePage(number, number, number === currentPage);
                        pages.push(page);
                    }

                    // Add links to move between page sets
                    if (isMaxSized && ! rotate) {
                        if (startPage > 1) {
                            var previousPageSet = makePage(startPage - 1, '...', false);
                            pages.unshift(previousPageSet);
                        }

                        if (endPage < totalPages) {
                            var nextPageSet = makePage(endPage + 1, '...', false);
                            pages.push(nextPageSet);
                        }
                    }

                    return pages;
                }

                var originalRender = paginationCtrl.render;
                paginationCtrl.render = function() {
                    originalRender();
                    if (scope.page > 0 && scope.page <= scope.totalPages) {
                        scope.pages = getPages(scope.page, scope.totalPages);
                    }
                };
            }
        };
    }])

    .constant('uibPagerConfig', {
        itemsPerPage: 10,
        previousText: '« Previous',
        nextText: 'Next »',
        align: true
    })

    .directive('uibPager', ['uibPagerConfig', function(pagerConfig) {
        return {
            restrict: 'EA',
            scope: {
                totalItems: '=',
                previousText: '@',
                nextText: '@',
                ngDisabled: '='
            },
            require: ['uibPager', '?ngModel'],
            controller: 'UibPaginationController',
            controllerAs: 'pagination',
            templateUrl: function(element, attrs) {
                return attrs.templateUrl || 'template/pagination/pager.html';
            },
            replace: true,
            link: function(scope, element, attrs, ctrls) {
                var paginationCtrl = ctrls[0], ngModelCtrl = ctrls[1];

                if (!ngModelCtrl) {
                    return; // do nothing if no ng-model
                }

                scope.align = angular.isDefined(attrs.align) ? scope.$parent.$eval(attrs.align) : pagerConfig.align;
                paginationCtrl.init(ngModelCtrl, pagerConfig);
            }
        };
    }]);

angular.module("template/pagination/pager.html", []).run(["$templateCache", function($templateCache) {
    $templateCache.put("template/pagination/pager.html",
        "<ul class=\"pager\">\n" +
        "  <li ng-class=\"{disabled: noPrevious()||ngDisabled, previous: align}\"><a href ng-click=\"selectPage(page - 1, $event)\">{{::getText('previous')}}</a></li>\n" +
        "  <li ng-class=\"{disabled: noNext()||ngDisabled, next: align}\"><a href ng-click=\"selectPage(page + 1, $event)\">{{::getText('next')}}</a></li>\n" +
        "</ul>\n" +
        "");
}]);

angular.module("template/pagination/pagination.html", []).run(["$templateCache", function($templateCache) {
    $templateCache.put("template/pagination/pagination.html",
        "<ul class=\"pagination\">\n" +
        "  <li ng-if=\"::boundaryLinks\" ng-class=\"{disabled: noPrevious()||ngDisabled}\" class=\"pagination-first\"><a href ng-click=\"selectPage(1, $event)\">{{::getText('first')}}</a></li>\n" +
        "  <li ng-if=\"::directionLinks\" ng-class=\"{disabled: noPrevious()||ngDisabled}\" class=\"pagination-prev\"><a href ng-click=\"selectPage(page - 1, $event)\">{{::getText('previous')}}</a></li>\n" +
        "  <li ng-repeat=\"page in pages track by $index\" ng-class=\"{active: page.active,disabled: ngDisabled&&!page.active}\" class=\"pagination-page\"><a href ng-click=\"selectPage(page.number, $event)\">{{page.text}}</a></li>\n" +
        "  <li ng-if=\"::directionLinks\" ng-class=\"{disabled: noNext()||ngDisabled}\" class=\"pagination-next\"><a href ng-click=\"selectPage(page + 1, $event)\">{{::getText('next')}}</a></li>\n" +
        "  <li ng-if=\"::boundaryLinks\" ng-class=\"{disabled: noNext()||ngDisabled}\" class=\"pagination-last\"><a href ng-click=\"selectPage(totalPages, $event)\">{{::getText('last')}}</a></li>\n" +
        "</ul>\n" +
        "");
}]);



angular.module('ui.bootstrap.tabs', ["uib/template/tabs/tab.html","uib/template/tabs/tabset.html"])

    .controller('UibTabsetController', ['$scope', function ($scope) {
        var ctrl = this,
            tabs = ctrl.tabs = $scope.tabs = [];

        ctrl.select = function(selectedTab) {
            angular.forEach(tabs, function(tab) {
                if (tab.active && tab !== selectedTab) {
                    tab.active = false;
                    tab.onDeselect();
                    selectedTab.selectCalled = false;
                }
            });
            selectedTab.active = true;
            // only call select if it has not already been called
            if (!selectedTab.selectCalled) {
                selectedTab.onSelect();
                selectedTab.selectCalled = true;
            }
        };

        ctrl.addTab = function addTab(tab) {
            tabs.push(tab);
            // we can't run the select function on the first tab
            // since that would select it twice
            if (tabs.length === 1 && tab.active !== false) {
                tab.active = true;
            } else if (tab.active) {
                ctrl.select(tab);
            } else {
                tab.active = false;
            }
        };

        ctrl.removeTab = function removeTab(tab) {
            var index = tabs.indexOf(tab);
            //Select a new tab if the tab to be removed is selected and not destroyed
            if (tab.active && tabs.length > 1 && !destroyed) {
                //If this is the last tab, select the previous tab. else, the next tab.
                var newActiveIndex = index === tabs.length - 1 ? index - 1 : index + 1;
                ctrl.select(tabs[newActiveIndex]);
            }
            tabs.splice(index, 1);
        };

        var destroyed;
        $scope.$on('$destroy', function() {
            destroyed = true;
        });
    }])

    .directive('uibTabset', function() {
        return {
            transclude: true,
            replace: true,
            scope: {
                type: '@'
            },
            controller: 'UibTabsetController',
            templateUrl: 'uib/template/tabs/tabset.html',
            link: function(scope, element, attrs) {
                scope.vertical = angular.isDefined(attrs.vertical) ? scope.$parent.$eval(attrs.vertical) : false;
                scope.justified = angular.isDefined(attrs.justified) ? scope.$parent.$eval(attrs.justified) : false;
            }
        };
    })

    .directive('uibTab', ['$parse', function($parse) {
        return {
            require: '^uibTabset',
            replace: true,
            templateUrl: 'uib/template/tabs/tab.html',
            transclude: true,
            scope: {
                active: '=?',
                heading: '@',
                onSelect: '&select', //This callback is called in contentHeadingTransclude
                //once it inserts the tab's content into the dom
                onDeselect: '&deselect'
            },
            controller: function() {
                //Empty controller so other directives can require being 'under' a tab
            },
            controllerAs: 'tab',
            link: function(scope, elm, attrs, tabsetCtrl, transclude) {
                scope.$watch('active', function(active) {
                    if (active) {
                        tabsetCtrl.select(scope);
                    }
                });

                scope.disabled = false;
                if (attrs.disable) {
                    scope.$parent.$watch($parse(attrs.disable), function(value) {
                        scope.disabled = !! value;
                    });
                }

                scope.select = function() {
                    if (!scope.disabled) {
                        scope.active = true;
                    }
                };

                tabsetCtrl.addTab(scope);
                scope.$on('$destroy', function() {
                    tabsetCtrl.removeTab(scope);
                });

                //We need to transclude later, once the content container is ready.
                //when this link happens, we're inside a tab heading.
                scope.$transcludeFn = transclude;
            }
        };
    }])

    .directive('uibTabHeadingTransclude', function() {
        return {
            restrict: 'A',
            require: '^uibTab',
            link: function(scope, elm) {
                scope.$watch('headingElement', function updateHeadingElement(heading) {
                    if (heading) {
                        elm.html('');
                        elm.append(heading);
                    }
                });
            }
        };
    })

    .directive('uibTabContentTransclude', function() {
        return {
            restrict: 'A',
            require: '^uibTabset',
            link: function(scope, elm, attrs) {
                var tab = scope.$eval(attrs.uibTabContentTransclude);

                //Now our tab is ready to be transcluded: both the tab heading area
                //and the tab content area are loaded.  Transclude 'em both.
                tab.$transcludeFn(tab.$parent, function(contents) {
                    angular.forEach(contents, function(node) {
                        if (isTabHeading(node)) {
                            //Let tabHeadingTransclude know.
                            tab.headingElement = node;
                        } else {
                            elm.append(node);
                        }
                    });
                });
            }
        };

        function isTabHeading(node) {
            return node.tagName && (
                node.hasAttribute('uib-tab-heading') ||
                node.hasAttribute('data-uib-tab-heading') ||
                node.hasAttribute('x-uib-tab-heading') ||
                node.tagName.toLowerCase() === 'uib-tab-heading' ||
                node.tagName.toLowerCase() === 'data-uib-tab-heading' ||
                node.tagName.toLowerCase() === 'x-uib-tab-heading'
                );
        }
    });


angular.module("uib/template/tabs/tab.html", []).run(["$templateCache", function($templateCache) {
    $templateCache.put("uib/template/tabs/tab.html",
        "<li ng-class=\"{active: active, disabled: disabled}\" class=\"uib-tab\">\n" +
        "  <div ng-click=\"select()\" uib-tab-heading-transclude>{{heading}}</div>\n" +
        "</li>\n" +
        "");
}]);

angular.module("uib/template/tabs/tabset.html", []).run(["$templateCache", function($templateCache) {
    $templateCache.put("uib/template/tabs/tabset.html",
        "<div>\n" +
        "  <ul class=\"nav nav-{{type || 'tabs'}}\" ng-class=\"{'nav-stacked': vertical, 'nav-justified': justified}\" ng-transclude></ul>\n" +
        "  <div class=\"tab-content\">\n" +
        "    <div class=\"tab-pane\" \n" +
        "         ng-repeat=\"tab in tabs\" \n" +
        "         ng-class=\"{active: tab.active}\"\n" +
        "         uib-tab-content-transclude=\"tab\">\n" +
        "    </div>\n" +
        "  </div>\n" +
        "</div>\n" +
        "");
}]);