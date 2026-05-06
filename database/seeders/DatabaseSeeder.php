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
            'contact-us',
            'custom-exception',
            'email',
            'f-a-q',
            'file-category',
            'region',
            'setting',
            'site-admin',
            'user',
            'audit',
            'zone',
            'role',
            'organization',
            'suggestion',
            'archive-crimes',
            'crud-generator',
            'country',
            'notification',
            'login-attempt',
            'directorate',
            'theme',
            'objective',
            'initiative-status',
            'implementation-status',
            'partner',
            'request-status',
            'support-request',
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
            'Federal Level Data Manager',
            'Region Level Data Manager',
            'Suppervisor',
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
            [
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
            ],


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
                $createdUser->assignRole("Federal Level Data Manager");
            } elseif ($user['email'] == 'fdatamanager@gmail.com') {
                $createdUser->assignRole("Federal Level Data Manager");
            } elseif ($user['email'] == 'rdatamanager@gmail.com') {
                $createdUser->assignRole("Region Level Data Manager");
            } elseif ($user['email'] == 'suppervisor@gmail.com') {
                $role = Role::findByName('Suppervisor');
                // $role->givePermissionTo(Permission::all());
                $createdUser->assignRole($role);
                $createdUser->assignRole("Suppervisor");
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
                'value2' => 'Cras mattis consectetur purus sit amet fermentum. Cras justo odio, dapibus ac facilisis in, egestas eget quam. Morbi leo risus, porta ac consectetur ac, vestibulum at eros.

                Praesent commodo cursus magna, vel scelerisque nisl consectetur et. Vivamus sagittis lacus vel augue laoreet rutrum faucibus dolor auctor.

                Aenean lacinia bibendum nulla sed consectetur. Praesent commodo cursus magna, vel scelerisque nisl consectetur et. Donec sed odio dui. Donec ullamcorper nulla non metus auctor fringilla.

                Cras mattis consectetur purus sit amet fermentum. Cras justo odio, dapibus ac facilisis in, egestas eget quam. Morbi leo risus, porta ac consectetur ac, vestibulum at eros.

                Praesent commodo cursus magna, vel scelerisque nisl consectetur et. Vivamus sagittis lacus vel augue laoreet rutrum faucibus dolor auctor.

                Aenean lacinia bibendum nulla sed consectetur. Praesent commodo cursus magna, vel scelerisque nisl consectetur et. Donec sed odio dui. Donec ullamcorper nulla non metus auctor fringilla.

                Cras mattis consectetur purus sit amet fermentum. Cras justo odio, dapibus ac facilisis in, egestas eget quam. Morbi leo risus, porta ac consectetur ac, vestibulum at eros.

                Praesent commodo cursus magna, vel scelerisque nisl consectetur et. Vivamus sagittis lacus vel augue laoreet rutrum faucibus dolor auctor.

                Aenean lacinia bibendum nulla sed consectetur. Praesent commodo cursus magna, vel scelerisque nisl consectetur et. Donec sed odio dui. Donec ullamcorper nulla non metus auctor fringilla.

                Cras mattis consectetur purus sit amet fermentum. Cras justo odio, dapibus ac facilisis in, egestas eget quam. Morbi leo risus, porta ac consectetur ac, vestibulum at eros.

                Praesent commodo cursus magna, vel scelerisque nisl consectetur et. Vivamus sagittis lacus vel augue laoreet rutrum faucibus dolor auctor.

                Aenean lacinia bibendum nulla sed consectetur. Praesent commodo cursus magna, vel scelerisque nisl consectetur et. Donec sed odio dui. Donec ullamcorper nulla non metus auctor fringilla.

                Cras mattis consectetur purus sit amet fermentum. Cras justo odio, dapibus ac facilisis in, egestas eget quam. Morbi leo risus, porta ac consectetur ac, vestibulum at eros.

                Praesent commodo cursus magna, vel scelerisque nisl consectetur et. Vivamus sagittis lacus vel augue laoreet rutrum faucibus dolor auctor.

                Aenean lacinia bibendum nulla sed consectetur. Praesent commodo cursus magna, vel scelerisque nisl consectetur et. Donec sed odio dui. Donec ullamcorper nulla non metus auctor fringilla.

                Cras mattis consectetur purus sit amet fermentum. Cras justo odio, dapibus ac facilisis in, egestas eget quam. Morbi leo risus, porta ac consectetur ac, vestibulum at eros.

                Praesent commodo cursus magna, vel scelerisque nisl consectetur et. Vivamus sagittis lacus vel augue laoreet rutrum faucibus dolor auctor.

                Aenean lacinia bibendum nulla sed consectetur. Praesent commodo cursus magna, vel scelerisque nisl consectetur et. Donec sed odio dui. Donec ullamcorper nulla non metus auctor fringilla.',
                'type' => 2,
                'created_by' => 3,
                'updated_by' => 3,
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
                    'created_by' => $setting['created_by'],
                    'updated_by' => $setting['updated_by'],
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
                'body' => 'This page shows the list of Organization types in the system. According the logged in user privileges buttons for create new organization type, edit and delete could be visible. By default the page shows only ten latest organization types. You can change the number of organiation types per page by clicking on records per page box on the left top side. You can search organization types by their name .Additionally,you can download the list being visible by the formats provided on the top of the table. (CSV,Excel,PDF,Print) ',
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
                'body' =>  "This page presents a comprehensive list of subscription within the system. The visibility of   button for deleting subscription depends on the privileges of the logged-in user." . PHP_EOL .
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
                'name' => 'User Mangment',
                'aboutus' => "Ensuring on the Ethiopia’s User Mangment resources conservation through the active participation of the community and other stakeholders, by enforcing national and international laws and conventions, to provide sustainable ecological, economic, and social benefits for Ethiopians as well as the global community and pass  to the next generation as a heritage.",
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

        $arr = [
            ['name' => 'Tigray', 'latitude' => '13.881272999776511', 'longitude' => '39.127495', 'ordering' => 3, 'is_cityadministration' => 0, 'zones' => ['Central Tigray', 'East Tigray', 'North West Tigray', 'South Tigray', 'South East Tigray', 'West Tigray', 'Mekelle'], 'color' => '#FF5733'],
            ['name' => 'Afar', 'latitude' => '11.710820599554784', 'longitude' => '41.03308659999999', 'ordering' => 4, 'is_cityadministration' => 0, 'zones' => ['Awsi Rasu', 'Kilbet Rasu', 'Gabi Rasu', 'Fanti Rasu', 'Hari Rasu', 'Mahi Rasu', 'Argobba'], 'color' => '#33FF57'],
            ['name' => 'Amhara', 'latitude' => '11.49999999953709', 'longitude' => '38.5', 'ordering' => 5, 'is_cityadministration' => 0, 'zones' => [' Agew Awi', 'East Gojjam', 'Oromia zone', '  North Gondar', 'North Shewa', ' North Wollo', '  South Gondar', ' South Wollo', ' Wag Hemra', 'West Gojjam', 'Bahir Dar '], 'color' => '#5733FF'],
            ['name' => 'Oromia', 'latitude' => '7.672164399392472', 'longitude' => '40.0299727', 'ordering' => 6, 'is_cityadministration' => 0, 'zones' => ['Arsi Zone', 'Bale Zone', 'Borena Zone', 'Buno Bedele Zone', 'East Hararghe Zone', 'East Shewa Zone', 'East Welega Zone', 'Guji Zone', 'Horo Guduru Welega Zone', 'Illu Aba Bora Zone', 'Jimma Zone', 'Kelam Welega Zone', 'North Shewa Zone', 'Southwest Shewa Zone', 'West Arsi Zone', 'West Guji Zone', 'West Hararghe Zone', 'West Shewa Zone', 'West Welega Zone', 'Oromia Special Zone Surrounding Finfinne'], 'color' => '#33FF33'],
            ['name' => 'Somali', 'latitude' => '7.262984399398904', 'longitude' => '43.5360531', 'ordering' => 7, 'is_cityadministration' => 0, 'zones' => ['Afder Zone', 'Dhawa Zone', 'Dollo Zone', 'Erer Zone', 'Fafan Zone', 'Jarar Zone', 'Korahe Zone', 'Liben Zone', 'Nogob Zone', 'Shabelle Zone', 'Sitti Zone'], 'color' => '#FF3366'],
            ['name' => 'Benishangul-Gumuz', 'latitude' => '10.78028890', 'longitude' => '35.56578620', 'ordering' => 8, 'is_cityadministration' => 0, 'zones' => ['Asosa', 'Kamashi', 'Metekel'], 'color' => '#FF9933'],
            ['name' => 'Southern Nations, Nationalities, and Peoples', 'latitude' => '6.453652499423955', 'longitude' => '36.6605637', 'ordering' => 9, 'is_cityadministration' => 0, 'zones' => ['Gamo Zone', 'Gofa Zone', 'Gedeo Zone', 'Gurage Zone', 'Hadiya Zone', 'Kembata Tembaro Zone', 'Silte Zone', 'Debub Omo Zone', 'Wolayita Zone', 'Alaba Zone', 'Amaro special woreda', 'Alle Special Woreda', 'Basketo special woreda', 'Burji special woreda', 'Dirashe special woreda', 'Konso Zone', 'Yem special woreda'], 'color' => '#9966FF'],
            ['name' => 'Gambela', 'latitude' => '7.810264699391271', 'longitude' => '34.1822172', 'ordering' => 10, 'is_cityadministration' => 0, 'zones' => [], 'color' => '#FFCC33'],
            ['name' => 'Harari', 'latitude' => '9.290551699409448', 'longitude' => '42.188567199999994', 'ordering' => 11, 'is_cityadministration' => 0, 'zones' => [], 'color' => '#66FFFF'],
            ['name' => 'Sidama', 'latitude' => '7.06205', 'longitude' => '38.47635', 'ordering' => 12, 'is_cityadministration' => 0, 'zones' => ['Aleta Chuko', 'Aleta Wendo', 'Arbegona', 'Aroresa', 'Hawassa Zuria', 'Bensa', 'Bona Zuria', 'Boricha', 'Bursa', 'Chere', 'Dale', 'Dara', 'Gorche', 'Hawassa', 'Hula', 'Loko Abaya', 'Malga', 'Shebedino', 'Wensho', 'Wondo Genet'], 'color' => '#FF66CC'],
            ['name' => 'South West Ethiopia Peoples', 'latitude' => '6.7678', 'longitude' => '35.6344', 'ordering' => 13, 'is_cityadministration' => 0, 'zones' => ['	Bench Maji Zone', 'Dawro Zone', 'Keffa Zone', 'Sheka Zone', 'West Omo Zone', 'Konta Zone'], 'color' => '#CCFF66'],
            ['name' => 'Addis Ababa', 'latitude' => '8.99999999940141', 'longitude' => '38.75', 'ordering' => 1, 'is_cityadministration' => 1, 'zones' => ['Addis Ketema', '	Akaky Kaliti', 'Arada', 'Bole', 'Gullele', 'Kirkos', 'Kolfe Keranio', 'Lideta', 'Nifas Silk-Lafto', 'Yeka'], 'color' => '#66CCFF'],
            ['name' => 'Dire Dawa', 'latitude' => '9.591219599420022', 'longitude' => '41.97971909999999', 'ordering' => 2, 'is_cityadministration' => 1, 'zones' => ['Dire Dawa city', 'Gurgura'], 'color' => '#FF6666'],
        ];




        $cc = 0;
        foreach ($arr as $key => $val) {

            $cc++;
            \App\Models\Region::factory()->create(
                [
                    'name' => $val['name'],
                    'ordering' => $val['ordering'],
                    'latitude' => $val['latitude'],
                    'longitude' => $val['longitude'],
                    'is_cityadministration' => $val['is_cityadministration'],
                    'color' => $val["color"],
                    'created_by' => 1,
                    'updated_by' => 1,
                ]
            );



            $zones = $val['zones'];

            foreach ($zones as $zone) {

                \App\Models\Zone::factory()->create(
                    [
                        'name' => $zone,
                        'region_id' => $cc,
                        'created_by' => 1,
                        'updated_by' => 1,
                    ]
                );
            }
        };



        $countries = [
            ['name' => 'Afghanistan', 'nationality' => 'Afghan'],
            ['name' => 'Albania', 'nationality' => 'Albanian'],
            ['name' => 'Algeria', 'nationality' => 'Algerian'],
            ['name' => 'Andorra', 'nationality' => 'Andorran'],
            ['name' => 'Angola', 'nationality' => 'Angolan'],
            ['name' => 'Antigua and Barbuda', 'nationality' => 'Antiguan'],
            ['name' => 'Argentina', 'nationality' => 'Argentinian'],
            ['name' => 'Armenia', 'nationality' => 'Armenian'],
            ['name' => 'Australia', 'nationality' => 'Australian'],
            ['name' => 'Austria', 'nationality' => 'Austrian'],
            ['name' => 'Azerbaijan', 'nationality' => 'Azerbaijani'],
            ['name' => 'The Bahamas', 'nationality' => 'Bahamian'],
            ['name' => 'Bahrain', 'nationality' => 'Bahraini'],
            ['name' => 'Bangladesh', 'nationality' => 'Bangladeshi'],
            ['name' => 'Barbados', 'nationality' => 'Barbadian'],
            ['name' => 'Belarus', 'nationality' => 'Belarusian'],
            ['name' => 'Belgium', 'nationality' => 'Belgian'],
            ['name' => 'Belize', 'nationality' => 'Belizean'],
            ['name' => 'Benin', 'nationality' => 'Beninese'],
            ['name' => 'Bhutan', 'nationality' => 'Bhutanese'],
            ['name' => 'Bolivia', 'nationality' => 'Bolivian'],
            ['name' => 'Bosnia and Herzegovina', 'nationality' => 'Bosnian'],
            ['name' => 'Botswana', 'nationality' => 'Motswana'],
            ['name' => 'Brazil', 'nationality' => 'Brazilian'],
            ['name' => 'Brunei', 'nationality' => 'Bruneian'],
            ['name' => 'Bulgaria', 'nationality' => 'Bulgarian'],
            ['name' => 'Burkina Faso', 'nationality' => 'Burkinabe'],
            ['name' => 'Burundi', 'nationality' => 'Burundian'],
            ['name' => 'Cabo Verde', 'nationality' => 'Cape Verdean'],
            ['name' => 'Cambodia', 'nationality' => 'Cambodian'],
            ['name' => 'Cameroon', 'nationality' => 'Cameroonian'],
            ['name' => 'Canada', 'nationality' => 'Canadian'],
            ['name' => 'Central African Republic', 'nationality' => 'Central African'],
            ['name' => 'Chad', 'nationality' => 'Chadian'],
            ['name' => 'Chile', 'nationality' => 'Chilean'],
            ['name' => 'China', 'nationality' => 'Chinese'],
            ['name' => 'Colombia', 'nationality' => 'Colombian'],
            ['name' => 'Comoros', 'nationality' => 'Comoran'],
            ['name' => 'Congo, Democratic Republic of the', 'nationality' => 'Congolese'],
            ['name' => 'Congo, Republic of the', 'nationality' => 'Congolese'],
            ['name' => 'Costa Rica', 'nationality' => 'Costa Rican'],
            ['name' => 'Côte d’Ivoire', 'nationality' => 'Ivorian'],
            ['name' => 'Croatia', 'nationality' => 'Croatian'],
            ['name' => 'Cuba', 'nationality' => 'Cuban'],
            ['name' => 'Cyprus', 'nationality' => 'Cypriot'],
            ['name' => 'Czech Republic', 'nationality' => 'Czech'],
            ['name' => 'Denmark', 'nationality' => 'Danish'],
            ['name' => 'Djibouti', 'nationality' => 'Djiboutian'],
            ['name' => 'Dominica', 'nationality' => 'Dominican'],
            ['name' => 'Dominican Republic', 'nationality' => 'Dominican'],
            ['name' => 'East Timor (Timor-Leste)', 'nationality' => 'Timorese'],
            ['name' => 'Ecuador', 'nationality' => 'Ecuadorian'],
            ['name' => 'Egypt', 'nationality' => 'Egyptian'],
            ['name' => 'El Salvador', 'nationality' => 'Salvadoran'],
            ['name' => 'Equatorial Guinea', 'nationality' => 'Equatorial Guinean'],
            ['name' => 'Eritrea', 'nationality' => 'Eritrean'],
            ['name' => 'Estonia', 'nationality' => 'Estonian'],
            ['name' => 'Eswatini', 'nationality' => 'Swazi'],
            ['name' => 'Ethiopia', 'nationality' => 'Ethiopian'],
            ['name' => 'Fiji', 'nationality' => 'Fijian'],
            ['name' => 'Finland', 'nationality' => 'Finnish'],
            ['name' => 'France', 'nationality' => 'French'],
            ['name' => 'Gabon', 'nationality' => 'Gabonese'],
            ['name' => 'The Gambia', 'nationality' => 'Gambian'],
            ['name' => 'Georgia', 'nationality' => 'Georgian'],
            ['name' => 'Germany', 'nationality' => 'German'],
            ['name' => 'Ghana', 'nationality' => 'Ghanaian'],
            ['name' => 'Greece', 'nationality' => 'Greek'],
            ['name' => 'Grenada', 'nationality' => 'Grenadian'],
            ['name' => 'Guatemala', 'nationality' => 'Guatemalan'],
            ['name' => 'Guinea', 'nationality' => 'Guinean'],
            ['name' => 'Guinea-Bissau', 'nationality' => 'Bissau-Guinean'],
            ['name' => 'Guyana', 'nationality' => 'Guyanese'],
            ['name' => 'Haiti', 'nationality' => 'Haitian'],
            ['name' => 'Honduras', 'nationality' => 'Honduran'],
            ['name' => 'Hungary', 'nationality' => 'Hungarian'],
            ['name' => 'Iceland', 'nationality' => 'Icelandic'],
            ['name' => 'India', 'nationality' => 'Indian'],
            ['name' => 'Indonesia', 'nationality' => 'Indonesian'],
            ['name' => 'Iran', 'nationality' => 'Iranian'],
            ['name' => 'Iraq', 'nationality' => 'Iraqi'],
            ['name' => 'Ireland', 'nationality' => 'Irish'],
            ['name' => 'Israel', 'nationality' => 'Israeli'],
            ['name' => 'Italy', 'nationality' => 'Italian'],
            ['name' => 'Jamaica', 'nationality' => 'Jamaican'],
            ['name' => 'Japan', 'nationality' => 'Japanese'],
            ['name' => 'Jordan', 'nationality' => 'Jordanian'],
            ['name' => 'Kazakhstan', 'nationality' => 'Kazakhstani'],
            ['name' => 'Kenya', 'nationality' => 'Kenyan'],
            ['name' => 'Kiribati', 'nationality' => 'I-Kiribati'],
            ['name' => 'Korea, North', 'nationality' => 'North Korean'],
            ['name' => 'Korea, South', 'nationality' => 'South Korean'],
            ['name' => 'Kosovo', 'nationality' => 'Kosovar'],
            ['name' => 'Kuwait', 'nationality' => 'Kuwaiti'],
            ['name' => 'Kyrgyzstan', 'nationality' => 'Kyrgyzstani'],
            ['name' => 'Laos', 'nationality' => 'Laotian'],
            ['name' => 'Latvia', 'nationality' => 'Latvian'],
            ['name' => 'Lebanon', 'nationality' => 'Lebanese'],
            ['name' => 'Lesotho', 'nationality' => 'Mosotho'],
            ['name' => 'Liberia', 'nationality' => 'Liberian'],
            ['name' => 'Libya', 'nationality' => 'Libyan'],
            ['name' => 'Liechtenstein', 'nationality' => 'Liechtensteiner'],
            ['name' => 'Lithuania', 'nationality' => 'Lithuanian'],
            ['name' => 'Luxembourg', 'nationality' => 'Luxembourger'],
            ['name' => 'Madagascar', 'nationality' => 'Malagasy'],
            ['name' => 'Malawi', 'nationality' => 'Malawian'],
            ['name' => 'Malaysia', 'nationality' => 'Malaysian'],
            ['name' => 'Maldives', 'nationality' => 'Maldivian'],
            ['name' => 'Mali', 'nationality' => 'Malian'],
            ['name' => 'Malta', 'nationality' => 'Maltese'],
            ['name' => 'Marshall Islands', 'nationality' => 'Marshallese'],
            ['name' => 'Mauritania', 'nationality' => 'Mauritanian'],
            ['name' => 'Mauritius', 'nationality' => 'Mauritian'],
            ['name' => 'Mexico', 'nationality' => 'Mexican'],
            ['name' => 'Micronesia, Federated States of', 'nationality' => 'Micronesian'],
            ['name' => 'Moldova', 'nationality' => 'Moldovan'],
            ['name' => 'Monaco', 'nationality' => 'Monacan'],
            ['name' => 'Mongolia', 'nationality' => 'Mongolian'],
            ['name' => 'Montenegro', 'nationality' => 'Montenegrin'],
            ['name' => 'Morocco', 'nationality' => 'Moroccan'],
            ['name' => 'Mozambique', 'nationality' => 'Mozambican'],
            ['name' => 'Myanmar (Burma)', 'nationality' => 'Burmese'],
            ['name' => 'Namibia', 'nationality' => 'Namibian'],
            ['name' => 'Nauru', 'nationality' => 'Nauruan'],
            ['name' => 'Nepal', 'nationality' => 'Nepali'],
            ['name' => 'Netherlands', 'nationality' => 'Dutch'],
            ['name' => 'New Zealand', 'nationality' => 'New Zealander'],
            ['name' => 'Nicaragua', 'nationality' => 'Nicaraguan'],
            ['name' => 'Niger', 'nationality' => 'Nigerien'],
            ['name' => 'Nigeria', 'nationality' => 'Nigerian'],
            ['name' => 'North Macedonia', 'nationality' => 'Macedonian'],
            ['name' => 'Norway', 'nationality' => 'Norwegian'],
            ['name' => 'Oman', 'nationality' => 'Omani'],
            ['name' => 'Pakistan', 'nationality' => 'Pakistani'],
            ['name' => 'Palau', 'nationality' => 'Palauan'],
            ['name' => 'Panama', 'nationality' => 'Panamanian'],
            ['name' => 'Papua New Guinea', 'nationality' => 'Papua New Guinean'],
            ['name' => 'Paraguay', 'nationality' => 'Paraguayan'],
            ['name' => 'Peru', 'nationality' => 'Peruvian'],
            ['name' => 'Philippines', 'nationality' => 'Filipino'],
            ['name' => 'Poland', 'nationality' => 'Polish'],
            ['name' => 'Portugal', 'nationality' => 'Portuguese'],
            ['name' => 'Qatar', 'nationality' => 'Qatari'],
            ['name' => 'Romania', 'nationality' => 'Romanian'],
            ['name' => 'Russia', 'nationality' => 'Russian'],
            ['name' => 'Rwanda', 'nationality' => 'Rwandan'],
            ['name' => 'Saint Kitts and Nevis', 'nationality' => 'Kittitian/Nevisian'],
            ['name' => 'Saint Lucia', 'nationality' => 'Saint Lucian'],
            ['name' => 'Saint Vincent and the Grenadines', 'nationality' => 'Saint Vincentian'],
            ['name' => 'Samoa', 'nationality' => 'Samoan'],
            ['name' => 'San Marino', 'nationality' => 'Sammarinese'],
            ['name' => 'Sao Tome and Principe', 'nationality' => 'Sao Tomean'],
            ['name' => 'Saudi Arabia', 'nationality' => 'Saudi'],
            ['name' => 'Senegal', 'nationality' => 'Senegalese'],
            ['name' => 'Serbia', 'nationality' => 'Serbian'],
            ['name' => 'Seychelles', 'nationality' => 'Seychellois'],
            ['name' => 'Sierra Leone', 'nationality' => 'Sierra Leonean'],
            ['name' => 'Singapore', 'nationality' => 'Singaporean'],
            ['name' => 'Slovakia', 'nationality' => 'Slovak'],
            ['name' => 'Slovenia', 'nationality' => 'Slovenian'],
            ['name' => 'Solomon Islands', 'nationality' => 'Solomon Islander'],
            ['name' => 'Somalia', 'nationality' => 'Somali'],
            ['name' => 'South Africa', 'nationality' => 'South African'],
            ['name' => 'Spain', 'nationality' => 'Spanish'],
            ['name' => 'Sri Lanka', 'nationality' => 'Sri Lankan'],
            ['name' => 'Sudan', 'nationality' => 'Sudanese'],
            ['name' => 'Sudan, South', 'nationality' => 'South Sudanese'],
            ['name' => 'Suriname', 'nationality' => 'Surinamese'],
            ['name' => 'Sweden', 'nationality' => 'Swedish'],
            ['name' => 'Switzerland', 'nationality' => 'Swiss'],
            ['name' => 'Syria', 'nationality' => 'Syrian'],
            ['name' => 'Taiwan', 'nationality' => 'Taiwanese'],
            ['name' => 'Tajikistan', 'nationality' => 'Tajik'],
            ['name' => 'Tanzania', 'nationality' => 'Tanzanian'],
            ['name' => 'Thailand', 'nationality' => 'Thai'],
            ['name' => 'Togo', 'nationality' => 'Togolese'],
            ['name' => 'Tonga', 'nationality' => 'Tongan'],
            ['name' => 'Trinidad and Tobago', 'nationality' => 'Trinidadian/Tobagonian'],
            ['name' => 'Tunisia', 'nationality' => 'Tunisian'],
            ['name' => 'Turkey', 'nationality' => 'Turkish'],
            ['name' => 'Turkmenistan', 'nationality' => 'Turkmen'],
            ['name' => 'Tuvalu', 'nationality' => 'Tuvaluan'],
            ['name' => 'Uganda', 'nationality' => 'Ugandan'],
            ['name' => 'Ukraine', 'nationality' => 'Ukrainian'],
            ['name' => 'United Arab Emirates', 'nationality' => 'Emirati'],
            ['name' => 'United Kingdom', 'nationality' => 'British'],
            ['name' => 'United States', 'nationality' => 'American'],
            ['name' => 'Uruguay', 'nationality' => 'Uruguayan'],
            ['name' => 'Uzbekistan', 'nationality' => 'Uzbek'],
            ['name' => 'Vanuatu', 'nationality' => 'Ni-Vanuatu'],
            ['name' => 'Vatican City', 'nationality' => 'Vatican'],
            ['name' => 'Venezuela', 'nationality' => 'Venezuelan'],
            ['name' => 'Vietnam', 'nationality' => 'Vietnamese'],
            ['name' => 'Yemen', 'nationality' => 'Yemeni'],
            ['name' => 'Zambia', 'nationality' => 'Zambian'],
            ['name' => 'Zimbabwe', 'nationality' => 'Zimbabwean'],
        ];




        foreach ($countries as $country) {

            \App\Models\Country::factory()->create(
                [
                    'name' => $country['name'],
                    'nationality' => $country['nationality'],
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
