<?php

use yii\db\Migration;

/**
 * Class m171225_173413_numbers
 */
class m171225_173413_numbers extends Migration
{
	/**
	 * @inheritdoc
	 */
	public function safeUp()
	{

		$this->createTable('numbers',[
			'id' => $this->primaryKey(),
			'number_val' => $this->string(30),
			'contact_id' => $this->integer()
		]);
		//$this->alterColumn('numbers','id', $this->integer() . 'NOT NULL AUTO_INCREMENT');

		$this->createIndex('idx-numbers-id',
			'numbers',
			'contact_id');

		$this->addForeignKey(
			'numbers_to_contact',
			'numbers',
			'contact_id',
			'contacts',
			'id',
			'CASCADE');

		$sql = "CREATE TRIGGER `check_rows_before_insert` BEFORE INSERT ON `numbers`
				FOR EACH ROW
					BEGIN
				DECLARE num_rows integer DEFAULT 0;
				SELECT COUNT(*) INTO num_rows FROM numbers
				WHERE contact_id = NEW.contact_id;
					IF(num_rows >= 10) THEN
						SIGNAL SQLSTATE '45000'
      	                SET MESSAGE_TEXT = 'error insert';
  	                END IF;
				END;";
		$this->execute($sql);
	}

	/**
	 * @inheritdoc
	 */
	public function safeDown()
	{
		$sql = "DROP TRIGGER IF EXISTS `check_rows_before_insert`;";
		$this->execute($sql);
		$this->dropTable('numbers');
	}

	/*
	// Use up()/down() to run migration code without a transaction.
	public function up()
	{

	}

	public function down()
	{
		echo "m171221_203214_numbers cannot be reverted.\n";

		return false;
	}
	*/
}