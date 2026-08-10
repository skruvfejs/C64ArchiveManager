<?php

declare(strict_types=1);

namespace App\Core;

final class Permission
{
    private function __construct()
    {
    }

    /*
     * Basic permissions
     */
    public const VIEW = 'view';
    public const DOWNLOAD = 'download';

    /*
     * Archive
     */
    public const IMPORT = 'import';
    public const EDIT = 'edit';
    public const DELETE = 'delete';

    /*
     * Administration
     */
    public const MANAGE_USERS = 'manage_users';
    public const MANAGE_ROLES = 'manage_roles';
    public const MANAGE_TAGS = 'manage_tags';
    public const MANAGE_SYSTEM = 'manage_system';
    public const MANAGE_IMPORTS = 'manage_imports';
    public const VIEW_LOGS = 'view_logs';

    /*
     * Future
     */
    public const MANAGE_PLUGINS = 'manage_plugins';
}

