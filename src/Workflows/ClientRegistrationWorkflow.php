<?php

namespace Litepie\Users\Workflows;

use Litepie\Flow\Workflows\Workflow;
use Litepie\Flow\States\State;
use Litepie\Flow\Transitions\Transition;
use Litepie\Users\Actions\SendClientWelcomeEmailAction;
use Litepie\Users\Actions\AssignDefaultRolesAction;
use Litepie\Users\Actions\NotifyAdminsAction;

class ClientRegistrationWorkflow
{
    public static function create(): Workflow
    {
        $workflow = new Workflow('client_registration', 'Client Registration Workflow');

        // Define states
        $pending = new State('pending', 'Pending Registration', true);
        $emailVerification = new State('email_verification', 'Email Verification Required');
        $adminApproval = new State('admin_approval', 'Awaiting Admin Approval');
        $active = new State('active', 'Active Client', false, true);
        $rejected = new State('rejected', 'Registration Rejected', false, true);

        // Add states to workflow
        $workflow->addState($pending)
                 ->addState($emailVerification)
                 ->addState($adminApproval)
                 ->addState($active)
                 ->addState($rejected);

        // Define transitions
        $verifyEmail = new Transition('pending', 'email_verification', 'verify_email');
        $submitForApproval = new Transition('email_verification', 'admin_approval', 'submit_for_approval');
        $approve = new Transition('admin_approval', 'active', 'approve');
        $reject = new Transition('admin_approval', 'rejected', 'reject');

        // Add actions to transitions
        $submitForApproval->addAction(new NotifyAdminsAction());
        $approve->addAction(new AssignDefaultRolesAction());
        $approve->addAction(new SendClientWelcomeEmailAction());

        // Add transitions to workflow
        $workflow->addTransition($verifyEmail)
                 ->addTransition($submitForApproval)
                 ->addTransition($approve)
                 ->addTransition($reject);

        return $workflow;
    }
}
