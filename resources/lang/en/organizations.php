<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Main
    |--------------------------------------------------------------------------
    */

    'title' => 'Organizations',

    'my_organizations' => 'My organizations',

    'create' => 'Create organization',

    'create_title' => 'Create organization',

    'organization_name' => 'Organization name',

    'name_placeholder' => 'Enter organization name',

    'open' => 'Open',

    'back' => 'Back',

    'back_to_organizations' => 'My organizations',

    'back_to_organization' => 'Back to organizations',

    'save' => 'Save',

    'cancel' => 'Cancel',

    /*
    |--------------------------------------------------------------------------
    | General information
    |--------------------------------------------------------------------------
    */

    'information' => 'Information',

    'status' => 'Status',

    'role' => 'Role',

    'your_role' => 'Your role',

    'your_status' => 'Your status',

    'organization_status' => 'Organization status',

    /*
    |--------------------------------------------------------------------------
    | Management
    |--------------------------------------------------------------------------
    */

    'management' => 'Management',

    'management_description' => 'Manage organization members, employees and settings.',

    'manage' => 'Manage',

    /*
    |--------------------------------------------------------------------------
    | Members
    |--------------------------------------------------------------------------
    */

    'members' => 'Members',

    'members_description' => 'Manage organization members, their roles and statuses.',

    'add_employee' => 'Add employee',

    'add_employee_description' => 'Add a DAODES user to the organization.',

    'select_user' => 'Select a DAODES user',

    /*
    |--------------------------------------------------------------------------
    | Roles
    |--------------------------------------------------------------------------
    */

    'roles' => [

        'manager' => 'Manager',

        'employee' => 'Employee',

    ],

    /*
    |--------------------------------------------------------------------------
    | Statuses
    |--------------------------------------------------------------------------
    */

    'statuses' => [

        'active' => 'Active',

        'testing' => 'Testing',

        'inactive' => 'Inactive',

        'blocked' => 'Blocked',

    ],

    /*
    |--------------------------------------------------------------------------
    | Actions
    |--------------------------------------------------------------------------
    */

    'actions' => [

        'remove' => 'Remove',

        'activate' => 'Activate',

        'deactivate' => 'Deactivate',

        'change_role' => 'Change role',

        'change_status' => 'Change status',

    ],

    /*
    |--------------------------------------------------------------------------
    | States
    |--------------------------------------------------------------------------
    */

    'no_organizations' => 'You do not have any organizations yet.',

    'no_members' => 'The organization has no members yet.',

    'no_available_users' => 'There are no DAODES users available to add.',

    'no_access' => 'You do not have access to this organization.',

    /*
    |--------------------------------------------------------------------------
    | Validation
    |--------------------------------------------------------------------------
    */

    'validation_error' => 'Please correct the errors.',

    'name_required' => 'Organization name is required.',

    'name_max' => 'Organization name must not exceed 255 characters.',

    'unknown_role' => 'Unknown role',

    'unknown_status' => 'Unknown status',

    /*
    |--------------------------------------------------------------------------
    | Successful operations
    |--------------------------------------------------------------------------
    */

    'created_successfully' => 'Organization successfully created.',

    'linked_successfully' => 'Organization successfully created and linked to your DAODES account.',

    /*
    |--------------------------------------------------------------------------
    | Messages
    |--------------------------------------------------------------------------
    */

    'messages' => [

        'created' => 'Organization successfully created.',

        'linked' => 'Organization successfully created and linked to your DAODES account.',

        'employee_added' => 'Employee successfully added.',

        'already_member' => 'This user is already a member of the organization.',

        'cannot_add_self' => 'You cannot add yourself as an employee.',

        'cannot_change_own_role' => 'You cannot change your own role.',

        'cannot_change_own_status' => 'You cannot change your own status.',

        'cannot_remove_self' => 'You cannot remove yourself from the organization.',

        'last_manager' => 'This action cannot be performed: the organization must have at least one active manager.',

        'role_updated' => 'Employee role successfully updated.',

        'status_updated' => 'Employee status successfully updated.',

        'employee_removed' => 'Employee removed from the organization.',

        'remove_confirm' => 'Are you sure you want to remove this employee from the organization?',

    ],

];