<?php

namespace PHPAccounting\Quickbooks\Message\Contacts\Responses;

/**
 * Update Contact(s) Response.
 *
 * QB's Update endpoint returns the same Customer shape as Get/Create, so the
 * parsing is identical to CreateContactResponse. A dedicated class exists for
 * symmetry with the other entities (Accounts, Invoices, InventoryItems all
 * have their own Update*Response) and so callers can type-hint the response
 * separately from the create path.
 *
 * @package PHPAccounting\Quickbooks\Message\Contacts\Responses
 */
class UpdateContactResponse extends CreateContactResponse
{
}
