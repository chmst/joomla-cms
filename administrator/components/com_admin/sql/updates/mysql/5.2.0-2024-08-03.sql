--
-- The featured View is integrated into articles view in Joomla 5.2.0
--

UPDATE `#__menu`
SET `link` = 'index.php?option=com_content&view=articles&featured=1'
WHERE `link` = 'index.php?option=com_content&view=featured'
AND `client_id` = 1;
