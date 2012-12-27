<?php

use Behat\Behat\Context\ClosuredContextInterface,
    Behat\Behat\Context\TranslatedContextInterface,
    Behat\Behat\Context\BehatContext,
    Behat\MinkExtension\Context\MinkContext,
    Behat\Mink\Exception\ExpectationException,
    Behat\Behat\Exception\PendingException;
use Behat\Gherkin\Node\PyStringNode,
    Behat\Gherkin\Node\TableNode;

$sDirRoot = dirname(realpath((dirname(__FILE__)) . "/../../../../../"));
set_include_path(get_include_path().PATH_SEPARATOR.$sDirRoot);

require_once("tests/behat/features/bootstrap/BaseFeatureContext.php");

/**
 * LiveStreet custom feature context
 */
class FeatureContext extends MinkContext
{
    protected $sDirRoot;

    public function __construct(array $parameters)
    {
        $this->sDirRoot = dirname(realpath((dirname(__FILE__)) . "/../../../../../"));
        $this->parameters = $parameters;
        $this->useContext('base', new BaseFeatureContext($parameters));
    }

    public function getEngine() {
        return $this->getSubcontext('base')->getEngine();
    }

    /**
     * @Then /^check is mail on dir$/
     */
    public function checkIsMailOnDir()
    {
        sleep(1);
        $exclude_list = array(".", "..");
        $directories = array_diff(scandir('/var/mail/sendmail/new'), $exclude_list);

        if (!count($directories)) {
            throw new ExpectationException('Messages not send (not is dir)', $this->getSession());
        }
    }

    /**
     * @Then /^run generate unsubscribe code$/
     */
    public function runGenerateUnsubscribeCode()
    {
        if (!file_exists("{$this->sDirRoot}/plugins/mailing/include/cron/generate-unsub-hash.php")) {
            throw new ExpectationException('Script file not found', $this->getSession());
        }

        shell_exec("{$this->sDirRoot}/plugins/mailing/include/cron/generate-unsub-hash.php");
    }

    /**
     * @Then /^I unsubskribe users$/
     */
    public function iUnsubskribeUsers()
    {
        $exclude_list = array(".", "..");
        $directories = array_diff(scandir('/var/mail/sendmail/new'), $exclude_list);

        if (!count($directories)) {
            throw new ExpectationException('Messages not found on dir', $this->getSession());
        }

        foreach ($directories as $fileName) {
            $fileContent = file_get_contents('/var/mail/sendmail/new/' . $fileName );

            $pattern = '/(\/mailing\/unsubscribe\?email=user_[a-z]+\@info.com\&hash=[a-f0-9]{16})"/';

            if ( !preg_match_all($pattern, $fileContent, $link)) {
                throw new ExpectationException('Link not found on file', $this->getSession());
            }

            $this->getSession()->visit($this->locatePath($link[1][0]));
            break;
        }
    }

    /**
     * @Then /^the message should cointain:$/
     */
    public function theMessageShouldCointain(TableNode $values)
    {
        $exclude_list = array(".", "..");
        $directories = reset(array_diff(scandir('/var/mail/sendmail/new'), $exclude_list));

        if (!count($directories)) {
            throw new ExpectationException('Messages not found on dir', $this->getSession());
        }

        $fileContent = file_get_contents('/var/mail/sendmail/new/' . $directories );

        foreach ($values->getHash() as $genreHash) {
            $regex  = '/'.preg_quote($genreHash['value'], '/').'/ui';
            if (!preg_match($regex, $fileContent)) {
                $message = sprintf('The string "%s" was not found anywhere in message.', $genreHash['value']);
                throw new ExpectationException($message, $this->getMinkContext()->getSession());
            }
        }
    }
}





