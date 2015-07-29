<?php
//require('../autoloader.php');

/*
 * This script checks for the availability of domain names
 *
 * You can specify multiple domain names to be checked
 */


/*if ($argc <= 1) {
    echo "Usage: infodomain.php <domainname>\n";
    echo "Please enter a domain name retrieve\n\n";
    die();
}

$domainname = $argv[1];

echo "Retrieving info on " . $domainname . "\n";
try {
    $conn = new Metaregistrar\EPP\metaregEppConnection();

    // Connect to the EPP server
    if ($conn->connect()) {
        if (login($conn)) {
            infodomain($conn, $domainname);
            logout($conn);
        }
    } else {
        echo "ERROR CONNECTING\n";
    }
} catch (Metaregistrar\EPP\eppException $e) {
    echo "ERROR: " . $e->getMessage() . "\n\n";
}
*/

function infodomain($conn, $domainname) {
    try {
        $epp = new Metaregistrar\EPP\eppDomain($domainname);
        $info = new Metaregistrar\EPP\eppInfoDomainRequest($epp);
        if ((($response = $conn->writeandread($info)) instanceof Metaregistrar\EPP\eppInfoDomainResponse) && ($response->Success())) {
            /* @var $response Metaregistrar\EPP\eppInfoDomainResponse */        	
        	$returnmsg = "<p><ul>";
            $d = $response->getDomain();
            $returnmsg = $returnmsg . "Info domain for " . $d->getDomainname();
            $returnmsg = $returnmsg . "<li>Created on " . $response->getDomainCreateDate() . "</li>";
            //echo "Last update on ".$response->getDomainUpdateDate()."\n";
            $returnmsg = $returnmsg . "<li>Registrant " . $d->getRegistrant() . "</li>";
            $returnmsg = $returnmsg . "<li>Contact info:<br>";
            foreach ($d->getContacts() as $contact) {
                $returnmsg = $returnmsg . "  " . $contact->getContactType() . ": " . $contact->getContactHandle() . "<br>";
            }
            $returnmsg = $returnmsg . "</li>";
            $returnmsg = $returnmsg . "<li>Nameserver info: <br>";
            foreach ($d->getHosts() as $nameserver) {
                $returnmsg = $returnmsg . "  " . $nameserver->getHostName() . "<br>";
            }
            $returnmsg = $returnmsg . "</li>";
            $returnmsg = $returnmsg . "</ul></p>";
            
            return $returnmsg;
        } else {
            return "ERROR2";
        }
    } catch (Metaregistrar\EPP\eppException $e) {
        // echo 'ERROR1';
        return $e->getMessage() . "<br>";
    }
}