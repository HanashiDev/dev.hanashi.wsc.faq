<?php
namespace wcf\data\faq;
use wcf\data\DatabaseObjectEditor;
use wcf\system\WCF;

class QuestionEditor extends DatabaseObjectEditor {
	
	/**
	 * @inheritDoc
	 */
	protected static $baseClass = Question::class;
	
	/**
	 * Returns the new show order for a object
	 *
	 * @param integer	$showOrder
	 *
	 * @return integer
	 */
	public function updateShowOrder($showOrder) {
		if ($showOrder === null) {
			$showOrder = PHP_INT_MAX;
		}

		//check showOrder
		if ($showOrder < $this->showOrder) {
			$sql = "UPDATE	" . static::getDatabaseTableName() . "
				SET	showOrder = showOrder + 1
				WHERE	showOrder >= ?
				AND	 showOrder < ?";
			$statement = WCF::getDB()->prepareStatement($sql);
			$statement->execute([
				$showOrder,
				$this->showOrder,
			]);
		} else if ($showOrder > $this->showOrder) {
			//get max show order
			$maxShowOrder = self::getShowOrder() - 1;

			//get show order
			if ($showOrder > $maxShowOrder) {
				$showOrder = $maxShowOrder;
			}

			//update databse
			$sql = "UPDATE	" . static::getDatabaseTableName() . "
				SET	showOrder = showOrder - 1
				WHERE	showOrder <= ?
				AND	 showOrder > ?";
			$statement = WCF::getDB()->prepareStatement($sql);
			$statement->execute([
				$showOrder,
				$this->showOrder,
			]);
		}

		//return show order
		return $showOrder;
	}

	/**
	 * Returns the show order for a new object
	 *
	 * @return integer
	 */
	public static function getShowOrder() {
		$sql = "SELECT MAX(showOrder) AS showOrder
			FROM " . static::getDatabaseTableName();
		$statement = WCF::getDB()->prepareStatement($sql);
		$statement->execute();
		$row = $statement->fetchArray();

		return (!empty($row) ? ($row['showOrder'] + 1) : 1);
	}
}
