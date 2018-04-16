<?php

class Users extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var integer
     * @Primary
     * @Identity
     * @Column(column="id", type="integer", length=11, nullable=false)
     */
    public $id;

    /**
     *
     * @var string
     * @Column(column="user_id", type="string", nullable=true)
     */
    public $user_id;

    /**
     *
     * @var string
     * @Column(column="user_name", type="string", nullable=true)
     */
    public $user_name;

    /**
     *
     * @var string
     * @Column(column="channel_id", type="string", nullable=true)
     */
    public $channel_id;

    /**
     *
     * @var string
     * @Column(column="channel_name", type="string", nullable=true)
     */
    public $channel_name;

    /**
     *
     * @var string
     * @Column(column="team_id", type="string", nullable=true)
     */
    public $team_id;


    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema("slackbot");
        $this->setSource("users");
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'users';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return Users[]|Users|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return Users|\Phalcon\Mvc\Model\ResultInterface
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }


}
