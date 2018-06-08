<?php

namespace App\Model\Table;

use App\Model\Entity\User;
use Cake\Auth\DefaultPasswordHasher;
use Cake\Event\Event;
use Cake\Http\ServerRequest;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Class UsersTable
 *
 * @property UserGroupsTable UserGroups
 * @property TokensTable Tokens
 * @property TipsTable Tips
 */
class UsersTable extends Table
{
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
        if (empty($user->group_id)) {
            $user->group_id = 2;
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
            'message' => __('This email is already used.')
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
     * Find a user by the given login credentials.
     *
     * @param ServerRequest $request
     * @return array|User|null
     */
    public function findByCredentials(ServerRequest $request)
    {
        $usernameOrEmail = $request->getData('login');
        $password = $request->getData('password');

        if (empty($usernameOrEmail) || empty($password)) {
            return null;
        }

        return $this->find()->where([
            'or' => [
                'username' => $usernameOrEmail,
                'email' => $usernameOrEmail
            ],
            'password' => (new DefaultPasswordHasher())->hash($password)
        ])->first();
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
