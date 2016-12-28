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
            ALL_INBOUND_MSG 	  : 'inbound_messages/all',
            PENDING_INBOUND_MSG   : 'inbound_messages/pending',
            ACCEPT_INBOUND_MSG	  : 'inbound_messages/accept_message',
            DECLINE_INBOUND_MSG   : 'inbound_messages/decline_message',
            SEND_MESSAGE          : 'txtnation/send_message',
            RESOLVED_MSG		  : 'inbound_messages/resolved_messages/',
            ALL_PSYCHIC           : 'psychics/psychic/',
            ALL_PSYCHICS          : 'psychics/psychic_all'
        })

})();