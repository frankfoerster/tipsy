<?php
use Cake\Cache\Cache;
use Cake\ORM\TableRegistry;
use Migrations\AbstractMigration;
use Migrations\Table;
use Phinx\Db\Table\Column;

class InitializeTipsy extends AbstractMigration
{
    public function init()
    {
        parent::init();

        Cache::clear();
    }

    /**
     * Migrate up
     */
    public function up()
    {
        $this->_createUsers();
        $this->_createUserGroups();
        $this->_createTokens();
        $this->_createGroups();
        $this->_createTeams();
        $this->_createGames();
        $this->_createTips();

        Cache::clear();
    }

    /**
     * Migrate down
     */
    public function down()
    {
        $this->_downAll();

        Cache::clear();
    }

    protected function _createUsers()
    {
        $this->output->write(__FUNCTION__ . "\r\n");

        $table = $this->table('users');
        $table = $this->_addFK($table, 'user_group_id');
        $table
            ->addColumn('username', 'string', ['length' => 255, 'null' => false])
            ->addColumn('email', 'string', ['length' => 255, 'null' => false])
            ->addColumn('password', 'char', ['length' => 60, 'null' => false])
            ->addColumn('verified', 'boolean', ['signed' => false, 'null' => false, 'default' => 0]);
        $table = $this->_addFK($table, 'winning_team_id', true);
        $table
            ->addColumn('created', 'datetime', ['null' => false, 'default' => 'CURRENT_TIMESTAMP'])
            ->addColumn('modified', 'datetime', ['null' => false, 'default' => 'CURRENT_TIMESTAMP']);

        $table
            ->addIndex('username', ['name' => 'UQ_USERNAME', 'unique' => true])
            ->addIndex('email', ['name' => 'UQ_EMAIL', 'unique' => true])
            ->addIndex('verified', ['name' => 'BY_VERIFIED', 'unique' => false]);

        $table->create();

        $id = new Column();
        $id->setIdentity(true)
            ->setType('integer')
            ->setOptions(['limit' => 11, 'signed' => false, 'null' => false]);

        $table->changeColumn('id', $id)->save();
    }

    protected function _createUserGroups()
    {
        $this->output->write(__FUNCTION__ . "\r\n");

        $table = $this->table('user_groups');
        $table->addColumn('name', 'string', ['length' => 255, 'null' => false]);
        $table->create();

        $id = new Column();
        $id->setIdentity(true)
            ->setType('integer')
            ->setOptions(['limit' => 11, 'signed' => false, 'null' => false]);

        $table->changeColumn('id', $id)->save();
    }

    protected function _createTokens()
    {
        $this->output->write(__FUNCTION__ . "\r\n");

        $table = $this->table('tokens');
        $table = $this->_addFK($table, 'user_id');
        $table
            ->addColumn('token', 'char', ['length' => 64, 'null' => false])
            ->addColumn('type', 'string', ['length' => 255, 'null' => false])
            ->addColumn('expires', 'datetime', ['null' => true, 'default' => null])
            ->addColumn('force_expired', 'boolean', ['signed' => false, 'null' => false, 'default' => 0]);
        $table
            ->addColumn('created', 'datetime', ['null' => false, 'default' => 'CURRENT_TIMESTAMP'])
            ->addColumn('modified', 'datetime', ['null' => false, 'default' => 'CURRENT_TIMESTAMP']);

        $table
            ->addIndex('token', ['name' => 'UQ_TOKEN', 'unique' => true])
            ->addIndex('force_expired', ['name' => 'BY_FORCE_EXPIRED', 'unique' => false]);

        $table->create();

        $id = new Column();
        $id->setIdentity(true)
            ->setType('integer')
            ->setOptions(['limit' => 11, 'signed' => false, 'null' => false]);

        $table->changeColumn('id', $id)->save();
    }

    protected function _createGroups()
    {
        $this->output->write(__FUNCTION__ . "\r\n");

        $table = $this->table('groups');
        $table->addColumn('name', 'string', ['length' => 255, 'null' => false]);

        $table->addIndex('name', ['name' => 'UQ_NAME', 'unique' => true]);

        $table->create();

        $id = new Column();
        $id->setIdentity(true)
            ->setType('integer')
            ->setOptions(['limit' => 11, 'signed' => false, 'null' => false]);

        $table->changeColumn('id', $id)->save();
    }

    protected function _createTeams()
    {
        $this->output->write(__FUNCTION__ . "\r\n");

        $table = $this->table('teams');
        $table = $this->_addFK($table, 'group_id', true);
        $table
            ->addColumn('name', 'string', ['length' => 255, 'null' => false])
            ->addColumn('icon', 'string', ['length' => 255, 'null' => true, 'default' => null]);

        $table->addIndex('name', ['name' => 'UQ_NAME', 'unique' => true]);

        $table->create();

        $id = new Column();
        $id->setIdentity(true)
            ->setType('integer')
            ->setOptions(['limit' => 11, 'signed' => false, 'null' => false]);

        $table->changeColumn('id', $id)->save();
    }

    protected function _createGames()
    {
        $this->output->write(__FUNCTION__ . "\r\n");

        $table = $this->table('games');
        $table = $this->_addFK($table, 'team1_id', false);
        $table = $this->_addFK($table, 'team2_id', false);
        $table
            ->addColumn('result1', 'integer', ['length' => 11, 'signed' => false, 'null' => true, 'default' => null])
            ->addColumn('result2', 'integer', ['length' => 11, 'signed' => false, 'null' => true, 'default' => null])
            ->addColumn('team1_note', 'string', ['length' => 255, 'null' => true, 'default' => null])
            ->addColumn('team2_note', 'string', ['length' => 255, 'null' => true, 'default' => null])
            ->addColumn('playing_time', 'datetime', ['null' => false])
            ->addColumn('is_preliminary', 'boolean', ['signed' => false, 'null' => false, 'default' => 0])
            ->addColumn('is_last_sixteen', 'boolean', ['signed' => false, 'null' => false, 'default' => 0])
            ->addColumn('is_quarter_final', 'boolean', ['signed' => false, 'null' => false, 'default' => 0])
            ->addColumn('is_semi_final', 'boolean', ['signed' => false, 'null' => false, 'default' => 0])
            ->addColumn('is_game_for_3rd_place', 'boolean', ['signed' => false, 'null' => false, 'default' => 0])
            ->addColumn('is_final', 'boolean', ['signed' => false, 'null' => false, 'default' => 0]);

        $table
            ->addIndex('is_preliminary', ['name' => 'BY_IS_PRELIMINARY', 'unique' => false])
            ->addIndex('is_last_sixteen', ['name' => 'BY_IS_LAST_SIXTEEN', 'unique' => false])
            ->addIndex('is_quarter_final', ['name' => 'BY_IS_QUARTER_FINAL', 'unique' => false])
            ->addIndex('is_semi_final', ['name' => 'BY_IS_SEMI_FINAL', 'unique' => false])
            ->addIndex('is_game_for_3rd_place', ['name' => 'BY_IS_GAME_FOR_THIRD_PLACE', 'unique' => false])
            ->addIndex('is_final', ['name' => 'BY_IS_FINAL', 'unique' => false]);

        $table->create();

        $id = new Column();
        $id->setIdentity(true)
            ->setType('integer')
            ->setOptions(['limit' => 11, 'signed' => false, 'null' => false]);

        $table->changeColumn('id', $id)->save();
    }

    protected function _createTips()
    {
        $this->output->write(__FUNCTION__ . "\r\n");

        $table = $this->table('tips');
        $table = $this->_addFK($table, 'game_id');
        $table = $this->_addFK($table, 'user_id');
        $table
            ->addColumn('result1', 'integer', ['length' => 11, 'signed' => false, 'null' => false])
            ->addColumn('result2', 'integer', ['length' => 11, 'signed' => false, 'null' => false]);
        $table
            ->addColumn('created', 'datetime', ['null' => false, 'default' => 'CURRENT_TIMESTAMP'])
            ->addColumn('modified', 'datetime', ['null' => false, 'default' => 'CURRENT_TIMESTAMP']);

        $table->create();

        $id = new Column();
        $id->setIdentity(true)
            ->setType('integer')
            ->setOptions(['limit' => 11, 'signed' => false, 'null' => false]);

        $table->changeColumn('id', $id)->save();
    }

    protected function _downAll()
    {
        $this->output->write(__FUNCTION__ . "\r\n");

        $this->dropTable('tips');
        $this->dropTable('games');
        $this->dropTable('teams');
        $this->dropTable('groups');
        $this->dropTable('tokens');
        $this->dropTable('user_groups');
        $this->dropTable('users');
    }

    /**
     * Add an fk id.
     *
     * @param \Phinx\Db\Table|Table $table
     * @param string $field
     * @param bool $nullAllowed
     * @return \Phinx\Db\Table|Table
     */
    protected function _addFK($table, $field, $nullAllowed = false)
    {
        return $table
            ->addColumn($field, 'integer', ['limit' => 11, 'signed' => false, 'null' => $nullAllowed, 'default' => $nullAllowed ? null : 0])
            ->addIndex($field, ['name' => 'FK_' . strtoupper($field), 'unique' => false]);
    }
}
