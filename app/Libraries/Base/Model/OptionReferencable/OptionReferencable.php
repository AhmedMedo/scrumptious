<?php

namespace App\Libraries\Base\Model\OptionReferencable;

use App\Component\Supplier\DomainModel\Business\Enum\SupplierOptionReferenceTypeEnum;
use Illuminate\Database\Eloquent\Relations\MorphMany;

interface OptionReferencable
{
    public function supplierOptionReferenceType(): SupplierOptionReferenceTypeEnum;

    public function supplierOptionReferences(): MorphMany;
}
