<?php

namespace Database\Seeders;

use App\Constants\Constants;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {


        $emails_setting = [
            /*'email:on_user_signup' => [
                'subject' => 'Registration was successful',
                'body' => 'Dear {user}, <br> You have been successfully registered on ' . Constants::APP_NAME . '. Please use your email and the following password to login into the system.
                <br> We strongly advise you to change your default password. <br><br> Link: {link} <br> Password: <b> {password} </b>',
                'status' => 1,
            ],*/
            'email:on_user_registration' => [
                'subject' => 'Registration was successful',
                'body' => 'Dear {user}, <br> You have been successfully registered on ' . Constants::APP_NAME . '. Please use your email and the following password to login into the system.
                <br> We strongly advise you to change your default password. <br><br> Link: {link} <br> Password: <b> {password} </b>',
                'status' => 1,
            ],
            'email:on_reset_password' => [
                'subject' => 'Reset Password',
                'body' => 'Dear {user}, <br> You can reset your password for ' . Constants::APP_NAME . ' with the following link. <br> link: {link}',
                'status' => 1,
            ],
            'email:on_contact_us' => [
                'subject' => 'Your message has been received',
                'body' => 'Dear {user} <br> Thank you for Your message on User mangment. We always appreciate feedback and suggestions.',
                'status' => 1,
            ],


            /* 'email:on_request_institutions_for_comment' => [
                'subject' => 'Request for comment',
                'body' => 'Greetings, <br> We need your institution to comment on the draft document shared below. <br> Draft link: {link}',
            ],

            'email:on_request_personnel_for_comment' => [
                'subject' => 'Request for comment',
                'body' => 'Dear {user}, <br> You have been assigned to comment the draft shared below. <br> Draft link: {link}',
            ],

            'email:on_document_creation' => [
                'subject' => 'Draft document created',
                'body' => 'Dear {user}, <br> <br> You have successfully created a new draft document. please check it from the link provided below. <br> Draft link: {link}',
            ],

            'email:on_comment_open' => [
                'subject' => 'Draft document has been opened for comment',
                'body' => 'Dear {user}, <br> Your document has been opened for comment. <br> Draft link: {link}',
            ],
            'email:on_comment_close' => [
                'subject' => 'Draft document commenting session has been closed',
                'body' => 'Dear {user}, <br> Your draft document commenting session has been closed. <br> Draft link: {link}',
            ],
            'email:on_assignment_for_comment_replies' => [
                'subject' => 'Assigned as comment replier',
                'body' => 'Dear {user}, <br> You have been assigned as comment replier for the following draft. <br> Draft link: {link}',
            ],
            'email:on_assignment_as_commenter' => [
                'subject' => 'Assigned as commenter',
                'body' => 'Dear {user}, <br> You have been assigned to give your comments on the following draft content. <br> Draft link: {link}',
            ],*/

        ];







        foreach ($emails_setting as $code => $subject_body) {
            \App\Models\Email::factory()->create([
                'code' => $code,
                'subject' => $subject_body['subject'],
                'body' => $subject_body['body'],
                'status' => $subject_body['status'],
            ]);
        }



        $permissions = [

            'custom-exception',
            'email',
            'setting',
            'site-admin',
            'user',
            'audit',
            'role',
            // 'organization',
            'crud-generator',
            'notification',
            'login-attempt',
            'directorate',
            'theme',
            'objective',
            'activity-status',
            'implementation-status',
            'partner',
            'request-status',
            'activity',
            'draft-initiative',
            'implementation-initiative',
            'shelf-initiative',
        ];
        $permission_activities = [
            'list',
            'view',
            'create',
            'edit',
            'delete',
            // 'restore',
        ];



        $permission_counter = 0;
        $arrayOfPermissionNames = [];
        foreach ($permissions as $permission) {

            foreach ($permission_activities as $activity) {
                $permission_counter++;
                $arrayOfPermissionNames[] = $permission . ': ' . $activity;
            }
        }


        //other non CRUD permissions
        $arrayOfPermissionNames[] = 'crime: restore';

        // $arrayOfPermissionNames[] = 'access-domain: zonal';



        $permissions = collect($arrayOfPermissionNames)->map(function ($permission) {
            return ['name' => $permission, 'guard_name' => 'web'];
        });

        Permission::insert($permissions->toArray());


        $roles = [
            'Super Admin',
            'Planning Directorate',
            'Directorates',
            'Higher level officials',
        ];



        foreach ($roles as $role) {
            $myrole = Role::create([
                'name' => $role,
                'code' => $role
            ]);

            // if ($role == 'Super Admin') {
            // $myrole->givePermissionTo(Permission::all());
            // }
        }


        $initial_users = [
            [
                'first_name' => 'Super Admin',
                'middle_name' => '',
                'last_name' => '',
                'mobile' => '09876513',
                'status' => 1,
                'email' => 'admin@gmail.com',
                'password_changed' => 1,
                'is_superadmin' => 1,
                'created_by' => 1,
                'updated_by' => 1,
            ],
            /* [
                 'first_name' => 'Federal Level',
                 'middle_name' => 'Data Manager',
                 'last_name' => '',
                 'mobile' => '090964323',
                 'password_changed' => 1,
                 'status' => 1,
                 'is_superadmin' => 0,
                 'created_by' => 1,
                 'updated_by' => 1,
                 'email' => 'fdatamanager@gmail.com',
             ],
             [
                 'first_name' => 'Region Level',
                 'middle_name' => 'Data Manager',
                 'last_name' => '',
                 'mobile' => '090974343',
                 'password_changed' => 1,
                 'status' => 1,
                 'is_superadmin' => 0,
                 'created_by' => 1,
                 'updated_by' => 1,
                 'email' => 'rdatamanager@gmail.com',
             ],
             [
                 'first_name' => 'Supperrvisor',
                 'middle_name' => '',
                 'last_name' => '',
                 'mobile' => '090977343',
                 'password_changed' => 1,
                 'status' => 1,
                 'is_superadmin' => 0,
                 'created_by' => 1,
                 'updated_by' => 1,
                 'email' => 'suppervisor@gmail.com',
             ],*/


        ];

        foreach ($initial_users as $user) {
            $createdUser = \App\Models\User::factory()->create([
                'first_name' => $user['first_name'],
                'middle_name' => $user['middle_name'],
                'last_name' => $user['last_name'],
                'mobile' => $user['mobile'],
                'password' => "12345678",
                'password_changed' => $user['password_changed'],
                'status' => $user['status'],
                'is_superadmin' => $user['is_superadmin'],
                'created_by' => $user['created_by'],
                'updated_by' => $user['updated_by'],
                'email' => $user['email'],
            ]);



            if ($user['is_superadmin']) {
                $role = Role::findByName('Super Admin');
                $role->givePermissionTo(Permission::all());
                $createdUser->assignRole($role);
                // $createdUser->assignRole("Federal Level Data Manager");
                // } elseif ($user['email'] == 'fdatamanager@gmail.com') {
                //     $createdUser->assignRole("Federal Level Data Manager");
                // } elseif ($user['email'] == 'rdatamanager@gmail.com') {
                //     $createdUser->assignRole("Region Level Data Manager");
                // } elseif ($user['email'] == 'suppervisor@gmail.com') {
                /*$role = Role::findByName('Suppervisor');
                $createdUser->assignRole($role);
                $createdUser->assignRole("Suppervisor");*/
            }


            /*if ($role) {
                $createdUser->assignRole($role);
            }*/
        }




        $item_types = [
            [
                'name' => 'Raw',
                'description' => 'Raw',
            ],
            [
                'name' => 'Processed',
                'description' => 'Processed',
            ],
        ];







        $settings = [

            [
                'code' => 'twofa_code',
                'name' => 'Two Factor Authentication',
                'value1' => '0',
                'value2' => 'null',
                'type' => 0,
                'created_by' => 1,
                'updated_by' => 1,
            ],
            [
                'code' => 'allow_user_signup',
                'name' => 'allow user signup',
                'value1' => '1',
                'value2' => 'null',
                'type' => 0,
                'created_by' => 1,
                'updated_by' => 1,
            ],
            [
                'code' => 'allow_telegram_message',
                'name' => 'allow telegram message',
                'value1' => '1',
                'value2' => 'null',
                'type' => 0,
                'created_by' => 1,
                'updated_by' => 1,
            ],
            [
                'code' => 'page_number',
                'name' => 'page_number',
                'value1' => '10,25,50,100,300,500',
                'value2' => 'null',
                'type' => 1,
                'created_by' => 1,
                'updated_by' => 1,
            ],
            [
                'code' => 'privacy_policy',
                'name' => 'privacy policy',
                'value1' => 'value2',
                'value2' => 'We may collect personal information, such as your name, email address, and contact details, when you voluntarily submit it to us through our website. We will not sell, rent, or share your personal information with third parties without your consent, except as required by law',
                'type' => 2,
                'created_by' => 2,
                'updated_by' => 2,
            ],
            [
                'code' => 'terms_and_conditions',
                'name' => 'terms and conditions',
                'value1' => 'value3',
                'value2' => 'Cras mattis consectetur purus sit amet fermentum. ',
                'type' => 2,
                'created_by' => 1,
                'updated_by' => 1,
            ],

        ];

        foreach ($settings as $setting) {

            \App\Models\Setting::factory()->create(
                [
                    'code' => $setting['code'],
                    'name' => $setting['name'],
                    'value1' => $setting['value1'],
                    'value2' => $setting['value2'],
                    'type' => $setting['type'],
                    // 'created_by' => $setting['created_by'],
                    // 'updated_by' => $setting['updated_by'],
                ]
            );
        }


        $helps = [
            [
                'title' => 'Users List help',
                'url' => null,
                'body' => 'This page shows the list of users in the system. According the logged in user privileges buttons  for registering users, edit and delete could be visible. By default the page shows only ten latest users. You change the number of users per page by clicking on records per page box on the left top side. you can search users by their name or by email. You can also use the top filtering boxes to get the goups of user under a given category. e.g users under some organizations or users under Federa/Regional/Zonal. or others. you can download the list being visible by the formats provided on the top of the table. (CSV,Excel,PDF,Print) ',
                'route' => 'admin.users.index',
                'active' => '1',
                'created_by' => 1,
                'updated_by' => 1,
            ],
            [
                'title' => 'Roles List help',
                'url' => null,
                'body' => 'This page shows the list of Roles in the system. The visibility of the create Role and the yes or no buttons, which allows the user to either give permission  or prohbit permission for users group as well as deleting users group(roles), depends on the privileges of the logged-in user. By default the page shows only ten latest roles. You can change the number of users per page by clicking on records per page box on the left top side. you can search users by their permissions.  Additionally can download the list being visible by the formats provided on the top of the table. (CSV,Excel,PDF,Print) ',
                'route' => 'admin.roles.index',
                'active' => '1',
                'created_by' => 1,
                'updated_by' => 1,
            ],
            [
                'title' => 'Organization Levels List help',
                'url' => null,
                'body' => 'This page shows the list of Organization levels in the system. According the logged in user privileges buttons for create new organization level, edit and delete could be visible. By default the page shows only ten latest organization levels. You change the number of organiation levels per page by clicking on records per page box on the left top side. You can search organization levels by their name .You can download the list being visible by the formats provided on the top of the table. (CSV,Excel,PDF,Print) ',
                'route' => 'admin.organization-levels.index',
                'active' => '1',
                'created_by' => 1,
                'updated_by' => 1,
            ],
            [
                'title' => 'Organization Types List help',
                'url' => null,
                'body' =>
                    'This page shows the list of Organization types in the system. According the logged in user privileges buttons for create new organization type, edit and delete could be visible. By default the page shows only ten latest organization types. You can change the number of organiation types per page by clicking on records per page box on the left top side. You can search organization types by their name .Additionally,you can download the list being visible by the formats provided on the top of the table. (CSV,Excel,PDF,Print) ',
                'route' => "admin.organization-types.index",
                'active' => '1',
                'created_by' => 1,
                'updated_by' => 1,
            ],
            [
                'title' => 'Organizations List help',
                'url' => null,
                'body' => 'This page displays a list of organizations in the system. Depending on the privileges of the logged-in user, buttons for adding new organizations, editing existing organizations, and deleting organizations could be visible. By default, the page shows the ten latest organizations.You can change the number of organizations displayed per page by clicking on the "records per page" box located on the top left side of the page. Additionally, you can search for organizations by their name, organization type, organization level, or region/zone. The top filtering boxes can be used to group organizations based on specific categories, such as organization types or organization levels.Furthermore, you have the option to download the visible list of organizations in various formats provided at the top of the table, including CSV, Excel, PDF, and Print.',
                'route' => 'admin.organizations.index',
                'active' => '1',
                'created_by' => 1,
                'updated_by' => 1,
            ],
            [
                'title' => 'Issues List  help ',
                'url' => null,
                'body' => 'This page displays a list of issues in the system. Depending on the privileges of the logged-in user, buttons for adding new issues, editing existing issues, and deleting issues could be visible. By default, the page shows the ten latest issues. You can change the number of issues displayed per page by clicking on the "records per page" box located on the top left side of the page. Additionally, you can search for issues by their name, responsible person or responsible institution. The top filtering boxes can be used to group issues based on specific categories, such as organizations ,state of issue,stage of the issue or by using issue create range. Furthermore, you have the option to download the visible list of issues in various formats provided at the top of the table, including CSV, Excel, PDF, and Print.',
                'route' => 'admin.issues.index',
                'active' => '1',
                'created_by' => 1,
                'updated_by' => 1,
            ],
            [
                'title' => 'Working Groups List  ',
                'url' => null,
                'body' => 'This page displays a list of working groups in the system. Depending on the privileges of the logged-in user, buttons for adding new working groups, editing existing working groups, and deleting working groups could be visible. By default, the page shows the ten latest working groups. You can change the number of working groups displayed per page by clicking on the "records per page" box located on the top left side of the page. Additionally, you can search for working groups by their name or by their organization levels.Furthermore, you have the option to download the visible list of working groups in various formats provided at the top of the table, including CSV, Excel, PDF, and Print.',
                'route' => 'admin.working-groups.index',
                'active' => '1',
                'created_by' => 1,
                'updated_by' => 1,
            ],
            [
                'title' => 'Issue Requests List help  ',
                'url' => null,
                'body' => 'This page displays a list of issue requests in the system. The visibility of the "Respond" button, which allows the user to either approve or reject the requested issue, depends on the privileges of the logged-in user. By default, the page shows the ten latest issue requests. Also, you can modify the number of issue requests displayed per page by clicking on the "records per page" box located on the top left side of the page.Additionally, you have the ability to search for issue requests based on their name, responsible person, or the responsible institution associated with the request.Moreover, you can download the visible list of issue requests in various formats provided at the top of the table. The available formats include CSV, Excel, PDF, and Print',
                'route' => 'admin.issues.issue_request',
                'active' => '1',
                'created_by' => 1,
                'updated_by' => 1,
            ],
            [
                'title' => 'Regions List help',
                'url' => null,
                'body' => 'This page displays a list of regions in the system. The visibility of buttons for creating a new region, editing existing regions, and deleting regions depends on the privileges of the logged-in user.  By default, the page shows the ten latest regions. However, you can change the number of regions displayed per page by clicking on the "records per page" box located on the top left side of the page.  You also have the ability to search for regions by their name, allowing you to quickly find specific regions of interest.  Additionally, you can download the visible list of regions in various formats provided at the top of the table. The available formats include CSV, Excel, PDF, and Print.',
                'route' => "admin.regions.index",
                'active' => '1',
                'created_by' => 1,
                'updated_by' => 1,
            ],
            [
                'title' => 'Zones List help',
                'url' => null,
                'body' => "This page displays a list of zones in the system. The visibility of buttons for creating a new zone, editing existing zones, and deleting zones depends on the privileges of the logged-in user. By default, the page shows the ten latest zones. However, you can change the number of zones displayed per page by clicking on the 'records per page' box located on the top left side of the page.  You also have the ability to search for regions by their name, allowing you to quickly find specific zones of interest." . PHP_EOL . " Additionally, you can select a region from the top left side box to view all the zones that exist in that particular region. This allows you to filter the list and focus on zones within a specific region." . PHP_EOL . " Furthermore, you can download the visible list of zones in various formats provided at the top of the table. The available formats include CSV, Excel, PDF, and Print.",
                'route' => "admin.zones.index",
                'active' => '1',
                'created_by' => 1,
                'updated_by' => 1,
            ],
            [
                'title' => 'KPIs List help',
                'url' => null,
                'body' => "This page presents a comprehensive list of KPIs within the system. The visibility of buttons for creating a new KPI, editing existing KPIs, and deleting KPIs depends on the privileges of the logged-in user." . PHP_EOL . " By default, the page shows the ten latest KPIs. However, you can change the number of KPIs displayed per page by clicking on the 'records per page' box located on the top left side of the page. You also have the ability to search for kpis by their name, allowing you to quickly find specific KPIs of interest." . PHP_EOL . " Additionally, you can download the visible list of KPIs in various formats provided at the top of the table. The available formats include CSV, Excel, PDF, and Print.",
                'route' => "admin.kpis.index",
                'active' => '1',
                'created_by' => 1,
                'updated_by' => 1,
            ],
            [
                'title' => 'Emails List help',
                'url' => null,
                'body' => "This page presents a comprehensive list of emails within the system. The visibility of button for editing existing details(subject and body) depends on the privileges of the logged-in user." . PHP_EOL . " By default, the page shows the ten latest emails. However, you can change the number of emails displayed per page by clicking on the 'records per page' box located on the top left side of the page." . PHP_EOL . " You also have the ability to search for emails by their code or by their subject, allowing you to quickly find specific emails of interest." . PHP_EOL . " Additionally, you can download the visible list of emails in various formats provided at the top of the table. The available formats include CSV, Excel, PDF, and Print.",
                'route' => "admin.emails.index",
                'active' => '1',
                'created_by' => 1,
                'updated_by' => 1,
            ],
            [
                'title' => 'Configurations List help',
                'url' => null,
                'body' => "This page shows the list of configurations in the system. According the logged in user privileges button for edit could be visible." . PHP_EOL . " By default the page shows only ten latest configurations. You can change the number of configurations per page by clicking on 'records per page' box on the left top side. Also, you can search configurations by their name." . PHP_EOL . " Additionally, you can download the list being visible by the formats provided on the top of the table. (CSV,Excel,PDF,Print) ",
                'route' => 'admin.settings.index',
                'active' => '1',
                'created_by' => 1,
                'updated_by' => 1,
            ],
            [
                'title' => '  File Categories List help',
                'url' => null,
                'body' => "This page shows the list of file categories in the system. According the logged in user privileges buttons for creating a new file category, editing existing file categories, and deleting file categories could be visible." . PHP_EOL . " By default the page shows only ten latest file categories. You can change the number of file categories per page by clicking on 'records per page' box on the left top side. Also, you can search file categories by their name." . PHP_EOL . " Additionally, you can download the list being visible by the formats provided on the top of the table. (CSV,Excel,PDF,Print) ",
                'route' => 'admin.file-categories.index',
                'active' => '1',
                'created_by' => 1,
                'updated_by' => 1,
            ],
            [
                'title' => '  helps List help',
                'url' => null,
                'body' => "This page shows the list of helps in the system. According the logged in user privileges buttons for editing existing helps, show all the detail of helps and deleting helps could be visible." . PHP_EOL . " By default the page shows only ten latest helps. You can change the number of helps per page by clicking on 'records per page' box on the left top side. Also, you can search helps by their name,url or by their route." . PHP_EOL . " Additionally, you can download the list being visible by the formats provided on the top of the table. (CSV,Excel,PDF,Print) ",
                'route' => 'admin.helps.index',
                'active' => '1',
                'created_by' => 1,
                'updated_by' => 1,
            ],
            //
            [
                'title' => 'Custom Exceptions  help',
                'url' => null,
                'body' => "This page presents a comprehensive list of custom exceptions within the system. The visibility of button for clear all custom exceptions, view description and delete custom exception   depends on the privileges of the logged-in user." . PHP_EOL . " By default, the page shows the ten latest custom exceptions. However, you can change the number of custom exceptions displayed per page by clicking on the 'records per page' box located on the top left side of the page." . PHP_EOL . " You also have the ability to search for custom exceptions by  their title, allowing you to quickly find specific custom exceptions of interest." . PHP_EOL . " Additionally, you can download the visible list of custom exception in various formats provided at the top of the table. The available formats include CSV, Excel, PDF, and Print.",
                'route' => "admin.custom-exceptions.index",
                'active' => '1',
                'created_by' => 1,
                'updated_by' => 1,
            ],
            [
                'title' => 'Subscriptions List help',
                'url' => null,
                'body' => "This page presents a comprehensive list of subscription within the system. The visibility of   button for deleting subscription depends on the privileges of the logged-in user." . PHP_EOL .
                    "By default, the page shows the ten latest  subscriptions. However, you can change the number of  subscriptions displayed per page by clicking on the 'records per page' box located on the top left side of the page." . PHP_EOL .
                    "You also have the ability to search for  subscriptions by their email, allowing you to quickly find specific  subscriptions of interest." . PHP_EOL .
                    "Additionally, you can download the visible list of  subscriptions in various formats provided at the top of the table. The available formats include CSV, Excel, PDF, and Print.",
                'route' => "subscriptions.index",
                'active' => '1',
                'created_by' => 1,
                'updated_by' => 1,
            ],
            [
                'title' => 'FAQs List  help',
                'url' => null,
                'body' => "This page presents a comprehensive list of FAQs within the system. The visibility of button for creating FAQ, editing existing FAQs and deleting FAQs depends on the privileges of the logged-in user." . PHP_EOL . " By default, the page shows the ten latest FAQs. However, you can change the number of FAQs displayed per page by clicking on the 'records per page' box located on the top left side of the page." . PHP_EOL . " You acan also search for FAQs by their questions, allowing you to quickly find specific FAQs of interest." . PHP_EOL . " Additionally, you can download the visible list of FAQs in various formats provided at the top of the table. The available formats include CSV, Excel, PDF, and Print.",
                'route' => "admin.faqs.index",
                'active' => '1',
                'created_by' => 1,
                'updated_by' => 1,
            ],
            [
                'title' => 'Contact Us List help',
                'url' => null,
                'body' => "This page presents a comprehensive list of Contact Us within the system. The visibility of button for editing existing details depends on the privileges of the logged-in user." . PHP_EOL . " By default, the page shows the ten latest Contact Us. However, you can change the number of Contact Us displayed per page by clicking on the 'records per page' box located on the top left side of the page." . PHP_EOL . " You also have the ability to search for Contact Us by their name,email or by their subject, allowing you to quickly find specific Contact Us of interest." . PHP_EOL . " Additionally, you can download the visible list of Contact Us in various formats provided at the top of the table. The available formats include CSV, Excel, PDF, and Print.",
                'route' => "admin.contact-us.index",
                'active' => '1',
                'created_by' => 1,
                'updated_by' => 1,
            ],
            [
                'title' => 'Site admin help',
                'url' => null,
                'body' => "This page presents a site admin of the system. The visibility of button for update existing details of site admin depends on the privileges of the logged-in user." . PHP_EOL . " By default, the page shows the details of the site admin.",
                'route' => "admin.site-admin.index",
                'active' => '1',
                'created_by' => 1,
                'updated_by' => 1,
            ],
            [
                'title' => 'Dashboard help',
                'url' => null,
                'body' => "Dashboard page is a landing page for every logged in user. ",
                'route' => "admin.dashboard",
                'active' => '1',
                'created_by' => 1,
                'updated_by' => 1,
            ],
            [
                'title' => 'Profile help',
                'url' => null,
                'body' => "Your account settings page offers simple, one-click control. You can enhance security by changing your password and fine-tune your profile with options to update your name, profile photo, educational background and other details.",
                'route' => "admin.profile",
                'active' => '1',
                'created_by' => 1,
                'updated_by' => 1,
            ],
            [
                'title' => 'Analysis',
                'url' => null,
                'body' => "This Page presents overview of issues. You can filter issues by their level (Federal, Regional, Zonal) and view all status issues using the 'All Status' checkbox. It's your command center for quick issue management.. By default, the page shows the overview of issues.",
                'route' => "admin.analysis",
                'active' => '1',
                'created_by' => 1,
                'updated_by' => 1,
            ],

        ];

        foreach ($helps as $help) {

            \App\Models\Help::factory()->create(
                [
                    'title' => $help['title'],
                    'url' => $help['url'],
                    'body' => $help['body'],
                    'route' => $help['route'],
                    'active' => $help['active'],
                    'created_by' => $help['created_by'],
                    'updated_by' => $help['updated_by'],
                ]
            );
        }

        $siteAdmins = [

            [
                'name' => 'Reform Initiatives',
                'aboutus' => 'To implement and monitor reform initiatives roadmap to enhance the institutional efficiency and effectiveness of the Ministry of Revenues.',
                'location' => 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d7880.724309874413!2d38.75270387770995!3d9.030689800000008!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x164b85f23d0aecdb%3A0x5368a36524cc3e5a!2sFDRE%20Ministry%20of%20Trade%20and%20Industry!5e0!3m2!1sen!2spl!4v1694002069266!5m2!1sen!2spl" width="600" height="450" style="border:0;"
                allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade',
                'address' => 'Woreda 09 basha wolde chilot, Arada Sub City, Addis Ababa,',
                'email' => 'info@gmail.com',
                'telephone' => '+251115513990',
                'facebook' => 'https://www.facebook.com',
                'twitter' => 'https://twitter.com',
                'youtube' => 'https://www.youtube.com',
                'intro_video' => 'https://www.youtube.com',
                'linkedin' => 'https://www.linkedin.com',
            ],
        ];

        foreach ($siteAdmins as $siteAdmin) {

            \App\Models\SiteAdmin::factory()->create(
                [
                    'name' => $siteAdmin['name'],
                    'aboutus' => $siteAdmin['aboutus'],
                    'location' => $siteAdmin['location'],
                    'address' => $siteAdmin['address'],
                    'email' => $siteAdmin['email'],
                    'telephone' => $siteAdmin['telephone'],
                    'facebook' => $siteAdmin['facebook'],
                    'twitter' => $siteAdmin['twitter'],
                    'youtube' => $siteAdmin['youtube'],
                    'intro_video' => $siteAdmin['intro_video'],
                    'linkedin' => $siteAdmin['linkedin'],
                ]
            );
        }











        $organizations = [
            [
                "name" => "organization 1",
                "description" => "description 1"
            ],
            [
                "name" => "organization 2",
                "description" => "description 3"
            ],
            [
                "name" => "organization 3",
                "description" => "description 4"
            ],
        ];

        $themes = [
            [
                "name" => "Sustainable Industrial Capacity development",
            ],
            [
                "name" => "Theme2: Sustainable Industrial Capacity development",
            ],
            [
                "name" => "Theme: the Sustainable Industrial Capacity development",
            ],
        ];
        $directorates = [
            [
                "name" => "Human resource management",
            ],
            [
                "name" => "e-Data division",
            ],
            [
                "name" => "Property administration",
            ],
        ];
        $objecitves = [
            [
                "name" => "Attraction, retaining, and enhancing the efficiency of human resource",
            ],
            [
                "name" => "Improving IT capital and utilization",
            ],
            [
                "name" => "Objective3: Improving IT capital and utilization",
            ],
        ];

        foreach ($themes as $theme) {
            $th = \App\Models\Theme::factory()->create(
                [
                    "name" => $theme["name"],
                ]
            );

            foreach ($objecitves as $objecitve) {
                \App\Models\Objective::factory()->create(
                    [
                        "name" => $objecitve["name"],
                        "theme_id" => $th->id,
                    ]
                );
            }

        }
        foreach ($directorates as $directorate) {
            \App\Models\Directorate::factory()->create(
                [
                    "name" => $directorate["name"],
                ]
            );
        }

        foreach ($organizations as $organization) {
            \App\Models\Organization::factory()->create(
                [
                    "name" => $organization["name"],
                    "description" => $organization["description"]
                ]
            );
        }


        $statuses = ['Drafting stage', 'Shelfing stage', 'Implementation stage'];
        foreach ($statuses as $status) {
            \App\Models\ImplementationStatus::firstOrCreate(['name' => $status]);
        }
    }
}
