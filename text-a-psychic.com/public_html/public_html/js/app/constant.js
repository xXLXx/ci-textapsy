/**
 * Angular Dependency: f45-playbook.constants
 *
 * This file contains variable constants.         
 *
 * CAUTION: Don't edit/remove the constants, unless you know what you're doing.
 */
(function() {
    
    'use strict';

    angular.module('txtapsy', [])
        .constant('TXTAPSY_API', {    
            AVAILABLE_INBOUND_MSG : 'inbound_messages/available',
            ACCEPT_INBOUND_MSG	  : 'inbound_messages/accept_message',
            DECLINE_INBOUND_MSG   : 'inbound_messages/decline_message',
            SEND_MESSAGE          : 'txtnation/send_message'
        })

})();