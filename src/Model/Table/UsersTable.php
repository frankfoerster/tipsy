<?php

namespace App\Model\Table;

use App\Model\Entity\User;
use App\Model\UserTipTrait;
use Cake\Auth\PasswordHasherFactory;
use Cake\Event\Event;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Class UsersTable
 *
 * @property UserGroupsTable UserGroups
 * @property TokensTable Tokens
 * @property TipsTable Tips
 * @property TipsTable UserTip
 */
class UsersTable extends Table
{
    use UserTipTrait;

    /**
     * Initialization hook method.
     *
     * @param array $config
     * @return void
     */
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->belongsTo('UserGroups');
        $this->hasMany('Tokens');
        $this->hasMany('Tips');
        $this->hasOne('UserTip', [
            'className' => 'Tips',
            'foreignKey' => 'user_id'
        ]);

        $this->addBehavior('Timestamp');
    }

    /**
     * beforeSave callback
     *
     * @param Event $event
     * @param User $user
     * @return void
     */
    public function beforeSave(Event $event, User $user)
    {
        if (empty($user->user_group_id)) {
            $user->user_group_id = 2;
        }
    }

    /**
     * Signup validation configuration.
     *
     * @param Validator $validator
     * @return Validator
     * @throws \Aura\Intl\Exception
     */
    public function validationSignup(Validator $validator)
    {
        $validator = $this->_requireEmail($validator);
        $validator = $this->_requireUsername($validator);
        $validator = $this->_requirePassword($validator);

        $validator = $this->_validEmail($validator);
        $validator = $this->_validUsername($validator);
        $validator = $this->_validPassword($validator);

        return $validator;
    }

    /**
     * Lost password validation configuration.
     *
     * @param Validator $validator
     * @return Validator
     * @throws \Aura\Intl\Exception
     */
    public function validationLostPassword(Validator $validator)
    {
        $validator = $this->_requireEmail($validator);
        $validator = $this->_validEmail($validator);

        return $validator;
    }

    /**
     * Reset password validation configuration.
     *
     * @param Validator $validator
     * @return Validator
     * @throws \Aura\Intl\Exception
     */
    public function validationResetPassword(Validator $validator)
    {
        $validator = $this->_requirePassword($validator);
        $validator = $this->_validPassword($validator);

        return $validator;
    }

    /**
     * Rules configuration.
     *
     * @param RulesChecker $rules
     * @return RulesChecker
     * @throws \Aura\Intl\Exception
     */
    public function buildRules(RulesChecker $rules)
    {
        $rules->add([$this, 'isUniqueEmail'], 'unique', [
            'errorField' => 'email',
            'message' => __('This email is already in use.')
        ]);

        $rules->add([$this, 'isUniqueUsername'], 'unique', [
            'errorField' => 'username',
            'message' => __('This username is already taken.')
        ]);

        return $rules;
    }

    /**
     * Create a new user with the given $data.
     *
     * @param User $user
     * @param array $data
     * @return bool|User|mixed
     */
    public function create(User $user, array $data)
    {
        $user = $this->patchEntity($user, $data, ['validate' => 'signup']);
        return $this->save($user);
    }

    /**
     * Find a user by the given credentials.
     *
     * @param string $login The email or username of the user.
     * @param string $password The password of the user.
     * @return array|User|null
     */
    public function findByCredentials($login, $password)
    {
        if (empty($login) || empty($password)) {
            return null;
        }

        /** @var User $user */
        $user = $this->find()
            ->select([
                'id',
                'username',
                'email',
                'password',
                'verified'
            ])
            ->where([
                'or' => [
                    'username' => $login,
                    'email' => $login
                ]
            ])
            ->first();

        if (!$user) {
            return null;
        }

        $hasher = PasswordHasherFactory::build('Default');
        if (!$hasher->check($password, $user->password)) {
            return null;
        }

        $user->unsetProperty('password');

        return $user;
    }

    /**
     * Find a user by valid $token and token $type.
     *
     * @param string $token
     * @param string $type The token type.
     * @return array|User|null
     */
    public function findByToken($token, $type)
    {
        if (empty($token)) {
            return null;
        }

        $user = $this->find()
            ->select([
                'id',
                'username',
                'email',
                'verified'
            ])
            ->matching('Tokens', function (Query $query) use ($token, $type) {
                return $query
                    ->select([
                        'user_id',
                        'token'
                    ])
                    ->where([
                        'token' => $token,
                        'type' => $type,
                        'force_expired' => false,
                        'expires >' => new \DateTime()
                    ]);
            })
            ->first();

        return $user;
    }

    /**
     * Find a user by email.
     *
     * @param string $email
     * @return array|User|null
     */
    public function findByEmail($email)
    {
        return $this->find()->where(['email' => $email])->first();
    }

    /**
     * Find user ranking for the vuex store.
     *
     * @return \Cake\Collection\CollectionInterface
     */
    public function findRankingForStore()
    {
        $users = $this->find()
            ->select([
                'Users.id',
                'base_user_id' => 'Users.id',
                'Users.username'
            ])
            ->contain([
                'UserTip' => function (Query $query) {
                    return $query
                        ->contain([
                            'Games'
                        ]);
                }
            ]);

        $bonusPointsQuery = $this->find()
            ->select([
                'points' => $users->func()->sum($this->getTotalBonusPoints($users))
            ])
            ->leftJoin(['Games' => 'games'], [
                    'or' => [
                        'Games.team1_id = Users.winning_team_id',
                        'Games.team2_id = Users.winning_team_id'
                    ]
            ])
            ->where([
                'Users.id = base_user_id',
                'Users.winning_team_id IS NOT NULL'
            ]);

        $rankings = $users
            ->group('Users.id')
            ->select([
                'total_points' => $users->func()->sum($this->getTotalPointsCase($users)),
                'total_exact' => $users->func()->sum($this->getExactCase($users)),
                'total_tendency' => $users->func()->sum($this->getTendencyCase($users)),
                'total_votes' => $users->func()->sum($this->getTotalValuesVotes($users)),
                'total_bonus_points' => $bonusPointsQuery
            ])
            ->filter(function (User $user) {
                if (empty($user->get('total_points'))) {
                    $user->set('total_points', 0);
                }
                if (empty($user->get('total_bonus_points'))) {
                    $user->set('total_bonus_points', 0);
                } else {
                    $user->set('total_bonus_points', (int)$user->get('total_bonus_points'));
                }
                $user->set('total_points', $user->get('total_points') + (int)$user->get('total_bonus_points'));
                return $user;
            })
            ->combine(
                function (User $user) {
                    return $user->id;
                },
                function (User $user) {
                    return $user;
                }
            );

        return $rankings;
    }

    /**
     * Verify the user with the given $userId.
     *
     * @param int $userId
     * @return void
     */
    public function verify($userId)
    {
        $this->updateAll(
            ['verified' => true],
            ['id' => $userId]
        );
    }

    /**
     * Check if the email of the given $user ist unique.
     *
     * @param User $user
     * @return bool
     */
    public function isUniqueEmail(User $user)
    {
        return !$this->exists(['Users.email' => $user->email]);
    }

    /**
     * Check if the username of the given $user is unique.
     *
     * @param User $user
     * @return bool
     */
    public function isUniqueUsername(User $user)
    {
        return !$this->exists(['Users.username' => $user->username]);
    }

    /**
     * Require presence of email for the given validator instance.
     *
     * @param Validator $validator
     * @param bool $mode true=create/update, false
     * @return Validator
     * @throws \Aura\Intl\Exception
     */
    protected function _requireEmail(Validator $validator, $mode = true)
    {
        $validator
            ->requirePresence('email', $mode, __('The field "email" must be present.'));

        return $validator;
    }

    /**
     * Require presence of username for the given validator instance.
     *
     * @param Validator $validator
     * @param bool $mode true=create/update, false
     * @return Validator
     * @throws \Aura\Intl\Exception
     */
    protected function _requireUsername(Validator $validator, $mode = true)
    {
        $validator
            ->requirePresence('username', $mode, __('The field "username" must be present.'));

        return $validator;
    }

    /**
     * Require presence of password for the given validator instance.
     *
     * @param Validator $validator
     * @param bool $mode true=create/update, false
     * @return Validator
     * @throws \Aura\Intl\Exception
     */
    protected function _requirePassword(Validator $validator, $mode = true)
    {
        $validator
            ->requirePresence('password', $mode, __('The field "password" must be present.'))
            ->requirePresence('password_confirmation', $mode, __('The field "password_confirmation" must be present.'));

        return $validator;
    }

    /**
     * Add email validation to the given validator instance.
     *
     * @param Validator $validator
     * @return Validator
     * @throws \Aura\Intl\Exception
     */
    protected function _validEmail(Validator $validator)
    {
        $validator
            ->notEmpty('email', __('Please enter an email address.'))
            ->add('email', [
                'valid' => [
                    'rule' => ['email'],
                    'message' => __('Please enter a valid email address'),
                ]
            ]);

        return $validator;
    }

    /**
     * Add username validation to the given validator instance.
     *
     * @param Validator $validator
     * @return Validator
     * @throws \Aura\Intl\Exception
     */
    protected function _validUsername(Validator $validator)
    {
        $validator
            ->notEmpty('username', __('Please enter a username.'))
            ->add('username', [
                'minLength' => [
                    'rule' => ['minLength', 2],
                    'message' => __('The username must contain at least 2 characters.'),
                ]
            ]);

        return $validator;
    }

    /**
     * Add password validation to the given validator instance.
     *
     * @param Validator $validator
     * @return Validator
     * @throws \Aura\Intl\Exception
     */
    protected function _validPassword(Validator $validator)
    {
        $validator
            ->notEmpty('password', __('Please enter a password.'))
            ->notEmpty('password_confirmation', __('Please confirm your password by entering it again.'))
            ->add('password', [
                'minLength' => [
                    'rule' => ['minLength', 8],
                    'message' => __('The password must contain at least 8 characters.'),
                ],
            ]);

        return $validator;
    }
}
