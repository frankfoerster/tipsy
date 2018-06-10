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
    const TYPE_ACCESS = 'access';
    const TYPE_LOST_PASSWORD = 'lost_password';
    const TYPE_VERIFY_EMAIL = 'verify_email';

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
     * @param string $validFor date interval period
     * @return bool|Token
     * @throws \Exception
     */
    public function create(User $user, $type, $validFor = 'PT1H')
    {
        $token = $this->newEntity([
            'user_id' => $user->id,
            'token' => $this->generateUniqueToken(),
            'type' => $type,
            'expires' => (new \DateTime())->add(new \DateInterval($validFor)),
            'force_expired' => false
        ]);

        return $this->save($token);
    }

    /**
     * Expire a token matching the given $token string.
     *
     * @param string $token
     * @return void
     */
    public function expireToken($token)
    {
        $this->updateAll(
            ['force_expired' => true],
            ['token' => $token]
        );
    }

    /**
     * Expire all token of a specific $type for the given $user.
     *
     * @param int $userId
     * @param string $type
     * @return void
     */
    public function expireTokens($userId, $type)
    {
        $this->updateAll(
            ['force_expired' => true],
            ['user_id' => $userId, 'type' => $type]
        );
    }

    /**
     * Renew the token expiration date of the given $token.
     *
     * @param string $token
     * @param string $validFor Date interval period the token should be valid for from now.
     * @throws \Exception
     * @return void
     */
    public function renewTokenExpiration($token, $validFor = 'PT1H')
    {
        $this->updateAll(
            ['expires' => (new \DateTime())->add(new \DateInterval($validFor))],
            ['token' => $token]
        );
    }

    /**
     * Generate a unique token string.
     *
     * @return string
     */
    public function generateUniqueToken()
    {
        $token = $this->generateToken();

        while ($this->exists(['token' => $token])) {
            $token = $this->generateToken();
        }

        return $token;
    }

    /**
     * Generate a token string.
     *
     * @return string
     */
    public function generateToken()
    {
        return Security::hash(Security::randomBytes(32), 'sha256', true);
    }
}
