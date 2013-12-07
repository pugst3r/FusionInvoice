<?php namespace FI\Storage\Eloquent\Repositories;

use FI\Storage\Eloquent\Models\InvoiceGroup;

class InvoiceGroupRepository implements \FI\Storage\Interfaces\InvoiceGroupRepositoryInterface {
	
	/**
	 * Get all records
	 * @return InvoiceGroup
	 */
	public function all()
	{
		return InvoiceGroup::orderBy('name')->all();
	}

	/**
	 * Get a paged list of records
	 * @param  int $page
	 * @param  int  $numPerPage
	 * @return InvoiceGroup
	 */
	public function getPaged($page = 1, $numPerPage = null)
	{
		\DB::getPaginator()->setCurrentPage($page);
		return InvoiceGroup::paginate($numPerPage ?: \Config::get('defaultNumPerPage'));
	}

	/**
	 * Get a single record
	 * @param  int $id
	 * @return InvoiceGroup
	 */
	public function find($id)
	{
		return InvoiceGroup::find($id);
	}

	/**
	 * Generate an invoice number
	 * @param  int $id
	 * @return string
	 */
	public function generateNumber($id)
	{
		$group = InvoiceGroup::find($id);

		$number = $group->next_id;

		if ($group->prefix) $number        = $group->prefix . $number;
		if ($group->prefix_year) $number  .= date('Y');
		if ($group->prefix_month) $number .= date('m');
		if ($group->left_pad) $number      = str_pad($number, $group->left_pad, '0', STR_PAD_LEFT);

		return $number;
	}

	/**
	 * Increment the next id after an invoice is created
	 * @param  int $id
	 * @return void
	 */
	public function incrementNextId($id)
	{
		$group          = InvoiceGroup::find($id);
		$group->next_id = $group->next_id + 1;
		$group->save();
	}

	/**
	 * Get a list of records formatted for dropdown
	 * @return array
	 */
	public function lists()
	{
		return InvoiceGroup::orderBy('name')->lists('name', 'id');
	}
	
	/**
	 * Create a record
	 * @param  string $name
	 * @param  int $nextId
	 * @param  int $leftPad
	 * @param  string $prefix
	 * @param  bool $prefixYear
	 * @param  bool $prefixMonth
	 * @return void
	 */
	public function create($name, $nextId, $leftPad, $prefix, $prefixYear, $prefixMonth)
	{
		InvoiceGroup::create(
			array(
				'name'         => $name,
				'next_id'      => $nextId,
				'left_pad'     => $leftPad,
				'prefix'       => $prefix,
				'prefix_year'  => $prefixYear,
				'prefix_month' => $prefixMonth
			)
		);
	}
	
	/**
	 * Update a record
	 * @param  int $id
	 * @param  string $name
	 * @param  int $nextId
	 * @param  int $leftPad
	 * @param  string $prefix
	 * @param  bool $prefixYear
	 * @param  bool $prefixMonth
	 * @return void
	 */
	public function update($id, $name, $nextId, $leftPad, $prefix, $prefixYear, $prefixMonth)
	{
		$invoiceGroup = InvoiceGroup::find($id);

		$invoiceGroup->fill(
			array(
				'name'         => $name,
				'next_id'      => $nextId,
				'left_pad'     => $leftPad,
				'prefix'       => $prefix,
				'prefix_year'  => $prefixYear,
				'prefix_month' => $prefixMonth
			)
		);

		$invoiceGroup->save();
	}
	
	/**
	 * Delete a record
	 * @param  int $id
	 * @return void
	 */
	public function delete($id)
	{
		InvoiceGroup::destroy($id);
	}
	
}