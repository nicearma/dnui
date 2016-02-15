/*
 This is from: https://angular-ui.github.io/bootstrap/
 */

angular.module('ui.bootstrap.pagination', ["template/pagination/pagination.html", "template/pagination/pager.html"])
    .controller('UibPaginationController', ['$scope', '$attrs', '$parse', function ($scope, $attrs, $parse) {
        var self = this,
            ngModelCtrl = {$setViewValue: angular.noop}, // nullModelCtrl
            setNumPages = $attrs.numPages ? $parse($attrs.numPages).assign : angular.noop;

        this.init = function (ngModelCtrl_, config) {
            ngModelCtrl = ngModelCtrl_;
            this.config = config;

            ngModelCtrl.$render = function () {
                self.render();
            };

            if ($attrs.itemsPerPage) {
                $scope.$parent.$watch($parse($attrs.itemsPerPage), function (value) {
                    self.itemsPerPage = parseInt(value, 10);
                    $scope.totalPages = self.calculateTotalPages();
                });
            } else {
                this.itemsPerPage = config.itemsPerPage;
            }

            $scope.$watch('totalItems', function () {
                $scope.totalPages = self.calculateTotalPages();
            });

            $scope.$watch('totalPages', function (value) {
                setNumPages($scope.$parent, value); // Readonly variable

                if ($scope.page > value) {
                    $scope.selectPage(value);
                } else {
                    ngModelCtrl.$render();
                }
            });
        };

        this.calculateTotalPages = function () {
            var totalPages = this.itemsPerPage < 1 ? 1 : Math.ceil($scope.totalItems / this.itemsPerPage);
            return Math.max(totalPages || 0, 1);
        };

        this.render = function () {
            $scope.page = parseInt(ngModelCtrl.$viewValue, 10) || 1;
        };

        $scope.selectPage = function (page, evt) {
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

        $scope.getText = function (key) {
            return $scope[key + 'Text'] || self.config[key + 'Text'];
        };

        $scope.noPrevious = function () {
            return $scope.page === 1;
        };

        $scope.noNext = function () {
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

    .directive('uibPagination', ['$parse', 'uibPaginationConfig', function ($parse, paginationConfig) {
        return {
            restrict: 'EA',
            scope: {
                totalItems: '=',
                firstText: '@',
                previousText: '@',
                nextText: '@',
                lastText: '@',
                ngDisabled: '='
            },
            require: ['uibPagination', '?ngModel'],
            controller: 'UibPaginationController',
            controllerAs: 'pagination',
            templateUrl: function (element, attrs) {
                return attrs.templateUrl || 'template/pagination/pagination.html';
            },
            replace: true,
            link: function (scope, element, attrs, ctrls) {
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
                    scope.$parent.$watch($parse(attrs.maxSize), function (value) {
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
                            startPage = Math.max(currentPage - Math.floor(maxSize / 2), 1);
                            endPage = startPage + maxSize - 1;

                            // Adjust if limit is exceeded
                            if (endPage > totalPages) {
                                endPage = totalPages;
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
                    if (isMaxSized && !rotate) {
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
                paginationCtrl.render = function () {
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

    .directive('uibPager', ['uibPagerConfig', function (pagerConfig) {
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
            templateUrl: function (element, attrs) {
                return attrs.templateUrl || 'template/pagination/pager.html';
            },
            replace: true,
            link: function (scope, element, attrs, ctrls) {
                var paginationCtrl = ctrls[0], ngModelCtrl = ctrls[1];

                if (!ngModelCtrl) {
                    return; // do nothing if no ng-model
                }

                scope.align = angular.isDefined(attrs.align) ? scope.$parent.$eval(attrs.align) : pagerConfig.align;
                paginationCtrl.init(ngModelCtrl, pagerConfig);
            }
        };
    }]);

angular.module("template/pagination/pager.html", []).run(["$templateCache", function ($templateCache) {
    $templateCache.put("template/pagination/pager.html",
        "<ul class=\"pager\">\n" +
        "  <li ng-class=\"{disabled: noPrevious()||ngDisabled, previous: align}\"><a href ng-click=\"selectPage(page - 1, $event)\">{{::getText('previous')}}</a></li>\n" +
        "  <li ng-class=\"{disabled: noNext()||ngDisabled, next: align}\"><a href ng-click=\"selectPage(page + 1, $event)\">{{::getText('next')}}</a></li>\n" +
        "</ul>\n" +
        "");
}]);

angular.module("template/pagination/pagination.html", []).run(["$templateCache", function ($templateCache) {
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


angular.module('ui.bootstrap.tabs', ["uib/template/tabs/tab.html", "uib/template/tabs/tabset.html"])

    .controller('UibTabsetController', ['$scope', function ($scope) {
        var ctrl = this,
            tabs = ctrl.tabs = $scope.tabs = [];

        ctrl.select = function (selectedTab) {
            angular.forEach(tabs, function (tab) {
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
        $scope.$on('$destroy', function () {
            destroyed = true;
        });
    }])

    .directive('uibTabset', function () {
        return {
            transclude: true,
            replace: true,
            scope: {
                type: '@'
            },
            controller: 'UibTabsetController',
            templateUrl: 'uib/template/tabs/tabset.html',
            link: function (scope, element, attrs) {
                scope.vertical = angular.isDefined(attrs.vertical) ? scope.$parent.$eval(attrs.vertical) : false;
                scope.justified = angular.isDefined(attrs.justified) ? scope.$parent.$eval(attrs.justified) : false;
            }
        };
    })

    .directive('uibTab', ['$parse', function ($parse) {
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
            controller: function () {
                //Empty controller so other directives can require being 'under' a tab
            },
            controllerAs: 'tab',
            link: function (scope, elm, attrs, tabsetCtrl, transclude) {
                scope.$watch('active', function (active) {
                    if (active) {
                        tabsetCtrl.select(scope);
                    }
                });

                scope.disabled = false;
                if (attrs.disable) {
                    scope.$parent.$watch($parse(attrs.disable), function (value) {
                        scope.disabled = !!value;
                    });
                }

                scope.select = function () {
                    if (!scope.disabled) {
                        scope.active = true;
                    }
                };

                tabsetCtrl.addTab(scope);
                scope.$on('$destroy', function () {
                    tabsetCtrl.removeTab(scope);
                });

                //We need to transclude later, once the content container is ready.
                //when this link happens, we're inside a tab heading.
                scope.$transcludeFn = transclude;
            }
        };
    }])

    .directive('uibTabHeadingTransclude', function () {
        return {
            restrict: 'A',
            require: '^uibTab',
            link: function (scope, elm) {
                scope.$watch('headingElement', function updateHeadingElement(heading) {
                    if (heading) {
                        elm.html('');
                        elm.append(heading);
                    }
                });
            }
        };
    })

    .directive('uibTabContentTransclude', function () {
        return {
            restrict: 'A',
            require: '^uibTabset',
            link: function (scope, elm, attrs) {
                var tab = scope.$eval(attrs.uibTabContentTransclude);

                //Now our tab is ready to be transcluded: both the tab heading area
                //and the tab content area are loaded.  Transclude 'em both.
                tab.$transcludeFn(tab.$parent, function (contents) {
                    angular.forEach(contents, function (node) {
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


angular.module("uib/template/tabs/tab.html", []).run(["$templateCache", function ($templateCache) {
    $templateCache.put("uib/template/tabs/tab.html",
        "<li ng-class=\"{active: active, disabled: disabled}\" class=\"uib-tab\">\n" +
        "  <div ng-click=\"select()\" uib-tab-heading-transclude>{{heading}}</div>\n" +
        "</li>\n" +
        "");
}]);

angular.module("uib/template/tabs/tabset.html", []).run(["$templateCache", function ($templateCache) {
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


angular.module('ui.bootstrap.stackedMap', [])
/**
 * A helper, internal data structure that acts as a map but also allows getting / removing
 * elements in the LIFO order
 */
    .factory('$$stackedMap', function () {
        return {
            createNew: function () {
                var stack = [];

                return {
                    add: function (key, value) {
                        stack.push({
                            key: key,
                            value: value
                        });
                    },
                    get: function (key) {
                        for (var i = 0; i < stack.length; i++) {
                            if (key === stack[i].key) {
                                return stack[i];
                            }
                        }
                    },
                    keys: function () {
                        var keys = [];
                        for (var i = 0; i < stack.length; i++) {
                            keys.push(stack[i].key);
                        }
                        return keys;
                    },
                    top: function () {
                        return stack[stack.length - 1];
                    },
                    remove: function (key) {
                        var idx = -1;
                        for (var i = 0; i < stack.length; i++) {
                            if (key === stack[i].key) {
                                idx = i;
                                break;
                            }
                        }
                        return stack.splice(idx, 1)[0];
                    },
                    removeTop: function () {
                        return stack.splice(stack.length - 1, 1)[0];
                    },
                    length: function () {
                        return stack.length;
                    }
                };
            }
        };
    });


angular.module('ui.bootstrap.modal', ['ui.bootstrap.stackedMap',"uib/template/modal/backdrop.html", "uib/template/modal/window.html"])
/**
 * A helper, internal data structure that stores all references attached to key
 */
    .factory('$$multiMap', function () {
        return {
            createNew: function () {
                var map = {};

                return {
                    entries: function () {
                        return Object.keys(map).map(function (key) {
                            return {
                                key: key,
                                value: map[key]
                            };
                        });
                    },
                    get: function (key) {
                        return map[key];
                    },
                    hasKey: function (key) {
                        return !!map[key];
                    },
                    keys: function () {
                        return Object.keys(map);
                    },
                    put: function (key, value) {
                        if (!map[key]) {
                            map[key] = [];
                        }

                        map[key].push(value);
                    },
                    remove: function (key, value) {
                        var values = map[key];

                        if (!values) {
                            return;
                        }

                        var idx = values.indexOf(value);

                        if (idx !== -1) {
                            values.splice(idx, 1);
                        }

                        if (!values.length) {
                            delete map[key];
                        }
                    }
                };
            }
        };
    })

/**
 * Pluggable resolve mechanism for the modal resolve resolution
 * Supports UI Router's $resolve service
 */
    .provider('$uibResolve', function () {
        var resolve = this;
        this.resolver = null;

        this.setResolver = function (resolver) {
            this.resolver = resolver;
        };

        this.$get = ['$injector', '$q', function ($injector, $q) {
            var resolver = resolve.resolver ? $injector.get(resolve.resolver) : null;
            return {
                resolve: function (invocables, locals, parent, self) {
                    if (resolver) {
                        return resolver.resolve(invocables, locals, parent, self);
                    }

                    var promises = [];

                    angular.forEach(invocables, function (value) {
                        if (angular.isFunction(value) || angular.isArray(value)) {
                            promises.push($q.resolve($injector.invoke(value)));
                        } else if (angular.isString(value)) {
                            promises.push($q.resolve($injector.get(value)));
                        } else {
                            promises.push($q.resolve(value));
                        }
                    });

                    return $q.all(promises).then(function (resolves) {
                        var resolveObj = {};
                        var resolveIter = 0;
                        angular.forEach(invocables, function (value, key) {
                            resolveObj[key] = resolves[resolveIter++];
                        });

                        return resolveObj;
                    });
                }
            };
        }];
    })


/**
 * A helper directive for the $modal service. It creates a backdrop element.
 */
    .directive('uibModalBackdrop', ['$animateCss', '$injector', '$uibModalStack',
        function ($animateCss, $injector, $modalStack) {
            return {
                replace: true,
                templateUrl: 'uib/template/modal/backdrop.html',
                compile: function (tElement, tAttrs) {
                    tElement.addClass(tAttrs.backdropClass);
                    return linkFn;
                }
            };

            function linkFn(scope, element, attrs) {
                if (attrs.modalInClass) {
                    $animateCss(element, {
                        addClass: attrs.modalInClass
                    }).start();

                    scope.$on($modalStack.NOW_CLOSING_EVENT, function (e, setIsAsync) {
                        var done = setIsAsync();
                        if (scope.modalOptions.animation) {
                            $animateCss(element, {
                                removeClass: attrs.modalInClass
                            }).start().then(done);
                        } else {
                            done();
                        }
                    });
                }
            }
        }])

    .directive('uibModalWindow', ['$uibModalStack', '$q', '$animate', '$animateCss', '$document',
        function ($modalStack, $q, $animate, $animateCss, $document) {
            return {
                scope: {
                    index: '@'
                },
                replace: true,
                transclude: true,
                templateUrl: function (tElement, tAttrs) {
                    return tAttrs.templateUrl || 'uib/template/modal/window.html';
                },
                link: function (scope, element, attrs) {
                    element.addClass(attrs.windowClass || '');
                    element.addClass(attrs.windowTopClass || '');
                    scope.size = attrs.size;

                    scope.close = function (evt) {
                        var modal = $modalStack.getTop();
                        if (modal && modal.value.backdrop &&
                            modal.value.backdrop !== 'static' &&
                            evt.target === evt.currentTarget) {
                            evt.preventDefault();
                            evt.stopPropagation();
                            $modalStack.dismiss(modal.key, 'backdrop click');
                        }
                    };

                    // moved from template to fix issue #2280
                    element.on('click', scope.close);

                    // This property is only added to the scope for the purpose of detecting when this directive is rendered.
                    // We can detect that by using this property in the template associated with this directive and then use
                    // {@link Attribute#$observe} on it. For more details please see {@link TableColumnResize}.
                    scope.$isRendered = true;

                    // Deferred object that will be resolved when this modal is render.
                    var modalRenderDeferObj = $q.defer();
                    // Observe function will be called on next digest cycle after compilation, ensuring that the DOM is ready.
                    // In order to use this way of finding whether DOM is ready, we need to observe a scope property used in modal's template.
                    attrs.$observe('modalRender', function (value) {
                        if (value === 'true') {
                            modalRenderDeferObj.resolve();
                        }
                    });

                    modalRenderDeferObj.promise.then(function () {
                        var animationPromise = null;

                        if (attrs.modalInClass) {
                            animationPromise = $animateCss(element, {
                                addClass: attrs.modalInClass
                            }).start();

                            scope.$on($modalStack.NOW_CLOSING_EVENT, function (e, setIsAsync) {
                                var done = setIsAsync();
                                if ($animateCss) {
                                    $animateCss(element, {
                                        removeClass: attrs.modalInClass
                                    }).start().then(done);
                                } else {
                                    $animate.removeClass(element, attrs.modalInClass).then(done);
                                }
                            });
                        }


                        $q.when(animationPromise).then(function () {
                            /**
                             * If something within the freshly-opened modal already has focus (perhaps via a
                             * directive that causes focus). then no need to try and focus anything.
                             */
                            if (!($document[0].activeElement && element[0].contains($document[0].activeElement))) {
                                var inputWithAutofocus = element[0].querySelector('[autofocus]');
                                /**
                                 * Auto-focusing of a freshly-opened modal element causes any child elements
                                 * with the autofocus attribute to lose focus. This is an issue on touch
                                 * based devices which will show and then hide the onscreen keyboard.
                                 * Attempts to refocus the autofocus element via JavaScript will not reopen
                                 * the onscreen keyboard. Fixed by updated the focusing logic to only autofocus
                                 * the modal element if the modal does not contain an autofocus element.
                                 */
                                if (inputWithAutofocus) {
                                    inputWithAutofocus.focus();
                                } else {
                                    element[0].focus();
                                }
                            }
                        });

                        // Notify {@link $modalStack} that modal is rendered.
                        var modal = $modalStack.getTop();
                        if (modal) {
                            $modalStack.modalRendered(modal.key);
                        }
                    });
                }
            };
        }])

    .directive('uibModalAnimationClass', function () {
        return {
            compile: function (tElement, tAttrs) {
                if (tAttrs.modalAnimation) {
                    tElement.addClass(tAttrs.uibModalAnimationClass);
                }
            }
        };
    })

    .directive('uibModalTransclude', function () {
        return {
            link: function (scope, element, attrs, controller, transclude) {
                transclude(scope.$parent, function (clone) {
                    element.empty();
                    element.append(clone);
                });
            }
        };
    })

    .factory('$uibModalStack', ['$animate', '$animateCss', '$document',
        '$compile', '$rootScope', '$q', '$$multiMap', '$$stackedMap',
        function ($animate, $animateCss, $document, $compile, $rootScope, $q, $$multiMap, $$stackedMap) {
            var OPENED_MODAL_CLASS = 'modal-open';

            var backdropDomEl, backdropScope;
            var openedWindows = $$stackedMap.createNew();
            var openedClasses = $$multiMap.createNew();
            var $modalStack = {
                NOW_CLOSING_EVENT: 'modal.stack.now-closing'
            };

            //Modal focus behavior
            var focusableElementList;
            var focusIndex = 0;
            var tababbleSelector = 'a[href], area[href], input:not([disabled]), ' +
                'button:not([disabled]),select:not([disabled]), textarea:not([disabled]), ' +
                'iframe, object, embed, *[tabindex], *[contenteditable=true]';

            function backdropIndex() {
                var topBackdropIndex = -1;
                var opened = openedWindows.keys();
                for (var i = 0; i < opened.length; i++) {
                    if (openedWindows.get(opened[i]).value.backdrop) {
                        topBackdropIndex = i;
                    }
                }
                return topBackdropIndex;
            }

            $rootScope.$watch(backdropIndex, function (newBackdropIndex) {
                if (backdropScope) {
                    backdropScope.index = newBackdropIndex;
                }
            });

            function removeModalWindow(modalInstance, elementToReceiveFocus) {
                var modalWindow = openedWindows.get(modalInstance).value;
                var appendToElement = modalWindow.appendTo;

                //clean up the stack
                openedWindows.remove(modalInstance);

                removeAfterAnimate(modalWindow.modalDomEl, modalWindow.modalScope, function () {
                    var modalBodyClass = modalWindow.openedClass || OPENED_MODAL_CLASS;
                    openedClasses.remove(modalBodyClass, modalInstance);
                    appendToElement.toggleClass(modalBodyClass, openedClasses.hasKey(modalBodyClass));
                    toggleTopWindowClass(true);
                }, modalWindow.closedDeferred);
                checkRemoveBackdrop();

                //move focus to specified element if available, or else to body
                if (elementToReceiveFocus && elementToReceiveFocus.focus) {
                    elementToReceiveFocus.focus();
                } else if (appendToElement.focus) {
                    appendToElement.focus();
                }
            }

            // Add or remove "windowTopClass" from the top window in the stack
            function toggleTopWindowClass(toggleSwitch) {
                var modalWindow;

                if (openedWindows.length() > 0) {
                    modalWindow = openedWindows.top().value;
                    modalWindow.modalDomEl.toggleClass(modalWindow.windowTopClass || '', toggleSwitch);
                }
            }

            function checkRemoveBackdrop() {
                //remove backdrop if no longer needed
                if (backdropDomEl && backdropIndex() === -1) {
                    var backdropScopeRef = backdropScope;
                    removeAfterAnimate(backdropDomEl, backdropScope, function () {
                        backdropScopeRef = null;
                    });
                    backdropDomEl = undefined;
                    backdropScope = undefined;
                }
            }

            function removeAfterAnimate(domEl, scope, done, closedDeferred) {
                var asyncDeferred;
                var asyncPromise = null;
                var setIsAsync = function () {
                    if (!asyncDeferred) {
                        asyncDeferred = $q.defer();
                        asyncPromise = asyncDeferred.promise;
                    }

                    return function asyncDone() {
                        asyncDeferred.resolve();
                    };
                };
                scope.$broadcast($modalStack.NOW_CLOSING_EVENT, setIsAsync);

                // Note that it's intentional that asyncPromise might be null.
                // That's when setIsAsync has not been called during the
                // NOW_CLOSING_EVENT broadcast.
                return $q.when(asyncPromise).then(afterAnimating);

                function afterAnimating() {
                    if (afterAnimating.done) {
                        return;
                    }
                    afterAnimating.done = true;

                    $animateCss(domEl, {
                        event: 'leave'
                    }).start().then(function () {
                        domEl.remove();
                        if (closedDeferred) {
                            closedDeferred.resolve();
                        }
                    });

                    scope.$destroy();
                    if (done) {
                        done();
                    }
                }
            }

            $document.on('keydown', keydownListener);

            $rootScope.$on('$destroy', function () {
                $document.off('keydown', keydownListener);
            });

            function keydownListener(evt) {
                if (evt.isDefaultPrevented()) {
                    return evt;
                }

                var modal = openedWindows.top();
                if (modal) {
                    switch (evt.which) {
                        case 27:
                        {
                            if (modal.value.keyboard) {
                                evt.preventDefault();
                                $rootScope.$apply(function () {
                                    $modalStack.dismiss(modal.key, 'escape key press');
                                });
                            }
                            break;
                        }
                        case 9:
                        {
                            $modalStack.loadFocusElementList(modal);
                            var focusChanged = false;
                            if (evt.shiftKey) {
                                if ($modalStack.isFocusInFirstItem(evt)) {
                                    focusChanged = $modalStack.focusLastFocusableElement();
                                }
                            } else {
                                if ($modalStack.isFocusInLastItem(evt)) {
                                    focusChanged = $modalStack.focusFirstFocusableElement();
                                }
                            }

                            if (focusChanged) {
                                evt.preventDefault();
                                evt.stopPropagation();
                            }
                            break;
                        }
                    }
                }
            }

            $modalStack.open = function (modalInstance, modal) {
                var modalOpener = $document[0].activeElement,
                    modalBodyClass = modal.openedClass || OPENED_MODAL_CLASS;

                toggleTopWindowClass(false);

                openedWindows.add(modalInstance, {
                    deferred: modal.deferred,
                    renderDeferred: modal.renderDeferred,
                    closedDeferred: modal.closedDeferred,
                    modalScope: modal.scope,
                    backdrop: modal.backdrop,
                    keyboard: modal.keyboard,
                    openedClass: modal.openedClass,
                    windowTopClass: modal.windowTopClass,
                    animation: modal.animation,
                    appendTo: modal.appendTo
                });

                openedClasses.put(modalBodyClass, modalInstance);

                var appendToElement = modal.appendTo,
                    currBackdropIndex = backdropIndex();

                if (!appendToElement.length) {
                    throw new Error('appendTo element not found. Make sure that the element passed is in DOM.');
                }

                if (currBackdropIndex >= 0 && !backdropDomEl) {
                    backdropScope = $rootScope.$new(true);
                    backdropScope.modalOptions = modal;
                    backdropScope.index = currBackdropIndex;
                    backdropDomEl = angular.element('<div uib-modal-backdrop="modal-backdrop"></div>');
                    backdropDomEl.attr('backdrop-class', modal.backdropClass);
                    if (modal.animation) {
                        backdropDomEl.attr('modal-animation', 'true');
                    }
                    $compile(backdropDomEl)(backdropScope);
                    $animate.enter(backdropDomEl, appendToElement);
                }

                var angularDomEl = angular.element('<div uib-modal-window="modal-window"></div>');
                angularDomEl.attr({
                    'template-url': modal.windowTemplateUrl,
                    'window-class': modal.windowClass,
                    'window-top-class': modal.windowTopClass,
                    'size': modal.size,
                    'index': openedWindows.length() - 1,
                    'animate': 'animate'
                }).html(modal.content);
                if (modal.animation) {
                    angularDomEl.attr('modal-animation', 'true');
                }

                $animate.enter($compile(angularDomEl)(modal.scope), appendToElement)
                    .then(function () {
                        $animate.addClass(appendToElement, modalBodyClass);
                    });

                openedWindows.top().value.modalDomEl = angularDomEl;
                openedWindows.top().value.modalOpener = modalOpener;

                $modalStack.clearFocusListCache();
            };

            function broadcastClosing(modalWindow, resultOrReason, closing) {
                return !modalWindow.value.modalScope.$broadcast('modal.closing', resultOrReason, closing).defaultPrevented;
            }

            $modalStack.close = function (modalInstance, result) {
                var modalWindow = openedWindows.get(modalInstance);
                if (modalWindow && broadcastClosing(modalWindow, result, true)) {
                    modalWindow.value.modalScope.$$uibDestructionScheduled = true;
                    modalWindow.value.deferred.resolve(result);
                    removeModalWindow(modalInstance, modalWindow.value.modalOpener);
                    return true;
                }
                return !modalWindow;
            };

            $modalStack.dismiss = function (modalInstance, reason) {
                var modalWindow = openedWindows.get(modalInstance);
                if (modalWindow && broadcastClosing(modalWindow, reason, false)) {
                    modalWindow.value.modalScope.$$uibDestructionScheduled = true;
                    modalWindow.value.deferred.reject(reason);
                    removeModalWindow(modalInstance, modalWindow.value.modalOpener);
                    return true;
                }
                return !modalWindow;
            };

            $modalStack.dismissAll = function (reason) {
                var topModal = this.getTop();
                while (topModal && this.dismiss(topModal.key, reason)) {
                    topModal = this.getTop();
                }
            };

            $modalStack.getTop = function () {
                return openedWindows.top();
            };

            $modalStack.modalRendered = function (modalInstance) {
                var modalWindow = openedWindows.get(modalInstance);
                if (modalWindow) {
                    modalWindow.value.renderDeferred.resolve();
                }
            };

            $modalStack.focusFirstFocusableElement = function () {
                if (focusableElementList.length > 0) {
                    focusableElementList[0].focus();
                    return true;
                }
                return false;
            };
            $modalStack.focusLastFocusableElement = function () {
                if (focusableElementList.length > 0) {
                    focusableElementList[focusableElementList.length - 1].focus();
                    return true;
                }
                return false;
            };

            $modalStack.isFocusInFirstItem = function (evt) {
                if (focusableElementList.length > 0) {
                    return (evt.target || evt.srcElement) === focusableElementList[0];
                }
                return false;
            };

            $modalStack.isFocusInLastItem = function (evt) {
                if (focusableElementList.length > 0) {
                    return (evt.target || evt.srcElement) === focusableElementList[focusableElementList.length - 1];
                }
                return false;
            };

            $modalStack.clearFocusListCache = function () {
                focusableElementList = [];
                focusIndex = 0;
            };

            $modalStack.loadFocusElementList = function (modalWindow) {
                if (focusableElementList === undefined || !focusableElementList.length) {
                    if (modalWindow) {
                        var modalDomE1 = modalWindow.value.modalDomEl;
                        if (modalDomE1 && modalDomE1.length) {
                            focusableElementList = modalDomE1[0].querySelectorAll(tababbleSelector);
                        }
                    }
                }
            };

            return $modalStack;
        }])

    .provider('$uibModal', function () {
        var $modalProvider = {
            options: {
                animation: true,
                backdrop: true, //can also be false or 'static'
                keyboard: true
            },
            $get: ['$rootScope', '$q', '$document', '$templateRequest', '$controller', '$uibResolve', '$uibModalStack',
                function ($rootScope, $q, $document, $templateRequest, $controller, $uibResolve, $modalStack) {
                    var $modal = {};

                    function getTemplatePromise(options) {
                        return options.template ? $q.when(options.template) :
                            $templateRequest(angular.isFunction(options.templateUrl) ?
                                options.templateUrl() : options.templateUrl);
                    }

                    var promiseChain = null;
                    $modal.getPromiseChain = function () {
                        return promiseChain;
                    };

                    $modal.open = function (modalOptions) {
                        var modalResultDeferred = $q.defer();
                        var modalOpenedDeferred = $q.defer();
                        var modalClosedDeferred = $q.defer();
                        var modalRenderDeferred = $q.defer();

                        //prepare an instance of a modal to be injected into controllers and returned to a caller
                        var modalInstance = {
                            result: modalResultDeferred.promise,
                            opened: modalOpenedDeferred.promise,
                            closed: modalClosedDeferred.promise,
                            rendered: modalRenderDeferred.promise,
                            close: function (result) {
                                return $modalStack.close(modalInstance, result);
                            },
                            dismiss: function (reason) {
                                return $modalStack.dismiss(modalInstance, reason);
                            }
                        };

                        //merge and clean up options
                        modalOptions = angular.extend({}, $modalProvider.options, modalOptions);
                        modalOptions.resolve = modalOptions.resolve || {};
                        modalOptions.appendTo = modalOptions.appendTo || $document.find('body').eq(0);

                        //verify options
                        if (!modalOptions.template && !modalOptions.templateUrl) {
                            throw new Error('One of template or templateUrl options is required.');
                        }

                        var templateAndResolvePromise =
                            $q.all([getTemplatePromise(modalOptions), $uibResolve.resolve(modalOptions.resolve, {}, null, null)]);

                        function resolveWithTemplate() {
                            return templateAndResolvePromise;
                        }

                        // Wait for the resolution of the existing promise chain.
                        // Then switch to our own combined promise dependency (regardless of how the previous modal fared).
                        // Then add to $modalStack and resolve opened.
                        // Finally clean up the chain variable if no subsequent modal has overwritten it.
                        var samePromise;
                        samePromise = promiseChain = $q.all([promiseChain])
                            .then(resolveWithTemplate, resolveWithTemplate)
                            .then(function resolveSuccess(tplAndVars) {
                                var providedScope = modalOptions.scope || $rootScope;

                                var modalScope = providedScope.$new();
                                modalScope.$close = modalInstance.close;
                                modalScope.$dismiss = modalInstance.dismiss;

                                modalScope.$on('$destroy', function () {
                                    if (!modalScope.$$uibDestructionScheduled) {
                                        modalScope.$dismiss('$uibUnscheduledDestruction');
                                    }
                                });

                                var ctrlInstance, ctrlLocals = {};

                                //controllers
                                if (modalOptions.controller) {
                                    ctrlLocals.$scope = modalScope;
                                    ctrlLocals.$uibModalInstance = modalInstance;
                                    angular.forEach(tplAndVars[1], function (value, key) {
                                        ctrlLocals[key] = value;
                                    });

                                    ctrlInstance = $controller(modalOptions.controller, ctrlLocals);
                                    if (modalOptions.controllerAs) {
                                        if (modalOptions.bindToController) {
                                            ctrlInstance.$close = modalScope.$close;
                                            ctrlInstance.$dismiss = modalScope.$dismiss;
                                            angular.extend(ctrlInstance, providedScope);
                                        }

                                        modalScope[modalOptions.controllerAs] = ctrlInstance;
                                    }
                                }

                                $modalStack.open(modalInstance, {
                                    scope: modalScope,
                                    deferred: modalResultDeferred,
                                    renderDeferred: modalRenderDeferred,
                                    closedDeferred: modalClosedDeferred,
                                    content: tplAndVars[0],
                                    animation: modalOptions.animation,
                                    backdrop: modalOptions.backdrop,
                                    keyboard: modalOptions.keyboard,
                                    backdropClass: modalOptions.backdropClass,
                                    windowTopClass: modalOptions.windowTopClass,
                                    windowClass: modalOptions.windowClass,
                                    windowTemplateUrl: modalOptions.windowTemplateUrl,
                                    size: modalOptions.size,
                                    openedClass: modalOptions.openedClass,
                                    appendTo: modalOptions.appendTo
                                });
                                modalOpenedDeferred.resolve(true);

                            }, function resolveError(reason) {
                                modalOpenedDeferred.reject(reason);
                                modalResultDeferred.reject(reason);
                            })['finally'](function () {
                            if (promiseChain === samePromise) {
                                promiseChain = null;
                            }
                        });

                        return modalInstance;
                    };

                    return $modal;
                }
            ]
        };

        return $modalProvider;
    });

angular.module("uib/template/modal/backdrop.html", []).run(["$templateCache", function ($templateCache) {
    $templateCache.put("uib/template/modal/backdrop.html",
        "<div class=\"modal-backdrop\"\n" +
        "     uib-modal-animation-class=\"fade\"\n" +
        "     modal-in-class=\"in\"\n" +
        "     ng-style=\"{'z-index': 1040 + (index && 1 || 0) + index*10}\"\n" +
        "></div>\n" +
        "");
}]);

angular.module("uib/template/modal/window.html", []).run(["$templateCache", function ($templateCache) {
    $templateCache.put("uib/template/modal/window.html",
        "<div modal-render=\"{{$isRendered}}\" tabindex=\"-1\" role=\"dialog\" class=\"modal\"\n" +
        "    uib-modal-animation-class=\"fade\"\n" +
        "    modal-in-class=\"in\"\n" +
        "    ng-style=\"{'z-index': 1050 + index*10, display: 'block'}\">\n" +
        "    <div class=\"modal-dialog\" ng-class=\"size ? 'modal-' + size : ''\"><div class=\"modal-content\" uib-modal-transclude></div></div>\n" +
        "</div>\n" +
        "");
}]);