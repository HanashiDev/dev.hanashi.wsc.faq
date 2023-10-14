<?php

use wcf\system\WCF;

$sql = "
    UPDATE      wcf1_faq_questions
    SET         isMultilingual = 1
    WHERE       answer LIKE ?
";
$statement = WCF::getDB()->prepare($sql);
$statement->execute(['wcf.faq.question.answer%']);
