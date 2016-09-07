<div id="logDNUI" ng-controller="LogCtrl">

<h3><?php _e('Logs','dnui-delete-not-used-image-wordpress'); ?></h3>

    <p ng-if="logs.length==0"><?php _e('Logs are empty...','dnui-delete-not-used-image-wordpress'); ?></p>

    <uib-accordion close-others="oneAtATime">
        <uib-accordion-group heading="{{log.type}}" ng-repeat="log in logs">
            {{log.message}}
        </uib-accordion-group>
        </uib-accordion>

</div>