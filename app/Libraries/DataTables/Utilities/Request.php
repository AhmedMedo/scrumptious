<?php

/**
 * Date: 02/09/2019
 * Time: 15:17
 *
 * @author Artur Bartczak <artur.bartczak@proexe.pl>
 */

namespace App\Libraries\DataTables\Utilities;

use Yajra\DataTables\Utilities\Request as BaseRequest;

class Request extends BaseRequest
{
    public function isExport(): bool
    {
        return (bool) $this->request->input('is_export', false);
    }
}
