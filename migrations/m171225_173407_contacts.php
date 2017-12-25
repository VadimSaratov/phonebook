<?php

use yii\db\Migration;

/**
 * Class m171225_173407_contacts
 */
class m171225_173407_contacts extends Migration
{
	/**
	 * @inheritdoc
	 */
	public function safeUp()
	{

		$this->createTable('contacts',[
			'id' => $this->primaryKey(),
			'name' => $this->string(15)->notNull(),
			'surname' => $this->string(30),
			'patronymic' => $this->string(30),
		]);
		//$this->alterColumn('contacts','id', $this->integer().'NOT NULL AUTO_INCREMENT');


	}

	/**
	 * @inheritdoc
	 */
	public function safeDown()
	{
		$this->dropTable('contacts');
	}

	/*
	// Use up()/down() to run migration code without a transaction.
	public function up()
	{

	}

	public function down()
	{
		echo "m171221_202446_contacts cannot be reverted.\n";

		return false;
	}
	*/
}