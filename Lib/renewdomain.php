<?php
// require('../autoloader.php');

/*
 * This sample script renews a domain name within your account
 *
 * The nameservers of metaregistrar are used as nameservers
 *
 */

/* if ($argc <= 3) 
{
    echo "Usage: renewdomain.php <domainname> <expirydate> <no_of_years>\n";
    echo "Please enter the domain name to be renewed\n\n";
    die();
}

$domainname = $argv[1];
$expirydate = $argv[2];
$no_of_years = $argv[3];

echo "Renewing $domainname\n";
$conn = new Metaregistrar\EPP\metaregEppConnection();

// Connect to the EPP server
if ($conn->connect()) {
    if (login($conn)) {
        renewdomain($conn, $domainname, $expirydate, $no_of_years);
        logout($conn);
    }
} */

function renewdomain($conn, $domainname, $expirydate, $no_of_years) {
    try {
        $domain = new Metaregistrar\EPP\eppDomain($domainname);
        $domain->setPeriod($no_of_years);
        $domain->setPeriodUnit('y');
        $renew = new Metaregistrar\EPP\eppRenewRequest($domain, $expirydate);

        if ((($response = $conn->writeandread($renew)) instanceof Metaregistrar\EPP\eppRenewResponse) && ($response->Success())) {
            /* @var $response Metaregistrar\EPP\eppRenewResponse */
            $retval = "Domain " . $response->getDomainName() . " renewed, expiration date is " . $response->getDomainExpirationDate() . "\n";
            return array('message' => $retval, 'status' => true, 'response' => $response);
        } else {
            return array('message' => "Failed to renew domain<br>", 'status' => false);
        }
    } catch (Metaregistrar\EPP\eppException $e) {
        return array('message' => $e->getMessage() . "<br>", 'status' => false);
    }
}
?>
