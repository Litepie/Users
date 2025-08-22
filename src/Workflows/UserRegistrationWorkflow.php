<?php

namespace Litepie\Users\Workflows;

use Litepie\Flow\Workflows\Workflow;
use Litepie\Flow\States\State;
use Litepie\Flow\Transitions\Transition;
use Litepie\Users\Actions\SendWelcomeEmailAction;
use Litepie\Users\Actions\AssignDefaultRolesAction;

class UserRegistrationWorkflow
{
    public static function create(): Workflow
    {
        $workflow = new Workflow('user_registration', 'User Registration Workflow');

        // Define states
        $pending = new State('pending', 'Pending Registration', true);
        $emailVerification = new State('email_verification', 'Email Verification Required');
        $active = new State('active', 'Active User', false, true);

        // Add states to workflow
        $workflow->addState($pending)
                 ->addState($emailVerification)
                 ->addState($active);

        // Define transitions
        $verifyEmail = new Transition('pending', 'email_verification', 'verify_email');
        $activate = new Transition('email_verification', 'active', 'activate');

        // Add actions to transitions
        $activate->addAction(new AssignDefaultRolesAction());
        $activate->addAction(new SendWelcomeEmailAction());

        // Add transitions to workflow
        $workflow->addTransition($verifyEmail)
                 ->addTransition($activate);

        return $workflow;
    }
}
