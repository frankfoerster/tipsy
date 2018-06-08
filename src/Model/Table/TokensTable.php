<?php
namespace App\Model\Table;

use App\Model\Entity\Token;
use App\Model\Entity\User;
use Cake\ORM\Table;
use Cake\Utility\Security;

/**
 * Class TokensTable
 *
 * @property UsersTable Users
 */
class TokensTable extends Table
{
    /**
     * Initialization method
     *
     * @param array $config
     * @return void
     */
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->belongsTo('Users');
        $this->addBehavior('Timestamp');
    }

    /**
     * Create a new token for the given $user and $type.
     *
     * @param User $user
     * @param string $type
     * @return bool|User
     */
    public function create(User $user, $type)
    {
        $token = $this->newEntity([
            'token' => $this->_generateUniqueToken(),
            'type' => $type,
            'user_id' => $user->id
        ]);

        return $this->save($token);
    }

    /**
     * Mark the given token as used.
     *
     * @param Token $token
     * @return bool|Token
     */
    public function useToken(Token $token)
    {
        $token->used = true;
        return $this->save($token);
    }

    /**
     * Find an unused token and corresponding user for the given $token string.
     *
     * @param string $token
     * @return array|null|Token
     */
    public function findUnusedTokenWithUser($token)
    {
        return $this->find()
            ->where(['token' => $token, 'used' => false])
            ->contain(['User'])
            ->first();
    }

    /**
     * Generate a unique token string.
     *
     * @return string
     */
    protected function _generateUniqueToken()
    {
        $token = $this->_generateToken();

        while ($this->exists(['token' => $token])) {
            $token = $this->_generateToken();
        }

        return $token;
    }

    /**
     * Generate a token string.
     *
     * @return string
     */
    protected function _generateToken()
    {
        return Security::hash(Security::randomBytes(32), 'sha256', false);
    }
}
