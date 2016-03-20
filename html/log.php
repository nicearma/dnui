<div id="logDNUI" ng-controller="LogCtrl">

<h3><?php _e('Logs', 'dnui') ?></h3>

    <p ng-if="logs.length==0"><?php _e('Logs are empty...', 'dnui') ?></p>

    <uib-accordion close-others="oneAtATime">
        <uib-accordion-group heading="{{log.type}}" ng-repeat="log in logs">
            {{log.message}}
        </uib-accordion-group>
        </uib-accordion>

</div>