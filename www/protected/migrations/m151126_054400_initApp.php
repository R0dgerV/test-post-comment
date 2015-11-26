<?php

class m151126_054400_initApp extends CDbMigration
{
	public function safeUp()
	{
		$now = new CDbExpression('NOW()');

		$this->createTable('Authors',
			[
				'id'        => 'int(11) unsigned NOT NULL AUTO_INCREMENT',
				'email'		=> 'varchar(255) NOT NULL',
				'name'		=> 'varchar(255) NOT NULL',
				'created'   => 'datetime NOT NULL',
				'modified'  => 'datetime NOT NULL',
				'flags'     => 'int(11) unsigned DEFAULT 0',
				'KEY created (created)',
				'KEY modified (modified)',
				'KEY flags (flags)',
				'PRIMARY KEY (id)',
			],
			'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci'
		);

 		$this->insert('Authors', ['email'=>'test@test.ru', 'name'=>'Test User 1', 'created'=>$now, 'modified'=>$now]);
		$this->insert('Authors', ['email'=>'admin@test.ru', 'name'=>'Admin User', 'created'=>$now, 'modified'=>$now]);

		$this->createTable('Pages',
			[
				'id'		=> 'int(11) unsigned NOT NULL AUTO_INCREMENT',
				'title'		=> 'varchar(255) NOT NULL',
				'text'		=> 'text NOT NULL',
				'authorId'	=> 'int(11) unsigned NOT NULL',
				'created'   => 'datetime NOT NULL',
				'modified'  => 'datetime NOT NULL',
				'flags'     => 'int(11) unsigned DEFAULT 0',
				'KEY created (created)',
				'KEY modified (modified)',
				'KEY authorId (authorId)',
				'KEY flags (flags)',
				'PRIMARY KEY (id)',

			],
			'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci'
		);

		$this->addForeignKey('Pages_ibfk_authorId',  'Pages', 'authorId', 'Authors', 'id', 'CASCADE', 'CASCADE');

		$this->insert('Pages', [
			'title'=>'Blog Post Title',
			'text'=>'<p class="lead">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Ducimus, vero, obcaecati, aut, error quam sapiente nemo saepe quibusdam sit excepturi nam quia corporis eligendi eos magni recusandae laborum minus inventore?</p>
				<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Ut, tenetur natus doloremque laborum quos iste ipsum rerum obcaecati impedit odit illo dolorum ab tempora nihil dicta earum fugiat. Temporibus, voluptatibus.</p>
				<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Eos, doloribus, dolorem iusto blanditiis unde eius illum consequuntur neque dicta incidunt ullam ea hic porro optio ratione repellat perspiciatis. Enim, iure!</p>
				<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Error, nostrum, aliquid, animi, ut quas placeat totam sunt tempora commodi nihil ullam alias modi dicta saepe minima ab quo voluptatem obcaecati?</p>
				<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Harum, dolor quis. Sunt, ut, explicabo, aliquam tenetur ratione tempore quidem voluptates cupiditate voluptas illo saepe quaerat numquam recusandae? Qui, necessitatibus, est!</p>',
			'authorId'=>2,
			'created'=>$now, 'modified'=>$now]);

		$this->createTable('Posts', [
				'id'		=> 'int(11) unsigned NOT NULL AUTO_INCREMENT',
				'text' 		=> 'text NOT NULL',
				'authorId'  => 'int(11) unsigned NOT NULL',
				'pageId' 	=> 'int(11) unsigned NOT NULL',
				'lft'		=> 'int(11) unsigned NOT NULL',
				'rgt'		=> 'int(11) unsigned NOT NULL',
	            'root'		=> 'int(11) unsigned DEFAULT NULL',
				'level'		=> 'SMALLINT(5) unsigned NOT NULL',
				'created'   => 'datetime NOT NULL',
				'modified'  => 'datetime NOT NULL',
				'flags'     => 'int(11) unsigned DEFAULT 0',
				'KEY created (created)',
				'KEY modified (modified)',
				'KEY flags (flags)',
				'KEY lft (lft)',
				'KEY rgt (rgt)',
  				'KEY root (root)',
				'KEY level (level)',
				'KEY authorId (authorId)',
				'KEY pageId (pageId)',
				'PRIMARY KEY (id)',
			],
			'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci'
		);
		$this->addForeignKey('Posts_ibfk_authorId',  'Posts', 'authorId', 'Authors', 'id', 'CASCADE', 'CASCADE');
		$this->addForeignKey('Posts_ibfk_pageId',  'Posts', 'pageId', 'Pages', 'id', 'CASCADE', 'CASCADE');


	}

	public function safeDown()
	{
		$this->dropTable('Pages');
		$this->dropTable('Authors');
		$this->dropTable('Posts');

	}
}