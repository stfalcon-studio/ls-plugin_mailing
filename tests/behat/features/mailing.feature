Feature: Mailing plugin standart features BDD
    Test base functionality of LiveStreet mailing plugin standart

@mink:selenium2
    Scenario: Mailing test for mans
        Given I load fixtures for plugin "mailing"
        Given I am on "/login"
        Then I want to login as "admin"

        Then run generate unsubscribe code

        # go to create new mailing for man
        Given I am on "/mailing/"
        Then I fill in "subject" with "test subject"
        Then I fill in "talk_text" with "test message for users"
        Then I press element by css "input[name='aSex[]'][value='woman']"
        Then I press element by css "input[name='aSex[]'][value='other']"

        Then I press element by css "input[name='submit']"

        Then I wait "1000"

        # Go to mailing list page and check for just create mailing
        Given I am on "/mailing/list"
        Then I should see in element by css "content" values:
        | value |
        | test subject |
        | 0 / 2 |

        Then I wait "1000"

        #Then run send message script
        Then run script "/plugins/mailing/include/cron/send-mail.php" and result should contain "/2 of 2 messages sended successful/"
        Then check is mail on dir

        #unsubskribe user
        Then I unsubskribe users

        # create new mailing for man
        Given I am on "/mailing/"
        Then I fill in "subject" with "test unsubskribe subject"
        Then I fill in "talk_text" with "test unsubskribe message for users"
        Then I press element by css "input[name='aSex[]'][value='woman']"
        Then I press element by css "input[name='aSex[]'][value='other']"

        Then I press element by css "input[name='submit']"

        Given I am on "/mailing/list"

        Then I wait "3000"

        #send unsubskribe mailing
        Then run script "/plugins/mailing/include/cron/send-mail.php" and result should contain "/1 of 1 messages sended successful/"


@mink:selenium2
    Scenario: Mailing test for woman and other
        Given I load fixtures for plugin "mailing"
        Given I am on "/login"
        Then I want to login as "admin"

        Then run generate unsubscribe code

      # go to create new mailing for man
        Given I am on "/mailing/"
        Then I fill in "subject" with "test subject"
        Then I fill in "talk_text" with "test message for users"
        Then I press element by css "input[name='aSex[]'][value='man']"

        Then I press element by css "input[name='submit']"

        Then I wait "1000"
        Given I am on "/mailing/list"

        Then I should see in element by css "content" values:
          | value |
          | test subject |
          | 0 / 4 |

        Then run script "/plugins/mailing/include/cron/send-mail.php" and result should contain "/4 of 4 messages sended successful/"

        Then the message should cointain:
        | value |
        | test subject |
        | test message for users |