<div id="logDNUI" ng-controller="LogCtrl">

<h3>Logs</h3>
    <uib-accordion close-others="oneAtATime">
        <uib-accordion-group heading="{{log.type}}" ng-repeat="log in logs">
            {{log.message}}
        </uib-accordion-group>
        </uib-accordion>

<!--
    <h3>Errors</h3>
    <uib-accordion close-others="oneAtATime">
        <uib-accordion-group heading="{{error.type}}" ng-repeat="error in errors">
            {{error.message}}
        </uib-accordion-group>
    </uib-accordion>
-->
</div>