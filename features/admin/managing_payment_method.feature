@managing_payment_methods
Feature: Adding a new Payline payment method
    In order to allow payment for orders, using a Payline gateway
    As an Administrator
    I want to add new payment methods to the system

    Background:
        Given the store operates on a single channel in "United States"
        And I am logged in as an administrator

    @ui @mink:chromedriver
    Scenario: Adding a new Payline payment method
        Given I want to create a new Payline payment method
        When I name it "Payline" in "English (United States)"
        And I specify its code as "payline"
        And I configure it with test payline gateway data "TEST", "TEST"
        And I add it
        Then I should be notified that it has been successfully created
        And the payment method "Payline" should appear in the registry
