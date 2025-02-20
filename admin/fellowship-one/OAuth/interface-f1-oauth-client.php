<?php

/**
 * Created by PhpStorm.
 * User: Chris
 * Date: 5/03/14
 * Time: 9:10 AM
 */
interface CTCI_F1OAuthClientInterface {
	public function setCallbackURL( $url );

	public function setUsername( $username );

	public function setPassword( $password );

    public function setServerURL( $url );

    public function setConsumerKey( $key );

    public function setConsumerSecret( $secret );

	public function authenticate();

	public function retrieveAccessToken($oauthToken, $tokenSecret);

	/**
	 * To be called when retrieving the access token from a cache (of some kind).
	 * @param $token
	 * @return $this
	 */
	public function setAccessToken( $token );

	/**
	 * To be called when retrieving the access token from a cache (of some kind).
	 * @param $tokenSecret
	 * @return mixed
	 */
	public function setAccessTokenSecret( $tokenSecret );

	public function getAccessToken();

	public function getAccessTokenSecret();

	/**
	 * @return $this
	 */
	public function json();

	/**
	 * @return $this
	 */
	public function xml();

	public function getPeopleLists();

	public function getPeopleList( $peopleListId );

	public function getPeopleListMembers( $peopleListId );

	public function getPeopleListMember( $peopleListId, $memberId );

	public function getPerson( $personId );

	public function getPersonCommunications( $personId );

	public function getPersonAttributes( $personId );
} 