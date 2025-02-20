<?php

require_once dirname( __FILE__ ) . '/interface-ctc-group.php';
require_once dirname( __FILE__ ) . '/interface-ctc-person.php';
require_once dirname( __FILE__ ) . '/interface-people-group.php';
require_once dirname( __FILE__ ) . '/interface-person.php';

/**
 * Created by PhpStorm.
 * User: Chris
 * Date: 4/03/14
 * Time: 2:07 PM
 */
interface CTCI_WPALInterface {
	public function getOption( $option );

	public function createCTCGroup( CTCI_PeopleGroupInterface $group );

	public function attachCTCGroup( CTCI_CTCGroupInterface $ctcGroup, CTCI_PeopleGroupInterface $group );

	public function createAttachedCTCGroup( CTCI_PeopleGroupInterface $group );

	public function unattachCTCGroup( CTCI_CTCGroupInterface $ctcGroup );

	public function deleteCTCGroupAttachRecord( $ctcGroupId );

	public function updateCTCGroup( CTCI_CTCGroupInterface $ctcGroup, CTCI_PeopleGroupInterface $group );

	public function deleteCTCGroup( CTCI_CTCGroupInterface $ctcGroup );

	public function getCTCGroup( $term_id );

	public function getAttachedCTCGroup( CTCI_PeopleGroupInterface $group );

	/**
	 * Returns an associative array of CTCI_CTCGroupInterface with each key being the id
	 * @param $providerTag
	 * @return CTCI_CTCGroupInterface[]
	 */
	public function getCTCGroupsAttachedViaProvider( $providerTag );

	/**
	 * @return CTCI_CTCGroupInterface[]
	 * @throws CTCI_CouldNotRetrieveUnattachedCTCGroupsException
	 */
	public function getUnattachedCTCGroups();

	public function createAttachedCTCPerson( CTCI_PersonInterface $person );

	public function attachCTCPerson( CTCI_CTCPersonInterface $ctcPerson, CTCI_PersonInterface $person );

	public function updateCTCPerson( CTCI_CTCPersonInterface $ctcPerson );

	public function unattachCTCPerson( CTCI_CTCPersonInterface $ctcPerson );

	public function setCTCPersonsGroups( CTCI_CTCPersonInterface $ctcPerson, array $groups );

	public function deleteCTCPerson( CTCI_CTCPersonInterface $ctcPerson );

	public function unpublishCTCPerson( CTCI_CTCPersonInterface $ctcPerson );

	public function publishCTCPerson( CTCI_CTCPersonInterface $ctcPerson );

	/**
	 * @param $providerTag
	 * @return CTCI_CTCPersonInterface[]
	 */
	public function getCTCPeopleAttachedViaProvider( $providerTag );

	public function getAttachedPersonId( CTCI_CTCPersonInterface $ctcPerson );

	/**
	 * @return CTCI_CTCPersonInterface[]
	 */
	public function getUnattachedCTCPeople();

	public function getCurrentAdminPageURL();

	public function clearSyncStatus();
	public function setSyncMessage( $message );
	public function addSyncError( $message = null );
	public function addSyncWarning( $message = null );
	public function getSyncStatusAsJSON();

} 