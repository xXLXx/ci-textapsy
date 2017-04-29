INSERT INTO `vadmin_navsub` (`nav_id`, `title`, `table`) VALUES (19, 'Clients', 'inbound_messages');

ALTER TABLE `vadmin_specs` ADD COLUMN `navsub_id` INT(11) UNSIGNED DEFAULT NULL AFTER `table`;

INSERT INTO `vadmin_specs` (`table`, `navsub_id`, `type`, `value`) VALUES('inbound_messages', 37, 'hide_fields', 'txtnation_msg_id,message,shortcode,responded_by,status');

INSERT INTO `vadmin_specs` (`table`, `navsub_id`, `type`, `value`) VALUES('inbound_messages', 37, 'group_by', 'number');